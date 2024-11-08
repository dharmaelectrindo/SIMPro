@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h3 class="page-title fw-semibold fs-18 mb-0">Role</h3>
        <div class="ms-md-1 ms-0">
            <nav>
                @can('role create')
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
                        <table class="table text-nowrap table-bordered" id="roles">
                            <thead>
                                <tr>
                                    <th width="50px">#</th>
                                    <th>NAME</th>
                                    {{-- <th>PERMISSION</th> --}}
                                    <th width="107px">ACTIONS</th>
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
                <form id="roleForm" name="roleForm" class="form-horizontal">
                    @csrf
                    <div class="alert alert-danger error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12">
                            <input type="hidden" name="roleID" id="roleID">
                            <label for="roleName" class="form-label">Role Name</label>
                            <input type="text" id="roleName" name="roleName" class="form-control" placeholder="Role Name">
                        </div>                                                                                                                                                                                                                 --}}
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


<!-- Modal Add Permissions -->
<div class="modal fade" id="formModelPermission" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modelHeading"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 bg-dark-transparent">
                <form id="addPermissionForm" name="addPermissionForm" class="form-horizontal">
                    @csrf
                    <div class="alert alert-danger error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="row p-1">
                        <div class="col-xl-12">
                            <input type="hidden" name="rolePermissionID" id="rolePermissionID">
                            <label for="rolePermissionName" class="form-label">Role Name</label>
                            <input type="text" id="rolePermissionName" name="rolePermissionName" class="form-control bg-dark-transparent" placeholder="Role Permission Name" readonly>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="card custom-card">
                            <div class="card-header justify-content-between">
                                <div class="card-title">
                                    Permission
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr id="permissionsTableHeader">
                                        </tr>
                                    </thead>
                                    <tbody id="permissionsContainer">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="savePermissionBtn" value="create">Save</button>
            </div>
        </form>
        </div>
    </div>
</div>




@endsection

@section('scripts')

<!-- CRUD Role -->
<script>
    $(document).ready(function () {
        $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#roles').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('roles.index') }}",
        columns: [
                {data: 'DT_RowIndex',name: 'DT_Row_Index',orderable: false,searchable: false},
                {data: 'name'},
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
        $('#roleID').val('');
        $('#roleName').val('');
        $('.error-msg').hide();
        $('#roleForm').trigger("reset");
        $('.modal-title').html("Create");
        $('#formModel').modal('show');
    });

    $('body').on('click', '.edit', function () {
        var id = $(this).data('id');
            $.get("{{ route('roles.index') }}" +'/' + id +'/edit', function (data) {
            $('.error-msg').hide();
            $('#roleForm').trigger("reset");
            $('#saveBtn').val("edit");
            $('.modal-title').html("Edit");
            $('#formModel').modal('show');
            $('#roleID').val(data.id);
            $('#roleName').val(data.name);
        });
    });

    $('body').on('click', '.add-permission', function () {
        var id = $(this).data('id');

        $.ajax({
            url: '/roles/add-permission/' + id,
            method: 'GET',
            success: function(data) {
                $('#permissionsContainer').empty();
                $('.error-msg').hide();
                $('#rolePermissionForm').trigger("reset");
                $('#savePermissionBtn').val("add-permission");
                $('.modal-title').html("Add Permission");
                $('#formModelPermission').modal('show');

                $('#rolePermissionID').val(data.role.id);
                $('#rolePermissionName').val(data.role.name);

                $.each(data.permissions, function(index, permission) {
                    var isChecked = $.inArray(permission.id, data.rolePermissions) !== -1 ? 'checked' : '';
                    var checkboxHtml = '<div class="col-lg-12">' +
                        '<div class="form-check form-check-md form-switch mb-2">' +
                        '<input class="form-check-input" type="checkbox" role="switch" name="permission[]" value="' + permission.name + '" ' + isChecked + '>' +
                        '<label class="form-check-label">' + permission.name + '</label>' +
                        '</div>' +
                        '</div>';
                    $('#permissionsContainer').append(checkboxHtml);
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });




    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading..');

        $.ajax({
            data: $('#roleForm').serialize(),
            url: "{{ route('roles.store') }}",
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
                url: "/roles/delete",
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


    
    $('#savePermissionBtn').click(function (e) {
        e.preventDefault();
        var $button = $(this);
        $button.html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading..');

        $.ajax({
            data: $('#addPermissionForm').serialize(),
            url: "{{ route('roles.give-permission') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    Swal.fire("Done!", data.message, "success");
                    $('.error-msg').hide();
                    $('#formModelPermission').modal('hide');
                    $button.html('Save');
                    table.draw(); // Refresh table
                } else {
                    printErrorMsg(data.error);
                    $button.html('Save');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                $button.html('Save');
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


    




});
</script>


@endsection