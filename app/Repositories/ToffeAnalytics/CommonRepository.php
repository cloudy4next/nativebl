<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories\ToffeAnalytics;

use App\Models\ToffeAnalytics\ToffeeAgency;
use App\Models\ToffeAnalytics\ToffeeBrand;

use Auth;

class CommonRepository
{

  public function toffeeAgencyListByUser()
   {
    $toffeeAgencies = ToffeeAgency::where('created_by', Auth::user()->id)
						->pluck('name','id');
    return $toffeeAgencies;
   }

  public function toffeeAgencyList()
   {
    $toffeeAgencies = ToffeeAgency::pluck('name','id');
    return $toffeeAgencies;
   }

  public function toffeeBrandList()
   {
    $toffeeBrands = ToffeeBrand::pluck('name','id');
    return $toffeeBrands;
   }
}
