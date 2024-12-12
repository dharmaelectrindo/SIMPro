@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    @can('roles create')
                        <div class="d-flex">
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light" id="create"><i class="ri-add-line fw-semibold align-middle me-1"></i> Create New</a>                  
                        </div>
                    @endcan
                    {{-- <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customers</li>
                    </ol> --}}
                </div>
                <h3 class="page-title fw-semibold fs-18 mb-0">Roles</h3>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table text-nowrap table-bordered" id="roles">
                        <thead>
                            <tr>
                                <th width="25">#</th>
                                <th scope="col">ROLE NAME</th>
                                <th class="text-center" width="150">CREATED AT</th>
                                <th class="text-center" width="150">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div> <!-- end card-body-->
            </div> <!-- end card -->
        </div> <!-- end col-->
    </div>
    <!-- end row -->
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


<!-- Assign Permissions Modal -->
<div class="modal fade" id="formModelAssignPermission" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Assign Permissions</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 bg-dark-transparent">
                <form id="assignPermissionForm" name="assignPermissionForm" class="form-horizontal">
                    @csrf
                    <div class="alert alert-danger error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="row mb-2">
                        <div class="col-xl-12">
                            <input type="hidden" name="rolePermissionID" id="rolePermissionID">
                            <label for="rolePermissionName" class="form-label">Role Name</label>
                            <input type="text" id="rolePermissionName" name="rolePermissionName" class="form-control bg-dark-transparent" placeholder="Role Name" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="row" id="permissionsContainer"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="savePermissionBtn" value="create">Save</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')

<!-- CRUD Roles -->
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
                {data: 'created_at'},
                {data: 'action',orderable: false,searchable: false},
            ],
        dom: "<'row'<'col-md-2'l><'col-md-3'B><'col-md-7'f>>" +
            "<'row'<'col-md-12'tr>>" +
            "<'row'<'col-md-5'i><'col-md-7'p>>"
        ,
        buttons: [
            'excel', 'pdf', 'print'
        ],
        columnDefs: [
            {
                "targets": 2,
                "className": "text-center",
            },
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



    $(document).on('click', '.assign-permissions', function () {
        let roleId = $(this).data('id');

        $.ajax({
            url: `/roles/${roleId}/assign-permissions`,
            method: 'GET',
            success: function (data) {
                $('.modal-title').html("Assign Permissions");
                $('#rolePermissionID').val(data.role.id);
                $('#rolePermissionName').val(data.role.name);

                let permissionsContainer = $('#permissionsContainer');
                permissionsContainer.empty();

                // Group permissions by category
                $.each(data.groupedPermissions, function (category, permissions) {
                    let categoryGroup = `<div class="col-md-12">
                        <h6>${category.toUpperCase()}</h6>
                        <div class="row mb-3">`;

                    permissions.forEach(permission => {
                        var isChecked = $.inArray(permission.id, data.rolePermissions) !== -1 ? 'checked' : '';
                        categoryGroup += 
                            '<div class="col-md-6">' +
                                '<div class="form-check form-switch">' +
                                    '<input class="form-check-input" type="checkbox" role="switch" name="permissions[]" value="' + permission.name + '" ' + isChecked + '>' +
                                    '<label class="form-check-label">' +
                                    permission.name +
                                    '</label>' +
                                '</div>' +
                            '</div>';
                    });

                    categoryGroup += '</div></div>';
                    permissionsContainer.append(categoryGroup);
                });

                $('#formModelAssignPermission').modal('show');
            },
            error: function (err) {
                alert('Failed to load permissions.');
            }
        });
    });


    $('#savePermissionBtn').click(function () {
        if ($('input[name="permissions[]"]:checked').length === 0) {
            alert("Please select at least one permission.");
            return;
        }

        let formData = $('#assignPermissionForm').serialize();

        $.ajax({
            url: "{{ route('roles.give-permissions') }}",
            type: "POST",
            dataType: 'json',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    Swal.fire("Done!", data.message, "success");
                    $('.error-msg').hide();
                    $('#formModelAssignPermission').modal('hide');
                } else {
                    printErrorMsg(data.error);
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });



    // Function display error messages
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