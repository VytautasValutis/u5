<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::where('id', '>', '0');
        $clients = $clients->orderBy('surname');
        $clients = $clients->paginate(7)->withQueryString();

        return view('clients.index', [
            'clients' => $clients
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
