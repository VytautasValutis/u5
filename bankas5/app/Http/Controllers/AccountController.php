<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as VV;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        session('filterMenuType', 0);

    }

    
    public function index(Request $request)
    {
        $request->session()->put('filterMenuType', 2);
        $clients = Client::all();
        $filterA = $request->filterA ?? 0;
        $accounts = match($filterA) {
            '1' => Account::where('value', '>', '0'),
            '2' => Account::where('value', '0'),
            '3' => Account::where('value', '<', '0'),
            default => Account::where('id', '>', '0')
        };
        $accounts = $accounts->orderBy('iban');
        $accounts = $accounts->paginate(11)->withQueryString();
        return view('accounts.index', [
            'clients' => $clients,
            'accounts' => $accounts,
        ]);

    }

    public function create()
    {
        session()->put('filterMenuType', 0);

        $clients = Client::all()->sortBy('surname');
        return view('accounts.create', [
            'clients' => $clients,
            'iban' => 'LT3306660' . sprintf('%1$011d', time()),
        ]);
    }

    public function store(Request $request)
    {
        session()->put('filterMenuType', 0);

        $iban = $request->iban ?? 'LT3306660' . sprintf('%1$011d', time());
        Account::create([
            'iban' => $iban,
            'value' => 0.00,
            'client_id' => $request->client_id,
        ]);
        $client = Client::where('id', $request->client_id)->get()->first();
        $client->accCount++;
        $client->save();
        return redirect()
            ->route('clients-index')
            ->With('ok', 'The new account: ' . $iban . ' was created. Client: ' . $client->surname . ' ' . $client->name)
            ;

    }

    public function show(Account $account)
    {
            //
    }

    public function transfer(Request $request)
    {
        session()->put('filterMenuType', 0);

        $accounts = Account::all();
        $lists = Client::join('Accounts','clients.id','=','accounts.client_id');
        $lists = $lists->orderBy('surname');
        $lists = $lists->get();

            return view('accounts.transfer', [
                'lists' => $lists,
            ]);
        }

    public function edit($oper, Client $client, $accountId)
    {
        session()->put('filterMenuType', 0);

        if($oper == 'Taxes') {
            $acc = Account::where('id', '>', 0)->get();
            $acc = $acc->unique('client_id')->all();
            $count = count($acc);
            foreach($acc as $ac){
                $ac->value -= 5;
                $ac->save();
            }
            return redirect()
            ->back()
            ->with('ok', 'Fees have been deducted ' . $count . ' accounts');
            ;
        }
        $accounts = Account::where('client_id', $client->id)->get();
        if($oper == 'Add') return view('accounts.addFunds', [
            'client' => $client,
            'accounts' => $accounts,
            'accountId' => $accountId,
        ]);
        if($oper == 'Rem') return view('accounts.remFunds', [
            'client' => $client,
            'accounts' => $accounts,
            'accountId' => $accountId,
        ]);
        return redirect()->back();
    }

    public function update(Request $request)
    {
        session()->put('filterMenuType', 0);

        if($request->oper == 'modal') {
            $request->request->set('oper', session('oper'));
            $request->request->set('value', session('value'));
            $request->request->set('account_id', session('account_id'));
            $request->request->set('from_acc', session('from_acc'));
            $request->request->set('to_acc', session('to_acc'));
        }

        $validator = Validator::make($request->all(), [
            'value' => 'required|decimal:0,2',
        ]);
        $validator->after(function(VV $validator) {
            $temp = $validator->safe()->value;
            if($temp < 0) {
                $validator->errors()->add('Error', 'Value less than zero');
            }
        });
        if($validator->fails()) {
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator);
        }
        if($request->oper == "Transfer") {
            if($request->from_acc == $request->to_acc) {
                return redirect()
                ->back()
                ->withErrors('Illegal transfer: from and to accounts match');
            }
            $accountFr  = Account::where('id', $request->from_acc)->get()->first();
            $accountTo  = Account::where('id', $request->to_acc)->get()->first();

            if($request->value > $accountFr->value) {
                $request->flash();
                return redirect()
                    ->back()
                    ->withErrors('Insufficient funds to perform the operation');
            }
            if(!$request->confirm && (float) $request->value > 1000) {
                session(['from_acc' => $request->from_acc]);
                session(['to_acc' => $request->to_acc]);
                session(['value' => $request->value]);
                session(['oper' => $request->oper]);
                return redirect()
                ->back()
                ->with('oper-modal', [
                    'The operation value exeds 1000. Do Your really perform operation?',
                ]);
            };
            $accountFr->value -= (float) $request->value;
            $accountFr->save();
            $accountTo->value += (float) $request->value;
            $accountTo->save();
            return redirect()
            ->back()
            ->with('ok', 'Transfer successful from: ' . $accountFr->iban . '  to  ' . $accountTo->iban . ' ' . $request->value . '  values');
        }

        $account  = Account::where('id', $request->account_id)->get()->first();
        if($request->oper == "Add") {
            if(!$request->confirm && (float) $request->value > 1000) {
                session(['account_id' => $request->account_id]);
                session(['value' => $request->value]);
                session(['oper' => $request->oper]);
                return redirect()
                ->back()
                ->with('oper-modal', [
                    'The operation value exeds 1000. Do Your really perform operation?',
                ]);
            };
            $account->value += (float) $request->value;
            $msg = ' added ' . $request->value;
        } else {
            if(!$request->confirm && (float) $request->value > 1000) {
                session(['account_id' => $request->account_id]);
                session(['value' => $request->value]);
                session(['oper' => $request->oper]);
                return redirect()
                ->back()
                ->with('oper-modal', [
                    'The operation value exeds 1000. Do Your really perform operation?',
                ]);
            };
            if($request->value > $account->value) {
                    $request->flash();
                    return redirect()
                        ->back()
                        ->withErrors('Insufficient funds to perform the operation');
                }
                $account->value -= (float) $request->value;
                $msg = ' subtract ' . $request->value;
            }
        $account->save();
        return redirect()
            ->back()
            ->with('ok', 'Client acc.num.: ' . $account->iban . $msg . ' values');
    }

    public function destroy(Account $account)
    {
        session()->put('filterMenuType', 0);

        if($account->value != 0) {
            return redirect()->back()
                ->withErrors('Account num.:' . $account->iban . ' not zero. Cannot be removed')
                ;
        }
        $client = Client::where('id', $account->client_id)->get()->first();
        $client->accCount--;
        $client->save();
        $account->delete();
        return redirect()->back()
            ->with('info', 'Account ' . $account->iban . ' deleted');

    }
}
