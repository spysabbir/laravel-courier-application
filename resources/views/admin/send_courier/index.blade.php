@extends('admin.layouts.admin_master')

@section('title', 'Send Courier List')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Details</h4>
            </div>
            <div class="card-body">
                <div class="filter">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <select class="form-control filter_data" id="courier_status">
                                <option value="">-- Select Status --</option>
                                <option value="Processing">Processing</option>
                                <option value="On the way">On the way</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-primary" id="send_courier_data">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Tracking Id</th>
                                <th>Sender Type</th>
                                <th>Sender Name</th>
                                <th>Receiver Branch Name</th>
                                <th>Payment Type</th>
                                <th>Courier Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Modal -->
                            <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">View</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="modalBody">

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tbody>
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
        $('#send_courier_data').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('send.courier.list') }}",
                "data":function(e){
                    e.courier_status = $('#courier_status').val();
                },
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'tracking_id', name: 'tracking_id' },
                { data: 'sender_type', name: 'sender_type' },
                { data: 'sender_name', name: 'sender_name' },
                { data: 'branch_name', name: 'branch_name' },
                { data: 'payment_type', name: 'payment_type' },
                { data: 'courier_status', name: 'courier_status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
        // Filter Data
        $(document).on('change', '.filter_data', function(e){
            e.preventDefault();
            $('#send_courier_data').DataTable().ajax.reload();
        })
        // View Data
        $(document).on('click', '.viewBtn', function () {
            var id = $(this).data('id');
            var url = "{{ route('courier.details.view', ":id") }}";
            url = url.replace(':id', id)
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    $('#modalBody').html(response);
                },
            });
        });
    })
</script>
@endsection
