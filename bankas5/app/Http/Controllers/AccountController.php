<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use Illuminate\Http\Request;


class AccountController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $clients = Client::all()->sortBy('surname');
        return view('accounts.create', [
            'clients' => $clients,
            'iban' => 'LT3306660' . sprintf('%1$011d', time()),
        ]);
    }

    public function store(Request $request)
    {
        $iban = $request->iban ?? 'LT3306660' . sprintf('%1$011d', time());
        Account::create([
            'iban' => $iban,
            'value' => 0.00,
            'client_id' => $request->client_id,
        ]);
        $client = Client::where('id', $request->client_id)->get();
        return redirect()
            ->route('clients-index')
            ->With('ok', 'The new account: ' . $iban . ' was created. Client: ' . $client->first()->surname . ' ' . $client->first()->name)
            ;

    }

    public function show(Account $account)
    {
        //
    }

    public function edit(Account $account)
    {
        //
    }

    public function update(Request $request, Account $account)
    {
        //
    }

    public function destroy(Account $account)
    {
        if($account->value != 0) {
            return redirect()->back()
                ->withErrors('Account num.:' . $account->iban . ' not zero. Cannot be removed')
                ;
        }
        $account->delete();
        return redirect()->back()
            ->with('info', 'Account ' . $account->iban . ' deleted');

    }
}
