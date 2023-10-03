<!-- Start Modal -->
<div class="modal fade custom-modal" id="addModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">{{ trans('main.Add') }} {{ trans('main.Product') }}</h4>
                <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <!-- <form id="storeForm" action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf -->

                    <ul id="error_list"></ul>

                    <div class="form-group">
                        <label>{{ trans('main.Name') }}</label>
                        <input type="text" class="form-control name" name="name" value="{{ old('name') }}" placeholder="{{ trans('main.Name') }}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('main.Details') }}</label>
                        <textarea type="text" class="form-control details" name="details" value="{{ old('details') }}" placeholder="{{ trans('main.Details') }}" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('main.Price') }}</label>
                        <input type="text" class="form-control price" name="price" value="{{ old('price') }}" placeholder="{{ trans('main.Price') }}" required>
                    </div>
                    <div class="mt-4">
                        <button id="storeBtn" class="btn btn-primary btn-block">{{ trans('main.Confirm') }}</button>
                    </div>
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->