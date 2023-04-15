<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $clientsAll = Client::all()->count();
        $accountsAll = Account::all()->count();
        $valuesTotal = Account::all()->sum('value');
        $valuesMax = Account::all()->max('value');
        $valuesAvg = $valuesTotal / $accountsAll;
        // $account0 = Account::where('value', '==', 0)->count();
        // $accountMinus = Account::where('value', '<', 0);

        return view('home', [
            'clientsAll' => $clientsAll,
            'accountsAll' => $accountsAll,
            'valuesTotal' => $valuesTotal,
            'valuesMax' => $valuesMax,
            'valuesAvg' => $valuesAvg,
            // 'account0' => $account0,
            // 'accountMinus' => $accountMinus,
            ]);
    }
}
