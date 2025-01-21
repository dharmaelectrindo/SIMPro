@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    @can('projects create')
                        <a class="btn btn-sm btn-primary uil-plus" href="javascript:void(0)" id="create"> Create New</a>
                    @endcan

                </div>
                <h4 class="page-title">Projects</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Start::row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <table class="table table-responsive table-bordered" id="projects">
                        <thead>
                            <tr>
                                <th width="40px">#</th>
                                <th>PROJECT CODE</th>
                                <th>PROJECT NAME</th>
                                <th>CUSTOMER</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>STATUS</th>
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



@endsection

@section('scripts')

<!-- CRUD Project -->
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#projects').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('projects.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_Row_Index', orderable: false, searchable: false},
                {data: 'project_code', className: 'text-center'},
                {data: 'project_name'},
                {data: 'customer'},
                {data: 'start_date'},
                {data: 'end_date'},
                {data: 'status', className: 'text-center'},
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
            $('#projectID').val('');
            $('#projectCode').val('');
            $('#projectName').val('');
            $('#customer').val('');
            $('#start_date').val('');
            $('#end_date').val('');
            $('.error-msg').hide();
            $('#projectForm').trigger("reset");
            $('.modal-title').html("Create");
            $('#formModel').modal('show');
        });

        $('body').on('click', '.edit', function () {
            var id = $(this).data('id');
                $.get("{{ route('projects.index') }}" +'/' + id +'/edit', function (data) {
                $('.error-msg').hide();
                $('#projectForm').trigger("reset");
                $('#saveBtn').val("edit");
                $('.modal-title').html("Edit");
                $('#formModel').modal('show');
                $('#projectID').val(data.id);
                $('#projectCode').val(data.project_code);
                $('#projectName').val(data.project_name);
                $('#customer').val(data.customer);
                $('#startDate').val(data.start_date);
                $('#endDate').val(data.end_date);
            });
        });


        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading..');

            $.ajax({
                data: $('#projectForm').serialize(),
                url: "{{ route('projects.store') }}",
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
                        url: "/projects/" + id, 
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