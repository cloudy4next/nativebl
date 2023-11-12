<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HelperTrait;

class DashboardController extends Controller
{
    use HelperTrait;
    public function main(Request $request)
    {
        $applciationID = $this->SessionCheck('applicationID');

        switch ($applciationID) {
            case $applciationID == config('nativebl.base.toffee_analytics_application_id'):
                return redirect('/all-campaign');
            case $applciationID == config('nativebl.base.dnd_application_id'):
                return view('home.dnd.dnd-dashboard');
            default:
                return view('main');
        }
    }
}
