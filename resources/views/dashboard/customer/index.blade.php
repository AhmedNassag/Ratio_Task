<?php $page="customers";?>

@extends('layouts.master')

@section('css')
    <!-- Print -->
    <style>
        @media print {
            .notPrint {
                display: none;
            }
        }
    </style>
    @section('title')
        {{ trans('main.Customers') }}
    @stop
@endsection



@section('content')
            <!-- validationNotify -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- success Notify -->
            @if (session()->has('success'))
                <script id="successNotify">
                    window.onload = function() {
                        notif({
                            msg: "تمت العملية بنجاح",
                            type: "success"
                        })
                    }
                </script>
            @endif

            <!-- error Notify -->
            @if (session()->has('error'))
                <script id="errorNotify">
                    window.onload = function() {
                        notif({
                            msg: "لقد حدث خطأ.. برجاء المحاولة مرة أخرى!",
                            type: "error"
                        })
                    }
                </script>
            @endif

            <!-- canNotDeleted Notify -->
            @if (session()->has('canNotDeleted'))
                <script id="canNotDeleted">
                    window.onload = function() {
                        notif({
                            msg: "لا يمكن الحذف لوجود بيانات أخرى مرتبطة بها..!",
                            type: "error"
                        })
                    }
                </script>
            @endif
            

            <!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">{{ trans('main.Customers') }}</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ trans('main.Dashboard') }}</a></li>
                                    <li class="breadcrumb-item active">{{ trans('main.Customers') }}</li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn add-button me-2" data-bs-toggle="modal" data-bs-target="#addModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn filter-btn me-2" id="filter_search">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <button type="button" class="btn" id="btn_delete_selected" title="{{ trans('main.Delete Selected') }}" style="display:none; width: 42px; height: 42px; justify-content: center; align-items: center; color: #fff; background: red; border: 1px solid red; border-radius: 5px;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    <!-- Search Filter -->
                    <div class="card filter-card" id="filter_inputs" @if($company || $from_date || $to_date) style="display:block" @endif>
                        <div class="card-body pb-0">
                            <form action="{{ route('customer.index') }}" method="get">
                                <div class="row filter-row">
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>{{ trans('main.Company') }}</label>
                                            <input class="form-control" type="text" name="company" value="{{ $company }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>{{ trans('main.From Date') }}</label>
                                            <input class="form-control" type="date" name="from_date" value="{{ $from_date }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>{{ trans('main.To Date') }}</label>
                                            <input class="form-control" type="date" name="to_date" value="{{ $to_date }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-block" type="submit">{{ trans('main.Search') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /Search Filter -->

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <ul id="edit_error_list"></ul>
                                    <ul id="delete_error_list"></ul>
                                    <div class="table-responsive">
                                        <div class="table-responsive">
                                            <table id="example1" class="table table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">{{ trans('main.Name') }}</th>
                                                        <th class="text-center">{{ trans('main.Contact Person') }}</th>
                                                        <th class="text-center">{{ trans('main.Email') }}</th>
                                                        <th class="text-center">{{ trans('main.Phone') }}</th>
                                                        <th class="text-center">{{ trans('main.Address') }}</th>
                                                        <th class="text-center">{{ trans('main.City') }}</th>
                                                        <th class="text-center">{{ trans('main.State') }}</th>
                                                        <th class="text-center">{{ trans('main.Postal Code') }}</th>
                                                        <th class="text-center">{{ trans('main.Country') }}</th>
                                                        <th class="text-center">{{ trans('main.Actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($data->count() > 0)
                                                        <?php $i = 0; ?>
                                                        @foreach ($data as $item)
                                                            <?php $i++; ?>
                                                            <tr>
                                                                <td class="text-center notPrint">
                                                                    <input id="delete_selected_input" type="checkbox" value="{{ $item->id }}" class="box1 mr-1" oninput="showBtnDeleteSelected()">
                                                                    {{ $i }}
                                                                </td>
                                                                <td class="text-center">{{ $item->company}}</td>
                                                                <td class="text-center">{{ $item->contact_person}}</td>
                                                                <td class="text-center">{{ $item->email}}</td>
                                                                <td class="text-center">{{ $item->phone}}</td>
                                                                <td class="text-center">{{ $item->address}}</td>
                                                                <td class="text-center">{{ $item->city}}</td>
                                                                <td class="text-center">{{ $item->state}}</td>
                                                                <td class="text-center">{{ $item->postal_code}}</td>
                                                                <td class="text-center">{{ $item->country}}</td>
                                                                <td class="text-center">
                                                                    <button type="button" class="editBtn btn btn-sm btn-secondary mr-1" value="{{ $item->id }}"><i class="far fa-edit"></i></button>
                                                                    <button type="button" class="deleteBtn btn btn-sm btn-danger" value="{{ $item->id }}"><i class="far fa-trash-alt"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <th class="text-center" colspan="11">
                                                                <div class="col mb-3 d-flex">
                                                                    <div class="card flex-fill">
                                                                        <div class="card-body p-3 text-center">
                                                                            <p class="card-text f-12">{{ trans('main.No Data Founded') }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                            {{ $data->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('dashboard.customer.addModal')
                            @include('dashboard.customer.editModal')
                            @include('dashboard.customer.deleteModal')
                            @include('dashboard.customer.deleteSelectedModal')		
                        </div>	
                    </div>
                </div>			
            </div>
            <!-- /Page Wrapper -->
		</div>
    </div>
	<!-- /Main Wrapper -->
	
@endsection



@section('js')
    <script>
        $(document).ready(function () {

            //store
            $(document).on('click','#storeBtn',function(e){
                e.preventDefault();
                $(this).text('جارى الحفظ');
                var storeData = {
                    'company'        : $('.company').val(),
                    'contact_person' : $('.contact_person').val(),
                    'email'          : $('.email').val(),
                    'phone'          : $('.phone').val(),
                    'address'        : $('.address').val(),
                    'city'           : $('.city').val(),
                    'state'          : $('.state').val(),
                    'postal_code'    : $('.postal_code').val(),
                    'country'        : $('.country').val(),
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "post",
                    url: "{{ route('customer.store') }}",
                    enctype: "multipart/form-data",
                    data: storeData,
                    dataType: "json",
                    success:function(response) {
                        if(response.status == false) {
                            $('#error_list').html("");
                            $('#error_list').addClass('alert alert-danger');
                            $.each(response.messages, function(key, val) {
                                $('#error_list').append('<li>'+ val +'</li>');
                            });
                            $('#storeBtn').text('المحاولة مجدداً');
                        } else {
                            $('#error_list').html("");
                            $('#addModal').modal('hide');
                            $('#addModal').find('input').val("");
                            $('#storeBtn').text('حفظ');
                            location.reload();
                        }
                    },
                    error:function(reject){},
                });
            });



            //edit
            $(document).on('click','.editBtn',function(e){
                e.preventDefault();
                var id = $(this).val();
                $('#edit_error_list').html("");
                $('#editModal').modal('show');
                $.ajax({
                    type: "get",
                    url: "/admin/customer/edit/"+id,
                    success:function(response) {
                        if(response.status == false) {
                            $('#edit_error_list').html("");
                            $('#edit_error_list').addClass('alert alert-danger');
                            $("#edit_error_list").append("<li>"+ response.messages +"</li>");
                        } else {
                            $('#update_id').val(response.data.id);
                            $('#update_company').val(response.data.company);
                            $('#update_contact_person').val(response.data.contact_person);
                            $('#update_email').val(response.data.email);
                            $('#update_phone').val(response.data.phone);
                            $('#update_address').val(response.data.address);
                            $('#update_city').val(response.data.city);
                            $('#update_state').val(response.data.state);
                            $('#update_postal_code').val(response.data.postal_code);
                            $('#update_country').val(response.data.country);
                        }
                    },
                    error:function(reject){},
                });
            });



            //update
            $(document).on('click','.updateBtn',function(e){
                e.preventDefault();
                $(this).text('جارى التعديل');
                var updateData = {
                    'id'             : $('#update_id').val(),
                    'company'        : $('#update_company').val(),
                    'contact_person' : $('#update_contact_person').val(),
                    'email'          : $('#update_email').val(),
                    'phone'          : $('#update_phone').val(),
                    'address'        : $('#update_address').val(),
                    'city'           : $('#update_city').val(),
                    'state'          : $('#update_state').val(),
                    'postal_code'    : $('#update_postal_code').val(),
                    'country'        : $('#update_country').val(),
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "post",
                    url: "{{ route('customer.update') }}",
                    data: updateData,
                    dataType: "json",
                    success:function(response) {
                        if(response.status == false) {
                            $('#update_error_list').html("");
                            $('#update_error_list').addClass('alert alert-danger');
                            $.each(response.messages, function(key, val) {
                                $('#update_error_list').append('<li>'+ val +'</li>');
                            });
                        } else {
                            $('#update_error_list').html("");
                            $('#editModal').modal('hide');
                            $('#addModal').find('input').val("");
                            $(this).text('جارى التعديل');
                            location.reload();
                        }
                    },
                    error:function(reject){},
                });
            });



            //delete
            $(document).on('click','.deleteBtn',function(e){
                e.preventDefault();
                var id = $(this).val();
                $('#delete_id').val(id);
                $('#delete_error_list').html("");
                $('#deleteModal').modal('show');
            });
            $(document).on('click','.destroyBtn',function(e){
                e.preventDefault();
                $(this).text('جارى الحذف');
                var id = $('#delete_id').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "delete",
                    url: "/admin/customer/destroy/"+id,
                    success:function(response) {
                        if(response.status == false) {
                            $('#delete_error_list').html("");
                            $('#delete_error_list').addClass('alert alert-danger');
                            $("#delete_error_list").append("<li>"+ response.messages +"</li>");
                            $('.destroyBtn').text('المحاولة مجدداً');
                        } else {
                            $('#delete_error_list').html("");
                            $('#deleteModal').modal('hide');
                            $('.destroyBtn').text('حذف');
                            location.reload();
                        }
                    },
                    error:function(reject){},
                });
            });
        });
    </script>
@endsection
