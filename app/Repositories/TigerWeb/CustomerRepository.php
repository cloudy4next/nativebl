<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories\TigerWeb;


use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\TigerWeb\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;

/**
 * This interface defines blueprints of NativeBL Repository.
 *
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

class CustomerRepository  extends AbstractNativeRepository implements CustomerRepositoryInterface
{
   public function getModelFqcn(): string
   {
     return Customer::class;
   }

   public function getAllCustomer()
   {
      return $this->all();
   }

   public function findCustomerByMsisdn(string $msisdn):  iterable
   {
       return Customer::where('msisdn',$msisdn)->get();
   }

   public function getGridData(array $filters=[]): ?iterable
   {
      $myArray = [
         ['id'=>1, 'full_name'=>'Laravel CRUD','msisdn'=>'01962424629'],
         ['id'=>2, 'full_name'=>'Laravel Ajax CRUD','msisdn'=>'01962424629'],
         ['id'=>3, 'full_name'=>'Laravel CORS Middleware','msisdn'=>'01962424629'],
         ['id'=>4, 'full_name'=>'Laravel Autocomplete','msisdn'=>'01962424629'],
         ['id'=>5, 'full_name'=>'Laravel Image Upload','msisdn'=>'01962424629'],
         ['id'=>6, 'full_name'=>'Laravel Ajax Request','msisdn'=>'01962424629'],
         ['id'=>7, 'full_name'=>'Laravel Multiple Image Upload','msisdn'=>'01962424629'],
         ['id'=>8, 'full_name'=>'Laravel Ckeditor','msisdn'=>'01962424629'],
         ['id'=>9, 'full_name'=>'Laravel Rest API','msisdn'=>'01962424629'],
         ['id'=>10, 'full_name'=>'Laravel Pagination','msisdn'=>'01962424629'],
      ];
      return $myArray;
   }
   public function getGridQuery(): ?Builder
   {
      return Customer::select();
   }

}
