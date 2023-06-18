<?php

declare(strict_types=1);

namespace App\QueryBuilders\Tour;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModelClass of Model
 * @extends Builder<Model>
 */
class TourQueryBuilder extends Builder
{
    public function searchByPriceFrom(int|null $priceFrom): self
    {
        return $this->when(
            value: $priceFrom,
            callback: fn ($query) => $query->where('price', '>=', $priceFrom * 100));
    }

    public function searchByPriceTo(int|null $priceTo): self
    {
        return $this->when(
            value: $priceTo,
            callback: fn ($query) => $query->where('price', '<=', $priceTo * 100));
    }

    public function searchByDateFrom(string|null $date): self
    {
        return $this->when(
            value: $date,
            callback: fn ($query) => $query->where('start_date', '>=', $date));
    }

    public function searchByDateTo(string|null $date): self
    {
        return $this->when(
            value: $date,
            callback: fn ($query) => $query->where('start_date', '<=', $date));
    }

    public function sortByPrice(string|null $order): self
    {
        return $this->when(
            value: $order,
            callback: fn ($query) => $query->orderBy('price', $order));
    }

    public function sortByStartDateAsc(): self
    {
        return $this->orderBy('start_date');
    }
}
