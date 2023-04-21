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

    public function edit($oper, Client $client)
    {
        $accounts = Account::where('client_id', $client->id)->get();
        if($oper == 'Add') return view('accounts.addFunds', [
            'client' => $client,
            'accounts' => $accounts,
        ]);
        if($oper == 'Rem') return view('accounts.remFunds', [
            'client' => $client,
            'accounts' => $accounts,
        ]);
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $account  = Account::where('id', $request->account_id)->get()->first();
        if($request->oper == "Add") {
            if(!$request->confirm && (int) $request->value > 1000) {
                return redirect()
                ->back()
                ->with('oper-modal', [
                    'The operation value exeds 1000. Do Your really perform operation?',
                    $request->account_id,
                    $request->value,
                    "Add",
                ]);
            };
            $account->value += (int) $request->value;
            $msg = ' added ' . $request->value;
        } else {
            if(!$request->confirm && (int) $request->value > 1000) {
                return redirect()
                ->back()
                ->with('oper-modal', [
                    'The operation value exeds 1000. Do Your really perform operation?',
                    $request->account_id,
                    $request->value,
                    "Rem",
                ]);
            };
            if($request->value > $account->value) {
                    $request->flash();
                    return redirect()
                        ->back()
                        ->withErrors('Insufficient funds to perform the operation');
                }
                $account->value -= (int) $request->value;
                $msg = ' subtract ' . $request->value;
            }
        $account->save();
        return redirect()
            ->back()
            ->with('ok', 'Client acc.num.: ' . $account->iban . $msg . ' values');
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
