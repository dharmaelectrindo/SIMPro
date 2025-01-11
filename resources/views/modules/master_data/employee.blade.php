@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    @can('employees import')
                        @if($check === null)
                            <a class="btn btn-sm btn-success bi bi-upload float-right" href="javascript:void(0)" id="import"> Import From Excel</a>
                        @endif                     
                    @endcan
                    @can('employees create')
                        <a class="btn btn-sm btn-primary uil-plus" href="javascript:void(0)" id="create"> Create New</a>
                    @endcan

                </div>
                <h4 class="page-title">Employee</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

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
                                <th class="text-center" width="200px">ACTIONS</th>
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
                                <label for="employeeName" class="form-label">Employee Name</label>
                                <input type="text" id="employeeName" name="employeeName" class="form-control" placeholder="Employee Name">
                            </div>
                            <div class="mb-3">
                                <label for="employeePosition" class="form-label">Employee Position</label>
                                <input type="text" id="employeePosition" name="employeePosition" class="form-control" placeholder="Employee Position">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="mb-3">
                                <label for="mobileNumber" class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" id="mobileNumber" name="mobileNumber" data-toggle="input-mask" data-mask-format="0000-0000">
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

<!-- Import Modal -->
<div class="modal fade" data-bs-backdrop="static" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="importHeading"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <!-- File Upload -->
                <form name="importForm" id="importForm" enctype="multipart/form-data">
                    @csrf
                    <div class="alert alert-danger error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <p>Silahkan upload file berformat <strong>Excel (.xls/.xlsx)</strong> 
                        untuk import data karyawan, template upload bisa di download <a href={{ asset('files/TEMPLATE_UPLOAD_EMPLOYEE.xlsx') }}><i><u>disini</u></i></a> </p>
                    <div>
                        <input type="file" class="form-control" id="file" name="file" required>
                    </div>
                    <div class="progress" style="display:none; margin-top: 15px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-lg btn-primary px-4 mt-3" id="importBtn" value="import">Upload</button>
                    </div>
                </form>
            </div>
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
                {data: 'DT_RowIndex', name: 'DT_Row_Index', orderable: false, searchable: false},
                {data: 'npk', className: 'text-center'},
                {data: 'employee_name'},
                {data: 'employee_position'},
                {data: 'email'},
                {data: 'mobile_number', className: 'text-center'},
                {data: 'action', orderable: false, searchable: false, className: 'text-center'},
            ],
            dom: "<'row'<'col-md-2'l><'col-md-3'B><'col-md-7'f>>" +
                "<'row'<'col-md-12'tr>>" +
                "<'row'<'col-md-5'i><'col-md-7'p>>",
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
                $('#npk').val(data.npk);
                $('#employeeName').val(data.employee_name);
                $('#employeePosition').val(data.employee_position);
                $('#email').val(data.email);
                $('#mobileNumber').val(data.mobile_number);
            });
        });

        $('#import').click(function () {
            $('#importBtn').html("Upload");
            $('#file').val('');
            $('.error-import-msg').hide();
            $('#importForm').trigger("reset");
            $('#importHeading').html("Import Employee");
            $('#importModal').modal('show');
        });


        $('#importForm').on('submit', function(e) {
            e.preventDefault();

            $('#importBtn').html(
                '<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading...'
            ).attr('disabled', 'disabled');

            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('employees.import') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    // Upload progress
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total * 100;
                            $('.progress-bar').css('width', percentComplete + '%').text(percentComplete.toFixed(0) + '%');
                        }
                    }, false);
                    return xhr;
                },
                beforeSend: function() {
                    $('.progress').show();
                    $('.progress-bar').css('width', '0%').text('0%');
                },
                success: function(response) {
                    $('.progress-bar').css('width', '100%').text('100%');
                    setTimeout(function() {
                        $('.progress').hide();
                    }, 500);
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: "Data telah berhasil di import",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Enable submit button
                    $('#importBtn').html('Upload').removeAttr('disabled');
                    $('#importForm').trigger("reset");
                    $('#importModal').modal('hide');
                    table.draw();
                },
                error: function(response) {
                    console.log(response);
                    $('.error-msg').show();
                    $('.error-msg ul').html('');
                    $.each(response.responseJSON.errors, function(key, value) {
                        $('.error-msg ul').append('<li>' + value + '</li>');
                    });
                    $('#importBtn').html('Upload').removeAttr('disabled');
                    table.draw();
                },
                complete: function() {
                    // Enable submit button
                    $('#importBtn').html('Upload').removeAttr('disabled');
            }
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
                        url: "/employees/" + id, 
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