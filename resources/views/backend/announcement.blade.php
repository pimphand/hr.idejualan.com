@extends('layouts.backend')

@section('styles')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
@endsection
@section('page-header')
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Pengumuman</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Announcement</li>
            </ul>
        </div>
        <div class="col-auto float-right ml-auto">
            <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_department"><i
                    class="fa fa-plus"></i> Tambah Pengumuman</a>
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
                            <th>Judul Pengumuman</th>
                            <th>Deskripsi Pengumuman</th>
                            <th>Gambar Pengumuman</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($announcements->count()))
                            @foreach ($announcements as $announcement)
                                <tr>
                                    <td>{{ $announcement->id }}</td>
                                    <td>{{ $announcement->title }}</td>
                                    <td>{{ $announcement->description }}</td>
                                    <td><img src="{{ url('storage/announcement/'.$announcement->image.'') }}" width="200px" style="border-radius: 20px" alt=""></td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a data-id="{{ $announcement->id }}" data-description="{{ $announcement->description }}" data-title="{{ $announcement->title }}"
                                                    class="dropdown-item editbtn" href="javascript:void(0);"
                                                    data-toggle="modal"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a data-id="{{ $announcement->id }}" class="dropdown-item deletebtn"
                                                    href="javascript:void(0);" data-toggle="modal"><i
                                                        class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <x-modals.delete :route="'announcement.destroy'" :title="'announcement'" />
                            <!-- Edit Department Modal -->
                            <div id="edit_announcement" class="modal custom-modal fade" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Pengumuman</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('announcement') }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" id="edit_id" name="id">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Judul Pengumuman <span
                                                                    class="text-danger">*</span></label>
                                                            <input class="form-control" name="title" id="edit_name"
                                                                type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Judul Deskripsi <span
                                                                    class="text-danger">*</span></label>
                                                            <input class="form-control" name="description" id="edit_description"
                                                                type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Foto Pengumuman<span
                                                                    class="text-danger">*</span></label>
                                                            <input class="form-control floating edit_avatar" name="image"
                                                                type="file">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="submit-section">
                                                    <button type="submit" class="btn btn-primary submit-btn">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Edit Department Modal -->
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Department Modal -->
    <div id="add_department" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengumuman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('announcement') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Judul Pengumuman <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" name="title"
                                        type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Judul Deskripsi <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" name="description"
                                        type="text">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Foto Pengumuman<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control floating edit_avatar" name="image"
                                        type="file">
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
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
            $('.table').on('click', '.editbtn', function() {
                $('#edit_announcement').modal('show');
                var id = $(this).data('id');
                var name = $(this).data('title');
                var description = $(this).data('description');
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_description').val(description);
            });
        });
    </script>
@endsection
