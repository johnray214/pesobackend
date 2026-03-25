<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use Illuminate\Http\Request;

class PublicMapController extends Controller
{
    /**
     * Lightweight map endpoint for mobile app.
     * Returns employers with coordinates and their open job listings.
     */
    public function employers(Request $request)
    {
        $employers = Employer::query()
            ->where('map_visible', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', 'verified')
            ->with(['jobListings' => function ($q) {
                $q->where('status', 'open')
                    ->select('id', 'employer_id', 'title', 'type', 'location', 'salary_range', 'description', 'posted_date', 'created_at');
            }])
            ->orderBy('company_name')
            ->get()
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'company_name' => $e->company_name,
                    'address_full' => $e->address_full,
                    'city' => $e->city,
                    'province' => $e->province,
                    'latitude' => $e->latitude,
                    'longitude' => $e->longitude,
                    'job_listings' => $e->jobListings->values(),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $employers,
        ]);
    }
}

