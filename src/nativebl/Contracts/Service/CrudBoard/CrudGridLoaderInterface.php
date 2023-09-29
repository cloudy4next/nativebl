<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NativeBL\Contracts\Service\CrudBoard;

/**
 * This interface defines blueprints of NativeBL CRUD grid loader.  
 * It will ensure to provide record for CrudBoard grid.
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface CrudGridLoaderInterface
{
   public function getGridData(array $filters = []): ?iterable;
   public function getGridQuery() : ?Builder;
   public function getGridPaginator(array $filters): ?LengthAwarePaginator;
   public function getGridCursorPaginator(array $filters): ?CursorPaginator;
   public function applyFilterQuery(Builder $query, array $filters): ?Builder;
   public function applyFilterData(Collection $data, array $filters) : Collection;
}