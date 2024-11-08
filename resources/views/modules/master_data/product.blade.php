@extends('layouts.master')

@section('styles')


@endsection

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Product</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                @can('product create')
                    <div class="d-flex">
                        <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light" id="create"><i class="ri-add-line fw-semibold align-middle me-1"></i> Create New</a>                  
                    </div>
                @endcan
                {{-- <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Customers</li>
                </ol> --}}
            </nav>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Start::row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                {{-- <div class="card-header justify-content-between">
                    <div class="card-title">Customers</div>               
                </div> --}}
                <div class="card-body">
                    <div class="table-responsive mb-4">
                        <table class="table text-nowrap table-bordered" id="product">
                            <thead>
                                <tr>
                                    <th width="25">#</th>
                                    <th scope="col">PART NUMBER</th>
                                    <th scope="col">PART NAME</th>
                                    <th scope="col">ASSY NUMBER</th>
                                    <th scope="col">MODEL</th>
                                    <th scope="col">ITEM</th>
                                    <th scope="col">CUSTOMER</th>
                                    <th scope="col">CREATED BY</th>
                                    <th scope="col">CREATED AT</th>
                                    <th width="150">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End::row -->

</div>

<!-- Form Modal -->
<div class="modal fade" id="formModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modelHeading"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <form id="productForm" name="productForm" class="form-horizontal">
                    @csrf
                    <div class="alert alert-danger error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="row gy-2">
                    <div class="col-xl-12">
                        <input type="hidden" name="product_id" id="product_id">
                        <label for="part_number" class="form-label">Part Number</label>
                        <input type="text" id="part_number" name="part_number" class="form-control" placeholder="Part Number">
                    </div> 
                    <div class="col-xl-12">
                        <label for="part_name" class="form-label">Part Name</label>
                        <input type="text" id="part_name" name="part_name" class="form-control" placeholder="Part Name">
                    </div> 
                    <div class="col-xl-12">
                        <label for="assy_number" class="form-label">Assy Number</label>
                        <input type="text" id="assy_number" name="assy_number" class="form-control" placeholder="Assy Number">
                    </div> 
                    <div class="col-xl-12">
                        <label for="model" class="form-label">Model</label>
                        <input type="text" id="model" name="model" class="form-control" placeholder="Model">
                    </div>
                    <div class="col-xl-12">
                        <label for="item" class="form-label">Item</label>
                        <select class="form-select" id="item" name="item"
                            aria-label="Pilih Item">
                            <option value="">-- Pilih --</option>
                            @foreach ($items as $item)
                                @if(old('item') === $item->itemName)
                                    <option value="{{ $item->itemName }}" selected>{{ $item->itemName }}</option>
                                @else
                                    <option value="{{ $item->itemName }}"> {{ $item->itemName }}</option>
                                @endif
                            @endforeach  
                        </select>                      
                    </div>
                    <div class="col-xl-12">
                        <label for="customer" class="form-label">Customer</label>
                        <select class="form-select" id="customer" name="customer"
                            aria-label="Pilih Customer">
                            <option value="">-- Pilih --</option>
                            @foreach ($customers as $customer)
                                @if(old('customer') === $customer->customerName)
                                    <option value="{{ $customer->customerName }}" selected>{{ $customer->customerName }}</option>
                                @else
                                    <option value="{{ $customer->customerName }}"> {{ $customer->customerName }}</option>
                                @endif
                            @endforeach  
                        </select>                        
                    </div>                                                                                                                                                                                                                       
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBtn" value="create">Save</button>
            </div>
        </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<!-- CRUD Process -->
<script>
    $(document).ready(function () {
        $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#product').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('product.index') }}",
        columns: [
                {data: 'DT_RowIndex',name: 'DT_Row_Index',orderable: false,searchable: false},
                {data: 'partNumber'},
                {data: 'partName'},
                {data: 'assyNumber'},
                {data: 'model'},
                {data: 'item'},
                {data: 'customer'},
                {data: 'createdBy'},
                {data: 'created_at'},
                {data: 'action',orderable: false,searchable: false},
            ],
        dom: "<'row'<'col-md-2'l><'col-md-3'B><'col-md-7'f>>" +
            "<'row'<'col-md-12'tr>>" +
            "<'row'<'col-md-5'i><'col-md-7'p>>"
        ,
        buttons: [
            'excel', 'pdf', 'print'
        ]
    });

    $('#create').click(function () {
        $('#saveBtn').val("create");
        $('#product_id').val('');
        $('#part_number').val('');
        $('#part_name').val('');
        $('#assy_number').val('');
        $('#model').val('');
        $('#item').val('');
        $('#customer').val('');
        $('.error-msg').hide();
        $('#productForm').trigger("reset");
        $('.modal-title').html("Create");
        $('#formModel').modal('show');
    });

    $('body').on('click', '.edit', function () {
        var id = $(this).data('id');
            $.get("{{ route('product.index') }}" +'/' + id +'/edit', function (data) {
            $('.error-msg').hide();
            $('#processForm').trigger("reset");
            $('#saveBtn').val("edit");
            $('.modal-title').html("Edit");
            $('#formModel').modal('show');
            $('#product_id').val(data.id);
            $('#part_number').val(data.partNumber);
            $('#part_name').val(data.partName);
            $('#assy_number').val(data.assyNumber);
            $('#model').val(data.model);
            $('#item').val(data.item);
            $('#customer').val(data.customer);
        });
    });


    $('#saveBtn').click(function (e) {
        e.preventDefault();
        
        // Change button text to indicate loading state
        $(this).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading..');

        // Send AJAX request
        $.ajax({
            data: $('#productForm').serialize(),
            url: "{{ route('product.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    // Success: Display success message, hide error messages, close modal
                    Swal.fire("Done!", data.message, "success");
                    $('.error-msg').hide();
                    $('#formModel').modal('hide');
                } else {
                    // Error: Display error messages
                    printErrorMsg(data.error);
                }
                // Reset button text
                $('#saveBtn').html('Save');
                // Optionally, refresh the table
                table.draw();
            },
            error: function (data) {
                // Error: Log the error and reset button text
                console.log('Error:', data);
                $('#saveBtn').html('Save');
            }
        });
    });

    function printErrorMsg(msg) {
        $('.error-msg').find('ul').html('');
        $('.error-msg').css('display', 'block');
        $.each(msg, function (key, value) {
            $(".error-msg").find("ul").append('<li>' + value + '</li>');
        });
    }



    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
                title: 'Delete',
                text: 'Apakah anda yakin ingin menghapus data ini?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                icon: 'question',
            }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                type: "POST",
                url: "/product/delete",
                data: {id:id},
                    success: function (data) {
                        Swal.fire('Data berhasil di hapus', '', 'success');
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        Swal.fire('Data gagal di hapus', '', 'error');
                        table.draw();
                    }
                });
            }

            function printErrorMsg(msg) {
                $('.error-msg').find('ul').html('');
                $('.error-msg').css('display','block');
                $.each( msg, function( key, value ) {
                    $(".error-msg").find("ul").append('<li>'+value+'</li>');
                });
            }

        });
    });



});
</script>


@endsection