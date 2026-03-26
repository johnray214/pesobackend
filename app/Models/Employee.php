<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_initial',
        'last_name',
        'suffix',
        'full_name',
        'job_title',
        'office',
        'id_number',
        'id_display',
        'card_year',
        'card_seq',
        'photo_path',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id_number' => 'integer',
            'card_year' => 'integer',
            'card_seq' => 'integer',
        ];
    }

    /**
     * Public URL for the stored photo, or null.
     */
    public function photoUrl(): ?string
    {
        if ($this->photo_path === null || $this->photo_path === '') {
            return null;
        }

        return asset('storage/'.$this->photo_path);
    }

    /**
     * Display code on the card (snapshot or derived from id_number).
     */
    public function effectiveIdDisplay(): string
    {
        if ($this->id_display !== null && $this->id_display !== '') {
            return $this->id_display;
        }

        if ($this->card_year && $this->card_seq) {
            $yy = sprintf('%02d', (int) $this->card_year % 100);

            return $yy.'-'.str_pad((string) $this->card_seq, 3, '0', STR_PAD_LEFT);
        }

        return sprintf('%02d', (int) now()->format('Y') % 100).'-'.str_pad((string) ($this->id_number ?? 0), 3, '0', STR_PAD_LEFT);
    }
}