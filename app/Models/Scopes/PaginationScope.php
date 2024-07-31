<?php

namespace App\Models\Scopes;

use App\Http\Requests\PageRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

/**
 * @property Builder usePage()
 */
trait PaginationScope
{
    /**
     * 添加一个分页的逻辑
     *
     * @param Builder $builder
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function scopeUsePage(Builder $builder): \Illuminate\Contracts\Pagination\Paginator
    {
        /**
         * @var $request PageRequest
         */
        $request = App::make(PageRequest::class);

        return $builder->paginate($request->limit());
    }
}
