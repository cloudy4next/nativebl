<?php declare(strict_types=1);

namespace NativeBL\Controller;

use NativeBL\Contracts\Controller\NativeControllerInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Contracts\Service\CrudBoard\CrudGridInterface;
use NativeBL\Contracts\Service\CrudBoard\CrudFormHandlerInterface;
use NativeBL\Field\ButtonField;
use NativeBL\Form\CrudForm;
use NativeBL\Services\CrudBoard\CrudBoard;

abstract class AbstractNativeController extends Controller implements NativeControllerInterface
{
 

   public function configureFormField(): iterable
   {
      return [];
   }

   public function configureGridColumn(): iterable
   {
      return [];
   }

   
   public function configureGridRowActions(): iterable
   {
      return [];
   } 


   public function initGrid(array $columns, mixed ...$params)
   {
       CrudBoardFacade::createGrid($this->getRepository(),$params)
         ->addColumns($columns)
         ->addActions($this->configureActions());
         ;
       $this->configureFilter();
       return $this;
   }
   
   protected function getForm(array $fields)
   {
       return  CrudBoardFacade::createForm($fields)
               ->setFormStat(CrudForm::STAT_NEW)
               ->setActions($this->configureActions());    
   }

   protected function getFilter(array $fields)
   {
       return  CrudBoardFacade::getGrid()
               ->getFilter()
               ->addFields($fields)
               ->setFormStat(CrudForm::STAT_NEW)
               ->setActions($this->configureActions())
               ->assignQueryData()
               ;    
   }

   protected function initCreate()
   {
      return $this->configureForm();
   }


   protected function initStore(Request $request)
   {
       $this->configureForm();
       return CrudBoardFacade::getForm()
            ->setFormHandler($this->getRepository())
            ->processData($request);
   }

   protected function getDefaultCrudActions(): iterable 
   {
      return [
         ButtonField::init(ButtonField::EDIT)->linkToRoute('customer_edit')->addCssClass('fa-file-lines'),
         ButtonField::init(ButtonField::DELETE)->linkToRoute('customer_delete'),
         ButtonField::init(ButtonField::DETAIL)->linkToRoute('customer_detail'),
         ButtonField::init('new','new')->linkToRoute('customer_create')->createAsCrudBoardAction(),
        // ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')

       ];
   }

   protected function initEdit(int|string $id)
   {
        $repo = $this->getRepository();
        $row = $repo->getRecordForEdit($id);
        $this->configureForm();
        CrudBoardFacade::getForm()
        ->setFormStat(CrudForm::STAT_EDIT)
        ->setData($row);
   }

   protected function initShow(int|string $id, array $fields)  
   {
      CrudBoardFacade::setRepository($this->getRepository())
      ->createShow($id,$fields)
      ->addActions($this->configureActions());
   }

   // private function prepareActions()
   // {
   //    $actions = [];
   //     foreach($this->configureActions() as $action){
   //       switch($action->getName()){ 
   //       case  ButtonField::EDIT :      
   //          $action->setCssClass('btn-primary')
   //          ->addIcon('<i class="fas fa-pen-to-square"></i>');
   //          break;
         
   //     }
   // }

   public function configureActions(): iterable
   {
     return $this->getDefaultCrudActions();
   }


}
