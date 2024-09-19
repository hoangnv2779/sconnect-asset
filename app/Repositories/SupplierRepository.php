<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class SupplierRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Supplier::class;
    }

    public function getListSupplierByFilters($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()
            ->select($columns);

        if (!empty($filters['name'])) {
            $query->where('supplier.name', 'like', $filters['name'] . '%');
        }

        if (!empty($filters['industry_id'])) {
            $query->leftJoin('supplier_asset_industries','supplier_asset_industries.supplier_id', 'supplier.id');

            $query->whereIn('supplier_asset_industries.industries_id', Arr::wrap($filters['industry_id']));
        }

        if (!empty($filters['level'])) {
            $query->whereIn('supplier.level', Arr::wrap($filters['level']));
        }

        return $query->get();
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()
            ->select($columns)
            ->with($with)
            ->orderBy('created_at', 'desc');

        if (!empty($filters['ids'])) {
            $query->whereIn('id', $filters['ids']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }
}