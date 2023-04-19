@if(Session::has('oper-modal'))
<div class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm operation</h5>
            </div>
            <div class="modal-body">
                <p>{{ Session::get('oper-modal')[0] }}</p>
            </div>
            <div class="modal-footer">
                <a href={{url()->full()}} type="button" class="btn btn-secondary">No</a>
                <form action="{{route('accounts-update')}}" method="post">
                    <button type="submit" class="btn btn-danger">Yes</button>
                    <input type="hidden" value="1" name="confirm">
                    @csrf
                    @method('put')
                </form>
            </div>
        </div>
    </div>
</div>
@endif
