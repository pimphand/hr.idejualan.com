@extends('layouts.backend')

@section('styles')
@endsection

@section('page-header')
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Pegawai</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Employee</li>
            </ul>
        </div>
        <div class="col-auto float-right ml-auto">
            <a href="javascript:void(0)" class="btn add-btn" data-toggle="modal" data-target="#add_employee"><i
                    class="fa fa-plus"></i> Tambah Pegawai</a>
            <div class="view-icons">
                <a href="{{ route('employees') }}" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
                <a href="{{ route('employees-list') }}" class="list-view btn btn-link"><i class="fa fa-bars"></i></a>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="container mb-5">

        <div class="row">

            <div class="four col-md-3">
                <div class="counter-box">
                    <i class="fa fa-thumbs-o-up"></i>
                    <span class="counter">{{$summaries['total_employees']}}</span>
                    <p>Total Pegawai Aktif</p>
                </div>
            </div>
            <div class="four col-md-3">
                <div class="counter-box">
                    <i class="fa fa-group"></i>
                    <span class="counter">{{$summaries['total_resign']}}</span>
                    <p>Total Pegawai Resign</p>
                </div>
            </div>
            <div class="four col-md-3">
                <div class="counter-box">
                    <i class="fa  fa-shopping-cart"></i>
                    <span class="counter">{{$summaries['total_leader']}}</span>
                    <p>Total Leader</p>
                </div>
            </div>
            <div class="four col-md-3">
                <div class="counter-box">
                    <i class="fa  fa-user"></i>
                    <span class="counter">{{$summaries['total_staff']}}</span>
                    <p>Total Staff</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row staff-grid-row">
        @if (!empty($employees->count()))
            @foreach ($employees as $employee)
                <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                    <div class="profile-widget">
                        <div class="profile-img">
                            <a href="javascript:void(0)" class="avatar"><img alt="avatar"
                                    src="@if (!empty($employee->avatar)) {{ $employee->avatar }} @else assets/img/profiles/default.jpg @endif"></a>
                        </div>
                        <div class="dropdown profile-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a data-id="{{ $employee->id }}" data-firstname="{{ $employee->firstname }}"
                                    data-lastname="{{ $employee->lastname }}" data-email="{{ $employee->email }}"
                                    data-phone="{{ $employee->phone }}" data-avatar="{{ $employee->avatar }}"
                                    data-company="{{ $employee->company }}"
                                    data-designation="{{ $employee->designation->id }}"
                                    data-birthday="{{ $employee->birthday }}" data-quota="{{ $employee->leaves_quota }}"
                                    data-department="{{ $employee->department->id }}" data-role="{{ $employee->role }}"
                                    class="dropdown-item editbtn" href="javascript:void(0)" data-toggle="modal"><i
                                        class="fa fa-pencil m-r-5"></i> Edit</a>
                                <a data-id="{{ $employee->id }}" class="dropdown-item deletebtn" href="javascript:void(0)"
                                    data-toggle="modal"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                            </div>
                        </div>
                        <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a
                                href="javascript:void(0)">{{ $employee->firstname }} {{ $employee->lastname }}</a></h4>
                        <h5 class="user-name m-t-10 mb-0 text-ellipsis"><a
                                href="javascript:void(0)">{{ $employee->designation->name }}</a></h5>

                    </div>
                </div>
            @endforeach
            <x-modals.delete :route="'employee.destroy'" :title="'Employee'" />
        @endif

    </div>

    <!-- Add Employee Modal -->
    <div id="add_employee" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pegawai</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('employee.add') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Nama Depan <span class="text-danger">*</span></label>
                                    <input class="form-control" name="firstname" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Nama Belakang</label>
                                    <input class="form-control" name="lastname" type="text">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                    <input class="form-control" name="email" type="email">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">password<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Phone </label>
                                    <input class="form-control" name="phone" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Perusahaan</label>
                                    <input type="text" class="form-control" name="company">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Departemen <span class="text-danger">*</span></label>
                                    <select name="department" class="select">
                                        <option>Pilih Departemen</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Role <span class="text-danger">*</span></label>
                                    <select name="designation" class="select">
                                        <option>Pilih Role</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Foto Pegawai<span class="text-danger">*</span></label>
                                    <input class="form-control floating" name="avatar" type="file">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Tanggal ulang tahun</label>
                                    <input type="date" class="form-control" name="birthday">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Kuota Cuti</label>
                                    <input type="number" class="form-control" name="leaves_quota">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jabatan <span class="text-danger">*</span></label>
                                    <select name="role" class="select">
                                        <option>Pilih Jabatan</option>
                                        <option value="staff">Staff</option>
                                        <option value="leader">Leader</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Employee Modal -->

    <!-- Edit Employee Modal -->
    <div id="edit_employee" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pegawai</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('employee.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Nama Depan <span class="text-danger">*</span></label>
                                    <input class="form-control edit_firstname" name="firstname" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Nama Belakang</label>
                                    <input class="form-control edit_lastname" name="lastname" type="text">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                    <input class="form-control edit_email" name="email" type="email">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">password<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Phone </label>
                                    <input class="form-control edit_phone" name="phone" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Perusahaan</label>
                                    <input type="text" class="form-control edit_company" name="company">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Departemen <span class="text-danger">*</span></label>
                                    <select name="department" selected="selected" id="edit_department" class="select">
                                        <option>Pilih Departemen</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Role <span class="text-danger">*</span></label>
                                    <select name="designation" selected="selected" class="select edit_designation">
                                        <option>Pilih Role </option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Foto Pegawai<span class="text-danger">*</span></label>
                                    <input class="form-control floating edit_avatar" name="avatar" type="file">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Tanggal ulang tahun</label>
                                    <input type="date" class="form-control edit_birthday" name="birthday">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Kuota Cuti</label>
                                    <input type="number" class="form-control edit_quota" name="leaves_quota">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jabatan <span class="text-danger">*</span></label>
                                    <select name="role" class="select edit_role">
                                        <option>Pilih Jabatan</option>
                                        <option value="staff">Staff</option>
                                        <option value="leader">Leader</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Employee Modal -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
                    $('.editbtn').on('click', function() {
                        $('#edit_employee').modal('show');
                        var id = $(this).data('id');
                        var firstname = $(this).data('firstname');
                        var lastname = $(this).data('lastname');
                        var email = $(this).data('email');
                        var phone = $(this).data('phone');
                        var avatar = $(this).data('avatar');
                        var company = $(this).data('company');
                        var designation = $(this).data('designation');
                        var department = $(this).data('department');
                        var birthday = $(this).data('birthday');
                        var quota = $(this).data('quota');
                        var role = $(this).data('role');
                        $('#edit_id').val(id);
                        $('.edit_firstname').val(firstname);
                        $('.edit_lastname').val(lastname);
                        $('.edit_email').val(email);
                        $('.edit_phone').val(phone);
                        $('.edit_company').val(company);
                        $('.edit_designation').val(designation);
                        $('#edit_department').val(department).attr('selected');
                        $('.edit_avatar').attr('src', avatar);
                        $('.edit_birthday').val(birthday);
                        $('.edit_quota').val(quota);
                        $('.edit_role').val(role);
                    });
                    $('.counter').each(function() {
                        $(this).prop('Counter', 0).animate({
                            Counter: $(this).text()
                        }, {
                            duration: 4000,
                            easing: 'swing',
                            step: function(now) {
                                $(this).text(Math.ceil(now));
                            }
                        });
                    });
			});
    </script>
@endsection
