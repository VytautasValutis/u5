@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8 ">
            <div class="card mt-5">
                <div class="card-header">
                    <h1>New Account</h1>
                </div>
                <div class="card-body">
                    <form action="{{route('accounts-store')}}" method="post">
                        <div class="mb-3">
                            <label class="form-label">Client</label>
                            <select class="form-select" name="client_id">
                                <option value="0">Clients list</option>
                                @foreach($clients as $client)
                                <option value="{{$client->id}}">
                                    {{$client->surname}}  {{$client->name}}
                                </option>    
                                @endforeach
                            </select>
                            <div class="form-text">Please select client</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-4">Accounts IBAN </label>
                            <input readonly type="text" class="form-control" name="iban" value="{{old('iban', $iban)}}">
                            <div class="form-text">Clients PID</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        @csrf
                        @method('put')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
