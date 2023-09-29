<?php declare(strict_types=1);

namespace NativeBL\Services\CrudBoard;

use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Contracts\Service\CrudBoard\CrudBoardInterface;
use NativeBL\Contracts\Service\CrudBoard\CrudGridInterface;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use NativeBL\Contracts\Service\CrudBoard\CrudShowInterface;
use NativeBL\Form\AbstractForm;
use NativeBL\Form\CrudForm;

abstract class AbstractCrudBoard implements  CrudBoardInterface
{
    protected NativeRepositoryInterface $repo;
    private  CrudGridInterface $grid;
    private AbstractForm $form;
    private CrudShowInterface $crudShow;
    private int $pagination;

    
    abstract protected function getRecordForShow(int|string $id): ?\ArrayAccess;

    public function createGrid(CrudGridLoaderInterface $dataLoader, array $params=[]): CrudGridInterface 
    {
        $this->grid = CrudGrid::init($dataLoader,$params);
        return $this->grid;
    }

    public function getRepository() : NativeRepositoryInterface
    {
         return $this->repo;
    }

    public function getGrid(): CrudGridInterface
    {
        return  $this->grid;
    }

    public function addGridColumns(array $columns) 
    {
        $this->grid->addColumns($columns);
        return $this;
    }

    public function getForm() : AbstractForm
    {
        return $this->form;
    }

   
    public function addGridActions(array $actions = []) 
    {
        $this->grid->addActions($actions);
        return $this;
    }

    protected function defaultGridRowActions()
    {
        
    }

    public function createForm(array $fields) : CrudForm
    {
        $this->form = (new CrudForm())
        ->addFields($fields);
        return $this->form;
    }

 

    public function createShow(string|int $id, array $fields)
    {
        $record = $this->getRecordForShow($id);
        if($record === null) {
             throw new \Exception("No record is found for details");
        }
        $this->crudShow = CrudShow::init($fields, $record);
        return $this->crudShow;
    }

    public function getCrudShow(): CrudShow
    {
        return $this->crudShow;
    }

}