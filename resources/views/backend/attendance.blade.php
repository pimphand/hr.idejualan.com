@extends('layouts.backend')

@section('styles')
<!-- Select2 CSS -->
<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.min.css')}}">
<!-- Datatable CSS -->
<link rel="stylesheet" href="{{asset('assets/css/dataTables.bootstrap4.min.css')}}">

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('page-header')
<div class="row align-items-center">
	<div class="col">
		<h3 class="page-title">Presensi Pegawai</h3>
		<ul class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
			<li class="breadcrumb-item active">Attendance</li>
		</ul>
	</div>
	<div class="col-auto float-right ml-auto">
		<a href="./attendance/export" class="btn btn-secondary"><i class="fa fa-cloud-download"></i> Export menjadi
			Excel</a>
	</div>
	<div class="col-auto float-right ml-auto">
		<a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_attendance"><i class="fa fa-plus"></i>
			Tambah Presensi</a>
	</div>
</div>
@endsection


@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="table-responsive">

			<table class="table datatable table-striped custom-table mb-0">
				<thead>
					<tr>
						<th>Pegawai</th>
						<th>Jam</th>
						<th>Alamat</th>
						<th>Keterangan</th>
						<th>Bukti</th>
						<th>Tanggal</th>
						<th>Status</th>
						<th class="text-end">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($attendances as $attendance)
					<tr>
						<td>
							<h2 class="table-avatar">
								<a href="#" class="avatar"><img alt="avatar"
										src="{{ !empty($attendance->employee->avatar) ? $attendance->employee->avatar : asset('assets/img/profiles/avatar-19.jpg') }}"></a>
								<a href="#">{{$attendance->employee->firstname.' '.
									$attendance->employee->lastname}}<span>{{$attendance->employee->designation->name}}</span></a>
							</h2>
						</td>
						<td>{{date_format(date_create($attendance->time),'H:i a')}}<br><span
								class="badge {{$attendance->type == " PULANG" ? "badge-secondary" : "badge-primary"
								}}">{{$attendance->type}}</span></td>
						<td>{{$attendance->address}}</td>
						<td>Koordinat LatLong : {{$attendance->latitude}},{{$attendance->longitude}}<br>Alamat IP :
							{{$attendance->ip_address}}<br>Kunci Sidik Jari : {{$attendance->employee->device_identifier
							?? 'belum ada sidik jari'}}</td>
						<td><img src="https://sgp1.vultrobjects.com/apphr/public/storage/attendees/{{$attendance->selfie_image}}"
								alt="" width="100px" style="border-radius: 20px"></td>
						<td data-sort="{{$attendance->created_at}}">{{$attendance->created_at->format("d-m-Y")}}</td>
						<td>{{$attendance->status}}</td>
						<td class="text-end">
							<div class="dropdown dropdown-action">
								<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
									aria-expanded="false"><i class="material-icons">more_vert</i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item editbtn" href="javascript:void(0)"
										data-id="{{$attendance->id}}" data-checkin="{{$attendance->checkin}}"
										data-checkout="{{$attendance->checkout}}"
										data-employee="{{$attendance->employee_id}}"><i class="fa fa-pencil m-r-5"></i>
										Edit</a>
									<a class="dropdown-item deletebtn" href="javascript:void(0)"
										data-id="{{$attendance->id}}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			{{ $attendances->links() }}
		</div>
	</div>
</div>

<x-modals.delete route="employees.attendance" title="Attendance" />
<x-modals.popup />
@endsection

@section('scripts')
<!-- Select2 JS -->
<script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script>
<!-- Datatable JS -->
<script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables.bootstrap4.min.js')}}"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
	$(document).ready(function(){
		$('.editbtn').click(function(){
			var id = $(this).data('id');
			var checkin = $(this).data('checkin');
			var checkout = $(this).data('type');
			var employee = $(this).data('employee');
			$('#edit_attendance').modal('show');
			$('#edit_id').val(id);
			$('#edit_employee').val(employee).trigger('change');
			$('#edit_checkin').val(checkin);
			$('#type_edit').val(checkout);
		});
		var dateRangeInput = `<label class="ml-2">Filter Tanggal: <input type="text" class="form-control form-control-sm" name="daterange" value=""></label>
					<label class="">MASUK: <input type="radio" class="form-control mt-1  form-control-sm" name="type" value="MASUK" {{ request()->type == "MASUK" ? "checked" : "" }}></label>	
					<label class="">PULANG: <input type="radio" class="form-control  form-control-sm" name="type" value="PULANG" {{ request()->type == "PULANG" ? "checked" : "" }}></label>
					<button class="btn btn-info btn-sm reset">Reset</button>
		`;
		$('.datatable').DataTable({
			"bDestroy": true,
        	order: [[4, 'desc']],
			"pageLength": 25,
   		});
		$(function() {
			$('input[type="radio"]').on('click', function () {
				// Display an alert when a radio button is clicked
		 	reloadPageCheckbox($(this).val()) 
			});
		});
	$('#DataTables_Table_0_filter').append(dateRangeInput);
	$(function() {
		$('input[name="daterange"]').daterangepicker({
			opens: 'left'
			}, function(start, end, label) {
				console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		
				reloadPageWithDateRange(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
			});
		});
	});
	$(document).ready(function () {
        $('#DataTables_Table_0_filter').on("click", ".reset", function () {
            window.location.href = window.location.origin + window.location.pathname;
        });
    });
	$('.relative.z-0.inline-flex.shadow-sm.rounded-md').parent().remove();
	$(document).ready(function () {
		var parentElement = $('.col-md-6 .dataTables_length');
		var filter = $('.col-md-6 .dataTables_filter');
		
		// Check if the parent element is found
		if (parentElement.length > 0) {
		// Update the parent's class from "col-md-6" to "col-md-2"
			parentElement.parent().removeClass('col-md-6').addClass('col-md-2');
		}
		if (filter.length > 0) {
		// Update the parent's class from "col-md-6" to "col-md-2"
		filter.parent().removeClass('col-md-6').addClass('col-md-10');
		}
	});

	
</script>
@endsection