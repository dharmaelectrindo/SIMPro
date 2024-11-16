@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h3 class="page-title fw-semibold fs-18 mb-0">Employees</h3>
        <div class="ms-md-1 ms-0">
            <nav>
                @can('employees create')
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
                <div class="card-body">
                    <table class="table table-responsive table-bordered" id="employees">
                        <thead>
                            <tr>
                                <th width="40px">#</th>
                                <th>NPK</th>
                                <th>EMPLOYEE NAME</th>
                                <th>EMAIL</th>
                                <th>EMPLOYEE POSITION</th>
                                <th>MOBILE NUMBER</th>
                                <th width="410px">ACTIONS</th>
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
                <form id="employeeForm" name="employeeForm" class="form-horizontal">
                    @csrf
                    <div class="alert alert-danger error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12">
                            <input type="hidden" name="employeeID" id="employeeID">
                            <div class="mb-3">
                                <label for="npk" class="form-label">NPK</label>
                                <input type="text" id="npk" name="npk" class="form-control" placeholder="NPK">
                            </div>
                            <div class="mb-3">
                                <label for="employee_name" class="form-label">Employee Name</label>
                                <input type="text" id="employee_name" name="employee_name" class="form-control" placeholder="Employee Name">
                            </div>
                            <div class="mb-3">
                                <label for="employee_position" class="form-label">Employee Position</label>
                                <input type="text" id="employee_position" name="employee_position" class="form-control" placeholder="Employee Position">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="mb-3">
                                <label for="mobile_number" class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" id="mobile_number" name="mobile_number" data-toggle="input-mask" data-mask-format="0000-0000">
                            </div>                            
                        </div>                                                         
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBtn" value="create">Save</button>
            </div>
        </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')

<!-- CRUD Employees -->
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    var table = $('#employees').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('employees.index') }}",
        columns: [
                {data: 'DT_RowIndex',name: 'DT_Row_Index',orderable: false,searchable: false},
                {data: 'npk'},
                {data: 'employee_name'},
                {data: 'employee_position'},
                {data: 'email'},
                {data: 'mobile_number'},
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
        $('#employeeID').val('');
        $('#employeeName').val('');
        $('.error-msg').hide();
        $('#employeeForm').trigger("reset");
        $('.modal-title').html("Create");
        $('#formModel').modal('show');
    });

    $('body').on('click', '.edit', function () {
        var id = $(this).data('id');
            $.get("{{ route('employees.index') }}" +'/' + id +'/edit', function (data) {
            $('.error-msg').hide();
            $('#employeeForm').trigger("reset");
            $('#saveBtn').val("edit");
            $('.modal-title').html("Edit");
            $('#formModel').modal('show');
            $('#employeeID').val(data.id);
            $('#employeeName').val(data.employee_name);
        });
    });


    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading..');

        $.ajax({
            data: $('#employeeForm').serialize(),
            url: "{{ route('employees.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                        Swal.fire("Done!", data.message, "success");
                        $('.error-msg').hide();
                        $('#formModel').modal('hide');
                        $('#saveBtn').html('Save');

                }else{
                    printErrorMsg(data.error);
                    $('#saveBtn').html('Save');
                }
                    table.draw();
                },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save');
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
                    url: "/roles/" + id, 
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
        $.each(msg, function (key, value) {
            $(".error-msg").find("ul").append('<li>' + value + '</li>');
        });
    }




});
</script>


@endsection