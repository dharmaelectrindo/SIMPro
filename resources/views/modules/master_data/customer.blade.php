@extends('layouts.app')

@section('content')
 


<div class="container-fluid"> 

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h3 class="page-title fw-semibold fs-18">Customers</h3>
        <div class="ms-md-1 ms-0">
            <nav>
                @can('customers create')
                    <div class="d-flex">
                        <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light" id="create"><i class="ri-add-line fw-semibold align-middle me-1"></i> Create New</a>                  
                    </div>
                @endcan
            </nav>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Start::row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <table class="table table-responsive table-bordered" id="customers">
                        <thead>
                            <tr>
                                <th width="40px">#</th>
                                <th>CUSTOMER CODE</th>
                                <th>CUSTOMER NAME</th>
                                <th class="text-center" width="185px">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <form id="customerForm" name="customerForm" class="form-horizontal" autocomplete="off">
                    @csrf
                    <div class="alert alert-danger error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12">
                            <input type="hidden" name="customerID" id="customerID">
                            <label for="customerCode" class="form-label">Customer Code</label>
                            <input type="text" id="customerCode" name="customerCode" class="form-control" placeholder="Customer Code">
                        </div>    
                        <div class="col-xl-12">
                            <label for="customerName" class="form-label">Customer Name</label>
                            <input type="text" id="customerName" name="customerName" class="form-control" placeholder="Customer name">
                        </div>                                                                                                                                                                                                      
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save</button>
            </div>
        </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<!-- CRUD Customer -->
<script>
$(document).ready(function (){
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#customers').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('customers.index') }}",
        columns: [
                {data: 'DT_RowIndex',name: 'DT_Row_Index',orderable: false,searchable: false},
                {data: 'customer_code'},
                {data: 'customer_name'},
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
        $('#customerID').val('');
        $('#customerCode').val('');
        $('#customerName').val('');
        $('.error-msg').hide();
        $('#customerForm').trigger("reset");
        $('.modal-title').html("Create");
        $('#formModel').modal('show');
    });


    $('body').on('click', '.edit', function () {
        var id = $(this).data('id');
        $.get("{{ route('customers.index') }}" + '/' + id + '/edit', function (data) {
            $('.error-msg').hide();
            $('#customerForm').trigger("reset");
            $('#saveBtn').val("edit");
            $('.modal-title').html("Edit");
            $('#formModel').modal('show');
            $('#customerID').val(data.id);
            $('#customerCode').val(data.customer_code);
            $('#customerName').val(data.customer_name);
        });
    });


    $('#saveBtn').click(function(e) {
        e.preventDefault();
        $(this).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading..');

        $.ajax({
            data: $('#customerForm').serialize(),
            url: "{{ route('customers.store') }}",
            type: "POST",
            dataType: 'json',
            beforeSend: function() {
                $('#saveBtn').attr('disabled', true);
            },
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    Swal.fire("Done!", data.message, "success");
                    $('.error-msg').hide();
                    $('#formModel').modal('hide');
                    $('#saveBtn').html('Save');
                    table.draw();
                } else {
                    printErrorMsg(data.error);
                    $('#saveBtn').html('Save');
                }
                $('#saveBtn').attr('disabled', false);
            },
            error: function(data) {
                console.log('Error:', data);
                $('#saveBtn').html('Save');
                $('#saveBtn').attr('disabled', false);
            }
        });
    });


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
                    type: "DELETE", 
                    url: "/customers/" + id, 
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content') 
                    },
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
        });
    });


    // Function error message
    function printErrorMsg(msg) {
        $('.error-msg').find('ul').html('');
        $('.error-msg').css('display', 'block');
        $.each(msg, function(key, value) {
            $(".error-msg").find("ul").append('<li>' + value + '</li>');
        });
    }

});
</script>

@endsection


