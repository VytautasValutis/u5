@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card fs-4">
                <div class="card-header fw-bold">General indicators of the bank</div>
                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            Number of clients : <span># {{$clients->count()}}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            Number of account : <span># {{$accounts->count()}}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            The total amount held : <span>&#x20AC; {{number_format($accounts->sum('value'), 2, '.', ' ')}}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            Maximum amount : <span>&#x20AC; {{number_format($accounts->max('value'), 2, '.', ' ')}}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            Average bill amount : <span>&#x20AC; {{number_format($accounts->avg('value'), 2, '.', ' ')}}</span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            Accounts with 0 balances : <span># {{$accounts->where('value', 0)->count(), 2, '.', ' '}}</span>
                        </div>
                    </div>
                </div> 
                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            Accounts with minus balance : <span># {{$accounts->where('value', '<', 0)->count(), 2, '.', ' '}}</span>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
