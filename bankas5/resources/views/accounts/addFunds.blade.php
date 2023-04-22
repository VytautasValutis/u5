@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8 ">
            <div class="card mt-3">
                <div class="card-header">
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                                <h1>Add funds</h1>
                            </div>
                            <div class="col-4">
                                <div class="mt-3">PID {{$client->pid}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('accounts-update')}}" method="post">
                        <div class="mb-3">
                            <label class="form-label fs-4">Client name</label>
                            <div class="form-text fs-5 text-primary">{{$client->name}}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-4">Client surname</label>
                            <div class="form-text fs-5 text-primary">{{$client->surname}}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-4">Client accounts</label>
                            <select class="form-select" name="account_id">
                                @foreach($accounts as $account)
                                <option value="{{$account->id}}" @if((int) $accountId > 0 && (int) $accountId == $account->id) selected @endif>
                                    {{$account->iban}} ==> &#x20AC; {{$account->value}}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text">Please select account</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-4 w-auto">The amount of added feed</label>
                            <input type="text" class="form-control w-50 d-inline-block float-end" name="value" value="0">
                        </div>
                        <input type="hidden" name="oper" value="Add">
                        <button type="submit" class="btn btn-primary ms-4">Submit</button>
                        @csrf
                        @method('put')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
