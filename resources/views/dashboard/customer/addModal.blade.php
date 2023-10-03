<!-- Start Modal -->
<div class="modal fade custom-modal" id="addModal">
    <div class="modal-dialog modal-dialog-centered  modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">{{ trans('main.Add') }} {{ trans('main.Customer') }}</h4>
                <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <!-- <form id="storeForm" action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf -->

                    <ul id="error_list"></ul>

                    <div class="row">
                        <!-- company -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ trans('main.Company') }}</label>
                                <input type="text" class="form-control company" name="company" value="{{ old('company') }}" placeholder="{{ trans('main.Company') }}" required>
                            </div>
                        </div>
                        <!-- contact_person -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ trans('main.Contact Person') }}</label>
                                <input name="contact_person" type="text" class="form-control contact_person" value="{{ old('contact_person') }}" placeholder="{{ trans('main.Contact Person') }}" required>
                            </div>
                        </div>
                        <!-- email -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ trans('main.Email') }}</label>
                                <input name="email" type="email" class="form-control email" value="{{ old('email') }}" placeholder="{{ trans('main.Email') }}" required>
                            </div>
                        </div>
                        <!-- phone -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ trans('main.Phone') }}</label>
                                <input name="phone" type="text" class="form-control phone" value="{{ old('phone') }}" placeholder="{{ trans('main.Phone') }}" required>
                            </div>
                        </div>
                        <!-- address -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ trans('main.Address') }}</label>
                                <input name="address" type="text" class="form-control address" value="{{ old('address') }}" placeholder="{{ trans('main.Address') }}" required>
                            </div>
                        </div>
                        <!-- city -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ trans('main.City') }}</label>
                                <input name="city" type="text" class="form-control city" value="{{ old('city') }}" placeholder="{{ trans('main.City') }}" required>
                            </div>
                        </div>
                        <!-- state -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ trans('main.State') }}</label>
                                <input name="state" type="text" class="form-control state" value="{{ old('state') }}" placeholder="{{ trans('main.State') }}" required>
                            </div>
                        </div>
                        <!-- postal_code -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ trans('main.Postal Code') }}</label>
                                <input name="postal_code" type="text" class="form-control postal_code" value="{{ old('postal_code') }}" placeholder="{{ trans('main.Postal Code') }}" required>
                            </div>
                        </div>
                        <!-- country -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ trans('main.Country') }}</label>
                                <input name="country" type="text" class="form-control country" value="{{ old('country') }}" placeholder="{{ trans('main.Country') }}" required>
                            </div>
                        </div>
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