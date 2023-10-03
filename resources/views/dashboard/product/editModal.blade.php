<!-- Start Modal -->
<div class="modal fade custom-modal" id="editModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">{{ trans('main.Edit') }} {{ trans('main.Product') }}</h4>
                <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <!-- <form action="{{ route('customer.update', 'test') }}" method="post" enctype="multipart/form-data">
                    {{ method_field('patch') }}
                    @csrf -->

                    <ul id="update_error_list"></ul>

                    <!-- name -->
                    <div class="form-group">
                        <label>{{ trans('main.Name') }}</label>
                        <input id="update_name" type="text" class="form-control name" name="name" placeholder="{{ trans('main.Name') }}" required>
                    </div>
                    <!-- details -->
                    <div class="form-group">
                        <label>{{ trans('main.Details') }}</label>
                        <textarea id="update_details" type="text" class="form-control details" name="details" placeholder="{{ trans('main.Details') }}" required></textarea>
                    </div>
                    <!-- price -->
                    <div class="form-group">
                        <label>{{ trans('main.Price') }}</label>
                        <input id="update_price" type="text" class="form-control price" name="price" placeholder="{{ trans('main.Price') }}" required>
                    </div>
                    
                    <!-- id -->
                    <div class="form-group">
                        <input id="update_id" type="hidden" name="id" class="form-control id">
                    </div>
                    <div class="mt-4">
                        <button id="updateBtn" class="updateBtn btn btn-primary btn-block">{{ trans('main.Confirm') }}</button>
                    </div>
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
