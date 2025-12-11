<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// HAPUS SEMUA: use App\Models\User;
// HAPUS SEMUA: use App\Models\Field;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'field_id', 'start_time', 'end_time', 
        'total_hours', 'total_price', 'status'
    ]; 
    
    // Relasi: Booking dimiliki oleh User
    public function user()
    {
        // Panggil Model User menggunakan String Namespace penuh
        return $this->belongsTo('App\Models\User'); 
    }

    // Relasi: Booking terikat pada Field
    public function field()
    {
        // Panggil Model Field menggunakan String Namespace penuh
        return $this->belongsTo('App\Models\Field');
    }
}