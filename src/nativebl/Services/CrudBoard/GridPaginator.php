<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NativeBL\Services\CrudBoard;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
 /**
  * This is an abstract form  class for crud board
  *
  * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
  */
 
  class GridPaginator 
  {

     public function __construct( private readonly int $recordPerPage = 3)
     {}

     public function paginate(Collection $data, $options = []): LengthAwarePaginator
     {
        $page = Paginator::resolveCurrentPage() ?? 1;
        return new LengthAwarePaginator($data->forPage($page, $this->recordPerPage), $data->count(), $this->recordPerPage, $page, $options);
     }

     public function paginateQuery(Builder $queryBuilder) :LengthAwarePaginator
     {
       return $queryBuilder->paginate($this->recordPerPage);
     }

  }