@extends('layouts.app')

@section('content')



<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                @can('users create')
                    <div class="d-flex">
                        <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light" id="create"><i class="ri-add-line fw-semibold align-middle me-1"></i> Create New</a>                  
                    </div>
                @endcan
                </div>
                <h4 class="page-title">Users</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Start::row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <table class="table table-responsive table-bordered" id="users">
                        <thead>
                            <tr>
                                <th width="50px">#</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>USERNAME</th>
                                <th>ORGANIZATION</th>
                                <th>ROLES</th>
                                <th width="200px">ACTIONS</th>
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

<!-- Form Modal -->
<div class="modal fade" id="formModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <form id="userForm" name="userForm" class="form-horizontal">
                    @csrf
                    <div class="alert alert-danger error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12">
                            <input type="hidden" name="userID" id="userID">
                            <label for="name" class="form-label">Nama</label>
                            <select id="name" name="name" class="form-select" aria-label=".form-select-sm example">
                                <option value="">-- Pilih Nama --</option>
                            </select>
                        </div>
                        <div class="col-xl-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="col-xl-12">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" placeholder="Username">
                        </div> 
                        <div class="col-xl-12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        </div> 
                        <div class="col-xl-12">
                            <label for="organization" class="form-label">Organization</label>
                            <select id="organization" name="organization[]" class="form-select" aria-label=".form-select-sm example">
                                <option value="">-- Pilih Organization --</option>
                            </select>
                        </div>
                        <div class="col-xl-12">
                            <label for="roles" class="form-label">Role</label>
                            <select id="roles" name="roles[]" class="form-select" aria-label=".form-select-sm example">
                                <option value="">-- Pilih Role --</option>
                            </select>
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

<!-- CRUD Users -->
<script>
$(document).ready(function (){
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#users').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.users') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_Row_Index', orderable: false, searchable: false},
            {data: 'name'},
            {data: 'email'},
            {data: 'username'},
            {data: 'organization', className: 'text-center'},
            {data: 'roles', orderable: false, searchable: false, className: 'text-center'},
            {data: 'action', orderable: false, searchable: false, className: 'text-center'},
        ],
        dom: "<'row'<'col-md-2'l><'col-md-3'B><'col-md-7'f>>" +
             "<'row'<'col-md-12'tr>>" +
             "<'row'<'col-md-5'i><'col-md-7'p>>",
        buttons: [
            'excelHtml5',
            'pdfHtml5',
            'print'
        ]
    });



    function fetchEmployees() {
        $.ajax({
            url: "{{ route('users.getEmployees') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
            const employees = data.map((employee) => ({
                id: employee.id,
                text: employee.employee_name,
                npk: employee.npk,
            }));

            $('#name').select2({
                data: employees,
                placeholder: '-- Pilih Nama --',
                allowClear: true,
            });

            $('#name').on('change', function() {
                const selectedOption = $(this).select2('data')[0];
                if (selectedOption) {
                $('#username').val(selectedOption.npk);
                } else {
                $('#username').val('');
                }
            });
            },
            error: function(xhr, status, error) {
            console.error('Error fetching employees:', error);
            },
        });
    }



    function fetchOrganizations() {
        $.ajax({
            url: "{{ route('users.getOrganizations') }}", // make sure this is your correct route
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var organizationDropdown = $('#organization');
                organizationDropdown.empty();
                organizationDropdown.append('<option value="">-- Pilih Organization --</option>');

                $.each(data, function (key, organization) {
                    organizationDropdown.append('<option value="' + organization.id + '">' + organization.description + '</option>');
                });
            },
            error: function (error) {
                console.log('Error fetching organizations:', error);
            }
        });
    }

    function fetchRoles() {
        $.ajax({
            url: "{{ route('users.getRoles') }}",
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var rolesDropdown = $('#roles');
                rolesDropdown.empty();
                rolesDropdown.append('<option value="">-- Select Role --</option>');

                $.each(data, function (key, role) {
                    rolesDropdown.append('<option value="' + role.id + '">' + role.name + '</option>');
                });
            },
            error: function (error) {
                console.log('Error fetching roles:', error);
            }
        });
    }
    

    $('#create').click(function () {
        $('#saveBtn').val("create");
        $('#userID').val('');
        $('#name').val('');
        $('#email').val('');
        $('#username').val('');
        $('#employeeName').val('').trigger('change');
        $('#organization').val('').trigger('change');
        $('#roles').val('').trigger('change');
        $('.error-msg').hide();
        $('#userForm').trigger("reset");
        $('.modal-title').html("Create");
        $('#formModel').modal('show');
        fetchEmployees();
        fetchOrganizations();
        fetchRoles();
    });


    $('body').on('click', '.edit', function () {
        var id = $(this).data('id');
        $.get("{{ route('users.users') }}" + '/' + id + '/edit', function (data) {
            $('.error-msg').hide();
            $('#userForm').trigger("reset");
            $('#saveBtn').val("edit");
            $('.modal-title').html("Edit");
            $('#formModel').modal('show');
            $('#userID').val(data.user.id);
            $('#name').val(data.user.name);
            $('#email').val(data.user.email);
            $('#username').val(data.user.username);
            
            // Populate roles
            var roles = data.user.roles.map(function(role) {
                return role.id;
            });
            $('#roles').val(roles).trigger('change');

            // Populate organizations
            $('#organization').val(data.user.organization_id).trigger('change');
        });
    });

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading..');

        $.ajax({
            data: $('#userForm').serialize(),
            url: "{{ route('users.store') }}",
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
                    url: "/users/" + id,
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
        $('.error-msg').css('display','block');
        $.each( msg, function( key, value ) {
            $(".error-msg").find("ul").append('<li>'+value+'</li>');
        });
    }


});
</script>

@endsection


