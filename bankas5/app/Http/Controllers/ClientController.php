<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as VV;

class ClientController extends Controller
{

    private function putRandCode() : string
    {
        $met = rand(1901,2007);
        $men = rand(1, 12);
        $nr = rand(1, 999);
        if(in_array($men,[1,3,5,7,8,10,12])) {
            $dien = rand(1, 31);
        };
        if(in_array($men,[4,6,9,11])) {
            $dien = rand(1, 30);
        };
        if($men == 2) {
            if($met % 4 === 0) {
                $dien = rand(1, 29);
            } else {
                $dien = rand(1, 28);
            }
        }
        if($met > 1999) {
            $ak[] = rand(5, 6);
        } else {
            $ak[] = rand(3, 4);
        }
        $ak[] = floor(($met % 100) / 10);
        $ak[] = $met % 10;
        $ak[] = floor($men / 10);
        $ak[] = $men % 10;
        $ak[] = floor($dien / 10);
        $ak[] = $dien % 10;
        $ak[] = floor($nr / 100);
        $ak[] = floor(($nr % 100) / 10);
        $ak[] = $nr % 10;
        $ks = $ak[0] + $ak[1] * 2 + $ak[2] * 3 +
            $ak[3] * 4 + $ak[4] * 5 + $ak[5] * 6 +
            $ak[6] * 7 + $ak[7] * 8 + $ak[8] * 9 + 
            $ak[9];
        $kss = $ks % 11;    
        if($kss === 10) {
            $ks = $ak[0] * 3 + $ak[1] * 4 + $ak[2] * 5 +
                $ak[3] * 6 + $ak[4] * 7 + $ak[5] * 8 +
                $ak[6] * 9 + $ak[7] + $ak[8] + $ak[9];
            $kss = $ks % 11;
            if($kss === 10) $kss = 0;
        }        
        $ak[] = $kss;
    
        return implode('', $ak);
    }

    private function testPersCode(string $code) : bool
    {
        function ak_tst($ak1, $met, $men, $dien, $nr) {
            $ak[] = $ak1;
            $ak[] = floor(($met % 100) / 10);
            $ak[] = $met % 10;
            $ak[] = floor($men / 10);
            $ak[] = $men % 10;
            $ak[] = floor($dien / 10);
            $ak[] = $dien % 10;
            $ak[] = floor($nr / 100);
            $ak[] = floor(($nr % 100) / 10);
            $ak[] = $nr % 10;
            $ks = $ak[0] + $ak[1] * 2 + $ak[2] * 3 +
                $ak[3] * 4 + $ak[4] * 5 + $ak[5] * 6 +
                $ak[6] * 7 + $ak[7] * 8 + $ak[8] * 9 + 
                $ak[9];
            $kss = $ks % 11;    
            if($kss === 10) {
                $ks = $ak[0] * 3 + $ak[1] * 4 + $ak[2] * 5 +
                    $ak[3] * 6 + $ak[4] * 7 + $ak[5] * 8 +
                    $ak[6] * 9 + $ak[7] + $ak[8] + $ak[9];
                $kss = $ks % 11;
                if($kss === 10) $kss = 0;
            }        
            $ak[] = $kss;
        
            return implode('', $ak);
        }

        $ak1 = (int) substr($code,0,1);
        if($ak1 < 3 || $ak1 > 6) return false;
        if($ak1 == 3 || $ak1 == 4) $met = (int)'19'.substr($code,1,2); 
        if($ak1 == 5 || $ak1 == 6) $met = (int)'20'.substr($code,1,2); 
        if($met > 2007) return false;
        $men = (int) substr($code,3,2);
        if($men > 12 || $men < 1) return false;
        $dien = (int) substr($code,5,2);
        if($dien > 31 || $dien < 1) return false;
        if($dien == 31 && in_array($men,[4,6,9,11])) return false;
        if($dien == 30 && $men == 2) return false;
        if($dien == 29 && $men == 2 && $met % 4 != 0) return false;
        $sk = (int) substr($code,7,3);
        if(ak_tst($ak1, (int) $met, $men, $dien, $sk) != $code) return false;
        return true;
    }

    public function index(Request $request)
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
        $page = $request->page ?? 1;

        $request->session()->put('last-client-view', [
            'page' => $page,
        ]);

        return view('clients.index', [
            'clients' => $clients,
            'clientAccounts' => $clientAccounts,
            'page' => $page,
        ]);
    }

    public function create()
    {
        session(['pid' => self::putRandCode()]);

        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'surname' => 'required|min:3',
            'pid' => 'required|unique:App\Models\Client,pid',
        ]);

        $validator->after(function(VV $validator) {
            $temp = $validator->safe()->pid;
            if(!self::testPersCode($temp)) {
                $validator->errors()->add('Error', 'Wrong client PID');
            }
        });

        if($validator->fails()) {
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        $client = new Client;
        $client->name = $request->name;
        $client->surname = $request->surname;
        $client->pid = $request->pid;
        $client->save();
        return redirect()
            ->route('clients-index')
            ->with('ok', 'Client :' . $request->name . ' ' . $request->surname . ' was created');

    }

    public function show(Client $client)
    {
        //
    }

    public function edit(Client $client)
    {
        $clientAccounts = Account::where('client_id', $client->id);
        $clientAccounts = $clientAccounts->get();

        return view('clients.edit', [
            'client' => $client,
            'accounts' => $clientAccounts,
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'surname' => 'required|min:3',
        ]);

        if($validator->fails()){
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        $client->name = $request->name;
        $client->surname = $request->surname;
        $client->save();
        return redirect()
            ->route('clients-index', $request->session()->get('last-client-view', []))
            ->With('ok', 'The client ' . $request->name . ' ' . $request->surname . ' was updated')
            ->with('light-up', $client->id)
            ;

    }

    public function destroy(Request $request, Client $client)
    {
        if($request->clientSum > 0) {
            return redirect()
            ->back()
            ->withErrors('Client: ' . $client->name . ' ' . $client->surname .' has values. Cannot be removed');
        }

        $client->delete();
        return redirect()
            ->route('clients-index')
            ->with('info', 'Client ' . $client->name . ' ' . $client->surname . ' removed');
    }
}
