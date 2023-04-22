@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-8">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Account #</th>
                    <th scope="col">Value</th>
                    <th scope="col">Name</th>
                    <th scope="col">Surname</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $acc)
                <tr>
                    <th>{{$acc->iban}}</th>
                    <td>&#x20AC; {{number_format($acc->value, 2, '.', ' ')}}</td>
                    <td>{{$clients->where('id', $acc->client_id)->first()->name}}</td>
                    <td>{{$clients->where('id', $acc->client_id)->first()->surname}}</td>
                    <td>
                        <a href="" class="btn btn-outline-success">Add funds</a>
                    </td>
                    <td>
                        <a href="" class="btn btn-outline-primary">Deduct funds</a>
                    </td>
                    <td>
                        <form action="" method="post">
                            <button type="submit" class="btn btn-outline-danger">Remove account</button>
                            @csrf
                            @method('delete')
                        </form>
                    </td> 
                </tr>
                @empty
                <th>No accounts</th>
                @endforelse
            </tbody>
        </table>
        <div class="m-2">
            {{ $accounts->links() }}
        </div>

    </div>
</div>
@endsection
