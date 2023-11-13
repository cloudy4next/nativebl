<?php


namespace App\Contracts\Services\ToffeAnalytics;

use Carbon\Carbon;
use Illuminate\Http\Request;

interface AgencyServiceInterface
{
    function getAllAgency();
    function store(Request $request);
    function delete($id);
    function getAgencyDataById($id);
    function deleteAgencyUser($id);
}
