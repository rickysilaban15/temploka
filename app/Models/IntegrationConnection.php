<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'integration_id',
        'connection_status',
        'connection_data'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}