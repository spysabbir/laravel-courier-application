@extends('admin.layouts.admin_master')

@section('title', 'Cost')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">List</h4>
                <div class="action-btn">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal"><i class="bi bi-plus-circle"></i></button>
                    <!-- Modal -->
                    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Create</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="createForm" >
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Unit Name</label>
                                            <select name="unit_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text unit_id_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label>Cost Rate</label>
                                            <input type="number" class="form-control" name="cost_rate" placeholder="Cost rate">
                                            <span class="text-danger error-text cost_rate_error"></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Store</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#recycleModal"><i class="bi bi-recycle"></i></button>
                    <!-- Modal -->
                    <div class="modal fade" id="recycleModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Recycle</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped table-hover table-borderless table-primary align-middle" id="recycleDataTable" style="width: 100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Unit Name</th>
                                                <th>Cost Rate</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="filter">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <select class="form-control filter_data" id="status">
                                <option value="">-- Select Status --</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-borderless table-primary align-middle" id="allDataTable">
                        <thead class="table-light">
                            <tr>
                                <th>Sl No</th>
                                <th>Unit Name</th>
                                <th>Cost Rate</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="editForm">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="hidden" id="cost_id">
                                                <div class="mb-3">
                                                    <label>Unit name</label>
                                                    <select name="unit_id" id="unit_id" class="form-control">
                                                        <option value="">Select</option>
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger error-text update_unit_id_error"></span>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Cost Rate</label>
                                                    <input type="text" class="form-control" name="cost_rate" id="cost_rate">
                                                    <span class="text-danger error-text update_cost_rate_error"></span>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Read Data
        $('#allDataTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('cost.index') }}",
                "data":function(e){
                    e.status = $('#status').val();
                },
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'unit_name', name: 'unit_name' },
                { data: 'cost_rate', name: 'cost_rate' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
        // Filter Data
        $(document).on('change', '.filter_data', function(e){
            e.preventDefault();
            $('#allDataTable').DataTable().ajax.reload();
        })
        // Store Data
        $('#createForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('cost.store') }}",
                type: "POST",
                data: $(this).serialize(),
                beforeSend:function(){
                    $(document).find('span.error-text').text('');
                },
                success: function (response) {
                    if (response.status == 400) {
                        $.each(response.error, function(prefix, val){
                            $('span.'+prefix+'_error').text(val[0]);
                        })
                    }else{
                        $('#createForm')[0].reset();
                        $("#createModal").modal('hide');
                        $('#allDataTable').DataTable().ajax.reload();
                        toastr.success('Cost store successfully.');
                    }
                },
            });
        });
        // Edit Data
        $(document).on('click', '.editBtn', function () {
            var id = $(this).data('id');
            var url = "{{ route('cost.edit', ":id") }}";
            url = url.replace(':id', id)
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    $('#cost_id').val(response.id);
                    $('#unit_id').val(response.unit_id);
                    $('#cost_rate').val(response.cost_rate);
                },
            });
        });
        // Update Data
        $('#editForm').submit(function (event) {
            event.preventDefault();
            var id = $('#cost_id').val();
            var url = "{{ route('cost.update', ":id") }}";
            url = url.replace(':id', id)
            $.ajax({
                url: url,
                type: "PUT",
                data: $(this).serialize(),
                beforeSend:function(){
                    $(document).find('span.error-text').text('');
                },
                success: function (response) {
                    if (response.status == 400) {
                        $.each(response.error, function(prefix, val){
                            $('span.update_'+prefix+'_error').text(val[0]);
                        })
                    }else{
                        $("#editModal").modal('hide');
                        $('#allDataTable').DataTable().ajax.reload();
                        toastr.success('Cost update successfully.');
                    }
                },
            });
        });
        // Delete Data
        $(document).on('click', '.deleteBtn', function(){
            var id = $(this).data('id');
            var url = "{{ route('cost.destroy', ":id") }}";
            url = url.replace(':id', id)
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        success: function(response) {
                            $('#allDataTable').DataTable().ajax.reload();
                            toastr.warning('Cost delete successfully.');
                            $('#recycleDataTable').DataTable().ajax.reload();
                        }
                    });
                }
            })
        })
        // Recycle Data
        $('#recycleDataTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('cost.recycle') }}",
            },
            columns: [
                { data: 'unit_name', name: 'unit_name' },
                { data: 'cost_rate', name: 'cost_rate' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
        // Restore Data
        $(document).on('click', '.restoreBtn', function () {
            var id = $(this).data('id');
            var url = "{{ route('cost.restore', ":id") }}";
            url = url.replace(':id', id)
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    $("#recycleModal").modal('hide');
                    $('#allDataTable').DataTable().ajax.reload();
                    $('#recycleDataTable').DataTable().ajax.reload();
                    toastr.success('Cost restore successfully.');
                },
            });
        });
        // Force Delete Data
        $(document).on('click', '.forceDeleteBtn', function(){
            var id = $(this).data('id');
            var url = "{{ route('cost.force.delete', ":id") }}";
            url = url.replace(':id', id)
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(response) {
                            $("#recycleModal").modal('hide');
                            $('#recycleDataTable').DataTable().ajax.reload();
                            toastr.error('Cost force delete successfully.');
                        }
                    });
                }
            })
        })
        // Status Change Data
        $(document).on('click', '.statusBtn', function () {
            var id = $(this).data('id');
            var url = "{{ route('cost.status', ":id") }}";
            url = url.replace(':id', id)
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    $('#allDataTable').DataTable().ajax.reload();
                    toastr.success('Cost status change successfully.');
                },
            });
        });
    })
</script>
@endsection
