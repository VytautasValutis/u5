@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-10">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">PID</th>
                    <th scope="col"></th>
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
                        <div> Accounts </div>
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
                    <td>
                        <div class="@if(Session::has('light-up') && Session::get('light-up') == $v->id) active @endif">{{$v->name}}
                        </div>
                    </td>
                    <td>
                        <div class="@if(Session::has('light-up') && Session::get('light-up') == $v->id) active @endif">{{$v->surname}}
                        </div>
                    </td>
                    <td><b> @if($v->clientAccountNum)
                            &#x20AC; {{$v->clientSum}}
                            @endif
                        </b></td>
                    <td>
                        <a href="{{route('clients-edit', $v)}}" class="btn btn-outline-success">Edit client</a>
                    </td>
                    <td>
                        <a href="{{route('accounts-edit', ['Add', $v, '0'])}}" class="btn btn-outline-success">Add funds</a>
                    </td>
                    <td>
                        <a href="{{route('accounts-edit', ['Rem', $v, '0'])}}" class="btn btn-outline-primary">Deduct funds</a>
                    </td>
                    <td>
                        <form action="{{route('clients-delete', $v)}}" method="post">
                            <input type="hidden" value="{{$v->clientSum}}" name="clientSum">
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
