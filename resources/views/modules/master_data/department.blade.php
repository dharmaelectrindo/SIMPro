@extends('layouts.app')

@section('content')
 


<div class="container-fluid"> 

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h3 class="page-title fw-semibold fs-18">Departments</h3>
        <div class="ms-md-1 ms-0">
            <nav>
                @can('departments create')
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
                    <table class="table table-responsive table-bordered" id="department">
                        <thead>
                            <tr>
                                <th width="40px">#</th>
                                <th>DEPARTMENT NAME</th>
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
                <h6 class="modal-title" id="modelHeading"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <form id="departmentForm" name="departmentForm" class="form-horizontal">
                    @csrf
                    <div class="alert alert-danger error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12">
                            <input type="hidden" name="departmentID" id="departmentID">
                            <label for="departmentName" class="form-label">Department Name</label>
                            <input type="text" id="departmentName" name="departmentName" class="form-control" placeholder="Department Name">
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

<!-- CRUD User -->
<script>
$(document).ready(function (){
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#department').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('departments.index') }}",
        columns: [
                {data: 'DT_RowIndex',name: 'DT_Row_Index',orderable: false,searchable: false},
                {data: 'department_name'},
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
        $('#departmentID').val('');
        $('#departmentName').val('');
        $('.error-msg').hide();
        $('#departmentForm').trigger("reset");
        $('.modal-title').html("Create");
        $('#formModel').modal('show');
    });


    $('body').on('click', '.edit', function () {
        var id = $(this).data('id');
        $.get("{{ route('departments.index') }}" + '/' + id + '/edit', function (data) {
            $('.error-msg').hide();
            $('#departmentForm').trigger("reset");
            $('#saveBtn').val("edit");
            $('.modal-title').html("Edit");
            $('#formModel').modal('show');
            $('#departmentID').val(data.id);
            $('#departmentName').val(data.department_name);
        });
    });


    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading..');

        $.ajax({
            data: $('#departmentForm').serialize(),
            url: "{{ route('departments.store') }}",
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

        function printErrorMsg(msg) {
            $('.error-msg').find('ul').html('');
            $('.error-msg').css('display','block');
            $.each( msg, function( key, value ) {
                $(".error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }
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
                type: "POST",
                url: "/departments/delete",
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


