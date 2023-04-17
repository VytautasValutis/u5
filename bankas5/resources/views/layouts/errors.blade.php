@if($errors->any())
<div class="alert alert-danger">
    <div class="row justify-content-center">
        <div class="col-10">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif
