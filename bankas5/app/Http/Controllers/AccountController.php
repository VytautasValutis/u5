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

    public function store(Request $request, Client $client)
    {
        Account::create([
            'iban' => 'LT3306660' . sprintf('%1$011d', time()),
            'value' => 0.00,
            'client_id' => $client->id,
        ]);

        return redirect()
            ->back()
            ->With('ok', 'The new account created. Client : ' . $client->name . ' ' . $client->surname)
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
        //
    }
}
