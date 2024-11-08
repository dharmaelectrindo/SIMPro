@extends('layouts.master')

@section('styles')


@endsection

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Claim Category</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                @can('claim-category create')
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
                        <table class="table text-nowrap table-bordered" id="claim-category">
                            <thead>
                                <tr>
                                    <th width="25">#</th>
                                    <th scope="col">CLAIM CATEGORY NAME</th>
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
                <form id="claimCategoryForm" name="claimCategoryForm" class="form-horizontal">
                    @csrf
                    <div class="alert alert-danger error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="row gy-2">
                    <div class="col-xl-12">
                        <input type="hidden" name="claimCategoryID" id="claimCategoryID">
                        <label for="claimCategoryName" class="form-label">Claim Category Name</label>
                        <input type="text" id="claimCategoryName" name="claimCategoryName" class="form-control" placeholder="Claim Category Name">
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

<!-- CRUD Customers -->
<script>
    $(document).ready(function () {
        $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var table = $('#claim-category').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('claimcategory.index') }}",
        columns: [
                {data: 'DT_RowIndex',name: 'DT_Row_Index',orderable: false,searchable: false},
                {data: 'claimCategoryName'},
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
        $('#claimCategoryID').val('');
        $('#claimCategoryName').val('');
        $('.error-msg').hide();
        $('#claimCategoryForm').trigger("reset");
        $('.modal-title').html("Create");
        $('#formModel').modal('show');
    });

    $('body').on('click', '.edit', function () {
        var id = $(this).data('id');
            $.get("{{ route('claimcategory.index') }}" +'/' + id +'/edit', function (data) {
            $('.error-msg').hide();
            $('#claimCategoryForm').trigger("reset");
            $('#saveBtn').val("edit");
            $('.modal-title').html("Edit");
            $('#formModel').modal('show');
            $('#claimCategoryID').val(data.id);
            $('#claimCategoryName').val(data.claimCategoryName);
        });
    });


    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading..');

        $.ajax({
            data: $('#claimCategoryForm').serialize(),
            url: "{{ route('claimcategory.store') }}",
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
                url: "/claimcategory/delete",
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