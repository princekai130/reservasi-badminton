<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    // ...
    protected $guarded = ['id']; // Gunakan fillable atau guarded

    // Relasi: Field memiliki banyak Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
