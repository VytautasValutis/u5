@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-10">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">PID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Surname</th>
                    <th scope="col">Values</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $v)
                <tr>
                    <th>{{$v->pid}}</th>
                    <td>
                        {{$v->name}}
                        @if($v->clientAccountNum)
                            @foreach($clientAccounts as $acc)
                                @if($acc->client_id == $v->id)
                                    <div>{{$acc->iban}}
                                    <span class="order-list">&#x20AC; {{number_format($acc->value, 2, '.', ' ')}}</span>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{$v->surname}}</td>
                    <td><b> @if($v->clientAccountNum)
                            &#x20AC; {{$v->clientSum}}
                            @endif
                        </b></td>
                    <td>
                        <a href="" class="btn btn-outline-success">Edit client</a>
                    </td>
                    <td>
                        <a href="" class="btn btn-outline-success">Add funds</a>
                    </td>
                    <td>
                        <a href="" class="btn btn-outline-primary">Deduct funds</a>
                    </td>
                    <td>
                        <form action="" method="post">
                            <button type="submit" class="btn btn-outline-danger">Remove client</button>
                            @csrf
                            @method('delete')
                        </form>
                    </td>
                </tr>
                @empty
                <th>No clients</th>
                @endforelse
            </tbody>
        </table>
        <div class="m-2">
            {{ $clients->links() }}
        </div>

    </div>
</div>
@endsection
