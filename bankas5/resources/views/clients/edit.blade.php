@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8 ">
            <div class="card mt-3">
                <div class="card-header">
                    <div class="container">
                        <div class="row">
                            <div class="col-4">
                                <h1>Edit client</h1>
                            </div>
                            <div class="col-4">
                                <div class="mt-3">PID {{$client->pid}}</div>
                            </div>
                            <div class="col-4 mt-1">
                                <form action="{{route('accounts-store')}}" method="post">
                                    <input type="hidden" value="{{$client->id}}" name="client_id">
                                    <button type="submit" class="btn btn-primary ms-4">Add new account</button>
                                    @csrf
                                    @method('put')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('clients-update', $client)}}" method="post">
                        <div class="mb-3">
                            <label class="form-label fs-4">Client name</label>
                            <input type="text" class="form-control" name="name" value="{{old('name', $client->name)}}">
                            <div class="form-text">Client name: min 3 characters</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-4">Client surname</label>
                            <input type="text" class="form-control" name="surname" value="{{old('surname', $client->surname)}}">
                            <div class="form-text">Client surname: min 3 characters</div>
                        </div>
                        <button type="submit" class="btn btn-primary ms-4">Submit</button>
                        @csrf
                        @method('put')
                    </form>
                    <div class="mb-3">
                        @forelse($accounts as $acc)
                        <div class="container">
                            <div class="row justify-content-left">
                                <div class="col-3 m-1">
                                    <form action="{{route('accounts-delete', $acc)}}" method="post">
                                        <button type="submit" class="btn btn-outline-danger">Remove account</button>
                                        @csrf
                                        @method('delete')
                                    </form>
                                </div>
                                <div class="col-4 m-2">
                                    {{$acc->iban}}
                                </div>
                                <div class="col-4 m-2">
                                    &#x20AC; {{number_format($acc->value, 2, '.', ' ')}}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div>No accounts</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
