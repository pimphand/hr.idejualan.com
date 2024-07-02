@extends('layouts.backend')

@section('styles')
<!-- Datatable CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('page-header')
<div class="row align-items-center">
    <div class="col">
        <h3 class="page-title">Pengajuan Izin Presensi</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">AttendanceOutside</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div>
            <table class="table table-striped custom-table mb-0 datatable">
                <thead>
                    <tr>
                        <th style="width: 30px;">#</th>
                        <th>Pegawai</th>
                        <th>Divisi & Role</th>
                        <th>Jam</th>
                        <th>Alamat</th>
                        <th>Alasan izin</th>
                        <th>Jarak dari Kantor</th>
                        <th>Tanggal</th>
                        <th>Status Izin</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($attendances->count()))
                    @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->id }}</td>
                        <td>
                            <h2 class="table-avatar">
                                <a href="javascript:void(0)" class="avatar"><img alt="avatar"
                                        src="@if (!empty($attendance->employee->avatar)) {{ $attendance->employee->avatar }} @else assets/img/profiles/default.jpg @endif"></a>
                                {{ $attendance->employee->firstname . ' ' . $attendance->employee->lastname . ' (' .
                                $attendance->employee->uuid . ')' }}
                            </h2>
                        </td>


                        <td>{{ $attendance->employee->department->name . ' (' . $attendance->employee->designation->name
                            . ')' }}
                        </td>
                        <td>{{ $attendance->time }}</td>
                        <td>{{ $attendance->address }}</td>
                        <td>{{ $attendance->permission_reason }}</td>
                        <td>{{ $attendance->distance }} Meter</td>
                        <td>{{ Carbon\Carbon::parse($attendance->created_at)->format('d/m/Y') }}</td>
                        <td>
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                    @if ($attendance->status_by_hr == 'pending')
                                    <button id="attend{{ $attendance->id }}" class="btn btn btn-warning">{{
                                        $attendance->status_by_hr }}</button>
                                    @elseif($attendance->status_by_hr == 'approved')
                                    <button id="attend{{ $attendance->id }}" class="btn btn btn-success">{{
                                        $attendance->status_by_hr }}</button>
                                    @else
                                    <button id="attend{{ $attendance->id }}" class="btn btn btn-danger">{{
                                        $attendance->status_by_hr }}</button>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @if ($attendance->status_by_hr != 'approved')
                                    <a id="approveButton" class="dropdown-item editbtn"
                                        onclick="setApproved('{{ $attendance->id }}')"><i
                                            class="fa fa-thumbs-o-up m-r-5"></i> Izinkan</a>
                                    @endif
                                    @if ($attendance->status_by_hr != 'rejected')
                                    <a id="rejectButton" class="dropdown-item deletebtn"
                                        onclick="setRejected('{{ $attendance->id }}')"><i
                                            class="fa fa-times-circle m-r-5"></i>
                                        Tolak</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    {{-- edit --}}
                    <!-- /Edit Department Modal -->
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- /Add Department Modal -->
@endsection

@section('scripts')
<!-- Datatable JS -->
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function() {
        var leavesTable = $('.datatable').DataTable({
        order: [[7, 'desc']],
        "bDestroy": true,
        });
        var dateRangeInput = '<label class="ml-2">Filter Tanggal: <input type="text" class="form-control form-control-sm" name="daterange" value=""></label> <button class="btn btn-info btn-sm reset">Reset</button>';
        $('#DataTables_Table_0_filter').append(dateRangeInput);

        $(function() {
            var defaultStartDate = moment();
            var defaultEndDate = moment();

            @if(request()->start_date != null)
                // Set the default start date to the value from the request
                defaultStartDate = moment('{{ request()->start_date }}');
                // You can also set a default end date if needed
                defaultEndDate = moment('{{ request()->end_date }}');
            @endif

		    $('input[name="daterange"]').daterangepicker({
			opens: 'left',
            startDate: defaultStartDate, // Set the default start date
            endDate: defaultEndDate,
			}, function(start, end, label) {
                
				reloadPageWithDateRange(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
			});
		});

    });

	$(document).ready(function () {
        $('#DataTables_Table_0_filter').on("click", ".reset", function () {
            window.location.href = window.location.origin + window.location.pathname;
        });
    });

        function setApproved(id) {
            $.ajax({
                url: '/employees/attendanceOutside/setApproved/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#attend' + id).removeClass('btn-warning');
                    $('#attend' + id).removeClass('btn-danger');
                    $('#attend' + id).addClass('btn-success');
                    $('#attend' + id).text('approved');
                    $('#approveButton').hide();
                    $('#rejectButton').show();
                }
            });
        }

        function setRejected(id) {
            $.ajax({
                url: '/employees/attendanceOutside/setRejected/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#attend' + id).removeClass('btn-warning');
                    $('#attend' + id).addClass('btn-danger');
                    $('#attend' + id).text('rejected');
                    $('#rejectButton').hide();
                    $('#approveButton').show();
                }
            });
        }
</script>
@endsection