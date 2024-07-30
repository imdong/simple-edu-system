<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    /**
     * @var Builder|Model
     */
    protected Model|Builder $model;

    /**
     * @param Invoice|Builder $invoice
     */
    public function __construct(Model|Builder $invoice)
    {
        $this->model = $invoice;
    }

    /**
     * @return Model|Invoice|Builder
     */
    public function getModel(): Model|Invoice|Builder
    {
        return $this->model;
    }

}
