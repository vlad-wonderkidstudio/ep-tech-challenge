<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'postcode',
    ];

    protected $appends = [
        'url',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class);
    }

    public function getBookingsCountAttribute(): int
    {
        return $this->bookings->count();
    }

    public function getBookingsSortedAttribute(): Collection
    {
        return $this->bookings()
            ->orderBy('end', 'desc')
            ->get();
    }

    public function getJournalsSortedAttribute(): Collection
    {
        return $this->journals()
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getUrlAttribute(): string
    {
        return route('clients.show', ['client' => $this->id]);
    }
}
