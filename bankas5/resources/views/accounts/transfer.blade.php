@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-6 ">
            <div class="card mt-3">
                <div class="card-header">
                    <h2>Transfer funds</h2>
                </div>
                <div class="card-body">
                    <form action="{{route('accounts-update')}}" method="post">
                        <div class="mb-3">
                            <label class="form-label fs-4">Transfer from accounts</label>
                            <select class="form-select" name="from_acc">
                                @foreach($lists as $list)
                                <option value="{{$list->id}}" @if($list->id === (int) $fromAcc) selected @endif>
                                    {{$list->surname}} {{$list->name}} {{$list->iban}} ==> &#x20AC; {{$list->value}}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text">Please select account</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-4">Transfer to accounts</label>
                            <select class="form-select" name="to_acc">
                                @foreach($lists as $list)
                                <option value="{{$list->id}}" @if($list->id === (int) $toAcc) selected @endif>
                                    {{$list->surname}} {{$list->name}} {{$list->iban}} ==> &#x20AC; {{$list->value}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-text">Please select account</div>
                        <div class="mb-3">
                            <label class="form-label fs-4 w-auto">The amount of transfer feed</label>
                            <input type="text" class="form-control w-50 d-inline-block float-end" name="value" value="0">
                        </div>
                        <input type="hidden" name="oper" value="Transfer">
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
