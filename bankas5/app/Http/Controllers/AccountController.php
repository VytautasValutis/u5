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
        //
    }

    public function store(Request $request)
    {
        Account::create([
            'iban' => 'LT3306660' . sprintf('%1$011d', time()),
            'value' => 0.00,
            'client_id' => $request->clientId,
        ]);

        return redirect()
            ->back()
            ->With('ok', 'The new account created.')
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
