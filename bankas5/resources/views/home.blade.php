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
                            Number of clients : <span># {{$clientsAll}}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            Number of account : <span># {{$accountsAll}}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            The total amount held : <span>&#x20AC; {{$valuesTotal}}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            Maximum amount : <span>&#x20AC; {{$valuesMax}}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            Average bill amount : <span>&#x20AC; {{$valuesAvg}}</span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            Accounts with 0 balances : <span># {{$account0}}</span>
                        </div>
                    </div>
                </div> 
                <div class="card-body">
                    <div class="text-line">
                        <div class="text-info">
                            Accounts with minus balance : <span># {{$accountMinus}}</span>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
