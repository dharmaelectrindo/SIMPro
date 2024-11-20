@extends('layouts.app')

@section('content')



<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h3 class="page-title fw-semibold fs-18 mb-0">Users</h3>
        <div class="ms-md-1 ms-0">
            <nav>
                @can('users create')
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
                        <table class="table text-nowrap table-bordered" id="users">
                            <thead>
                                <tr>
                                    <th width="50px">#</th>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>USERNAME</th>
                                    <th>ORGANIZATION</th>
                                    <th>ROLES</th>
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
                <form id="userForm" name="userForm" class="form-horizontal">
                    @csrf
                    <div class="alert alert-danger error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12">
                            <input type="hidden" name="userID" id="userID">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Name">
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
                            <label for="role" class="form-label">Role</label>
                            <select id="role" name="role[]" class="form-select" aria-label=".form-select-sm example">
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

<!-- CRUD User -->
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
                {data: 'DT_RowIndex',name: 'DT_Row_Index',orderable: false,searchable: false},
                {data: 'name'},
                {data: 'email'},
                {data: 'username'},
                {data: 'organization_id'},
                {data: 'roles'},
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


    function fetchEmployees() {
        $.ajax({
            url: "{{ route('users.getEmployees') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var employees = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    local: $.map(data, function(employee) {
                        return employee.employee_name; 
                    })
                });

                $('#name').typeahead(
                    {
                        hint: true,
                        highlight: true,
                        minLength: 1
                    },
                    {
                        name: 'name',
                        source: employees
                    }
                );

            },
            error: function(error) {
                console.log('Error fetching employees:', error);
            }
        });
    }

    $('#employeeName').on('change', function() {
        var employeeName = $(this).val();  

        if (employeeName) {
            $.ajax({
                url: "{{ route('users.getEmployeeId') }}",
                type: 'GET',
                data: {
                    employee_name: employeeName
                },
                dataType: 'json',
                success: function(response) {
                    if (response.employee_id) {
                        $('#employee_id').val(response.employee_id);  
                    } else {
                        $('#employee_id').val('');  
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching employee ID:', error);
                    $('#employee_id').val(''); 
                }
            });
        } else {
            $('#employee_id').val('');
        }
    });


    function fetchRoles() {
        $.ajax({
            url: "{{ route('users.getRoles') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var rolesDropdown = $('#roles');
                rolesDropdown.empty();
                rolesDropdown.append('<option value="">-- Pilih Roles --</option>');
                $.each(data, function(key, role) {
                    rolesDropdown.append('<option value="' + role.name + '">' + role.name + '</option>');
                });
            },
            error: function(error) {
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
        $('#organization').val('');
        $('#roles').val('');
        $('.error-msg').hide();
        $('#userForm').trigger("reset");
        $('.modal-title').html("Create");
        $('#formModel').modal('show');
        fetchEmployees();
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
            $('#password').val();

            var roles = data.user.roles.map(function(role) {
                return role.name;
            });
            $('#roles').val(roles).trigger('change');

            var rolesSelect = $('#roles');
            rolesSelect.empty();
            $.each(data.roles, function(key, value) {
                rolesSelect.append('<option value="' + key + '">' + value + '</option>');
            });

            $('#roles').val(roles).trigger('change');
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
                url: "/users/delete",
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


