<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'travel_id',
        'name',
        'start_date',
        'end_date',
        'price',
    ];
}
