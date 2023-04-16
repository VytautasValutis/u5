<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public function accountClient() 
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

}
