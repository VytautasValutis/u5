<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Account;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::where('id', '>', '0');
        $clients = $clients->orderBy('surname');
        $clients = $clients->paginate(5)->withQueryString();
        foreach($clients as $c) {
            $clientAccounts = Account::where('client_id', $c->id);
            $clientAccounts->get();
            $clientSum = $clientAccounts->sum('value');
            $clientAccountNum = $clientAccounts->count();
            $c->clientSum = number_format($clientSum, 2, '.', ' ');
            $c->clientAccountNum = $clientAccountNum;
            // $c->all();
        }
        $clientAccounts = Account::all();

        return view('clients.index', [
            'clients' => $clients,
            'clientAccounts' => $clientAccounts,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(StoreClientRequest $request)
    {
        //
    }

    public function show(Client $client)
    {
        //
    }

    public function edit(Client $client)
    {
        //
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        //
    }

    public function destroy(Client $client)
    {
        //
    }
}
