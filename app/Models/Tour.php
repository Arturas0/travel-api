<?php

namespace App\Models;

use App\QueryBuilders\Tour\TourQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'integer',
    ];

    public function newEloquentBuilder($query): Builder
    {
        return new TourQueryBuilder($query);
    }

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
}
