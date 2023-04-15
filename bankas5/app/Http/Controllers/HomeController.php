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
        $valuesTot = Account::all()->sum('value');
        $valuesTotal = number_format($valuesTot, 2, '.', ' ');
        $valuesMax = number_format(Account::all()->max('value'), 2, '.', ' ');
        $valuesAvg = number_format($valuesTot / $accountsAll, 2, '.', ' ');
        $accounts = Account::where('value', 0);
        $account0 = $accounts->count();
        $accounts = Account::where('value', '<', 0);
        $accountMinus = $accounts->count();

        return view('home', [
            'clientsAll' => $clientsAll,
            'accountsAll' => $accountsAll,
            'valuesTotal' => $valuesTotal,
            'valuesMax' => $valuesMax,
            'valuesAvg' => $valuesAvg,
            'account0' => $account0,
            'accountMinus' => $accountMinus,
            ]);
    }
}
