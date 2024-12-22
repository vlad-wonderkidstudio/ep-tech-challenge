<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'date', 'text'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
