<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NativeBL\Repository;

use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Contracts\Service\CrudBoard\CrudFormHandlerInterface;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This interface defines blueprints of NativeBL Repository.
 *
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

abstract class AbstractNativeRepository implements NativeRepositoryInterface, CrudFormHandlerInterface, CrudGridLoaderInterface
{
    public function all() : iterable
    {
        return $this->getModelFqcn()::all();
    }

    public function findOrFail(int | string $id) : object
    {
        return $this->getModelFqcn()::findOrFail($id);
    }
    public function find($id) 
    {
       return $this->getModelFqcn()::find($id);
    }

    public function findOneBy(string $field, $value)
    {
        return $this->getModelFqcn()::where($field,$value)->first();
    }

    protected function findBy($field,$value) : iterable
    {
        return $this->getModelFqcn()::where($field,$value)->get();
    }

    public function saveFormData(array $data = [])
    {
        $class =   $this->getModelFqcn();
        $model = new $class;
        foreach($data as $key=>$value){
             $model->$key = $value;
        }
        $model->save();
       return $model;
    }

    public function crudShow(int|string $id): ?\ArrayAccess
    {
       return $this->find($id);
    }

    public function getRecordForEdit(int|string $id) : object
    {
        $record = $this->findOrFail($id);
        return $record;
    }

    public function getGridData(array $filters=[]): ?iterable
    {
        return null;
    }

    public function getGridQuery(): ?Builder
    {
        return null;
    }
    public function getGridPagination(): ?Paginator
    {
        return null;
    }
    public function getGridCursorPaginator(array $filters): ?CursorPaginator
    {
        return null;
    }
    public function getGridPaginator(array $filters): ?LengthAwarePaginator
    {
        return null;
    }
    public function applyFilterQuery(Builder $query, array $filters): Builder
    {
        foreach($filters as $field=>$value){
            $query->where($field,$value);
        }
        return $query;
    }

    public function applyFilterData(Collection $data, array $filters) : Collection
    {
        foreach($filters as $field=>$value){
            $filtered = $data->where($field,$value);
            $data = $filtered;
        }
        return $data;
    }

}
