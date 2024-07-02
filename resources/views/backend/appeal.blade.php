@extends('layouts.backend')

@section('styles')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
@endsection
@section('page-header')
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Pengajuan Banding</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Appeals</li>
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
                            <th>Pengaju</th>
                            <th>Divisi & Role</th>
                            <th>Alasan Banding</th>
                            <th>Detail Absensi</th>
                            <th>status banding</th>
                            <th>diajukan pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($appeals->count()))
                            @foreach ($appeals as $appeal)
                                <tr>
                                    <td>{{ $appeal->id }}</td>

                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="javascript:void(0)" class="avatar"><img alt="avatar"
                                                    src="@if (!empty($appeal->employee->avatar)) {{ $appeal->employee->avatar }} @else assets/img/profiles/default.jpg @endif"></a>
                                            {{ $appeal->employee->firstname . ' ' . $appeal->employee->lastname . ' (' . $appeal->employee->uuid . ')' }}
                                        </h2>
                                    </td>


                                    <td>{{ $appeal->employee->department->name . ' (' . $appeal->employee->designation->name . ')' }}
                                    </td>
                                    <td>{{ $appeal->reason }}</td>
                                    <td>ID presensi : {{ $appeal->attendance ? $appeal->attendance->id : '' }}<br>Tanggal :
                                        {{ $appeal->attendance ? $appeal->attendance->created_at : '' }}</td>
                                    <td>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                aria-expanded="false">
                                                @if ($appeal->status == 'PENDING')
                                                    <button id="appeal{{ $appeal->id }}"
                                                        class="btn btn btn-warning">{{ $appeal->status }}</button>
                                                @elseif($appeal->status == 'APPROVED')
                                                    <button id="appeal{{ $appeal->id }}"
                                                        class="btn btn btn-success">{{ $appeal->status }}</button>
                                                @else
                                                    <button id="appeal{{ $appeal->id }}"
                                                        class="btn btn btn-danger">{{ $appeal->status }}</button>
                                                @endif
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if ($appeal->status != 'APPROVED')
                                                    <a id="approveButton" class="dropdown-item editbtn"
                                                        onclick="setApproved('{{ $appeal->id }}')"><i
                                                            class="fa fa-thumbs-o-up m-r-5"></i> Izinkan</a>
                                                @endif
                                                @if ($appeal->status != 'REJECTED')
                                                    <a id="rejectButton" class="dropdown-item deletebtn"
                                                        onclick="setRejected('{{ $appeal->id }}')"><i
                                                            class="fa fa-times-circle m-r-5"></i>
                                                        Tolak</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $appeal->created_at }}</td>
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
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable();
        });

        function setApproved(id) {
            $.ajax({
                url: '/appeal/setApproved/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#appeal' + id).removeClass('btn-warning');
                    $('#appeal' + id).removeClass('btn-danger');
                    $('#appeal' + id).addClass('btn-success');
                    $('#appeal' + id).text('APPROVED');
                    $('#approveButton').hide();
                    $('#rejectButton').show();
                }
            });
        }

        function setRejected(id) {
            $.ajax({
                url: '/appeal/setRejected/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#appeal' + id).removeClass('btn-warning');
                    $('#appeal' + id).addClass('btn-danger');
                    $('#appeal' + id).text('REJECTED');
                    $('#rejectButton').hide();
                    $('#approveButton').show();
                }
            });
        }
    </script>
@endsection
