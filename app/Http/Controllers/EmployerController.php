<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Latest registration for this full name (to show last position and enforce “new card only if position changed”).
     */
    public function showByFullName(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
        ]);

        $name = trim($validated['full_name']);
        $employee = Employee::query()
            ->where('full_name', $name)
            ->orderByDesc('id')
            ->first();

        if ($employee === null) {
            return response()->json(['message' => 'No employee found for this name.'], 404);
        }

        $registeredTitles = Employee::query()
            ->where('full_name', $name)
            ->orderBy('id')
            ->pluck('job_title')
            ->all();

        return response()->json(array_merge(
            $this->serializeEmployee($employee),
            ['registered_job_titles' => $registeredTitles],
        ));
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        return response()->json(
            Employee::query()
                ->orderByDesc('id')
                ->paginate($perPage)
        );
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $validated = $request->safe()->only(['full_name', 'job_title', 'office']);
        $base = is_array($validated) ? $validated : $validated->all();

        $employee = DB::transaction(function () use ($request, $base) {
            $cardYear = (int) now()->year;
            $yy = now()->format('y');

            $nextSeq = (int) Employee::query()
                ->where('card_year', $cardYear)
                ->lockForUpdate()
                ->max('card_seq');
            $nextSeq = max(0, $nextSeq) + 1;

            $nextIdNum = (int) Employee::query()->lockForUpdate()->max('id_number');
            $nextIdNum = max(0, $nextIdNum) + 1;

            $data = $base;
            $data['card_year'] = $cardYear;
            $data['card_seq'] = $nextSeq;
            $data['id_number'] = $nextIdNum;
            $data['id_display'] = $yy.'-'.str_pad((string) $nextSeq, 3, '0', STR_PAD_LEFT);

            if ($request->hasFile('photo')) {
                $data['photo_path'] = $request->file('photo')->store('employee-photos', 'public');
            }

            return Employee::query()->create($data);
        });

        return response()->json($this->serializeEmployee($employee), 201);
    }

    public function show(Employee $employee): JsonResponse
    {
        return response()->json($this->serializeEmployee($employee));
    }

    public function update(Request $request, Employee $employee): JsonResponse
    {
        return response()->json([
            'message' => 'ID cards cannot be updated. Submit a new registration when your official position changes.',
        ], 403);
    }

    public function destroy(Employee $employee): JsonResponse
    {
        if ($employee->photo_path) {
            Storage::disk('public')->delete($employee->photo_path);
        }

        $employee->delete();

        return response()->json(null, 204);
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeEmployee(Employee $employee): array
    {
        return [
            'id' => $employee->id,
            'full_name' => $employee->full_name,
            'job_title' => $employee->job_title,
            'office' => $employee->office,
            'id_number' => $employee->id_number,
            'id_display' => $employee->id_display,
            'effective_id_display' => $employee->effectiveIdDisplay(),
            'photo_path' => $employee->photo_path,
            'photo_url' => $employee->photoUrl(),
            'created_at' => $employee->created_at?->toIso8601String(),
            'updated_at' => $employee->updated_at?->toIso8601String(),
        ];
    }
}