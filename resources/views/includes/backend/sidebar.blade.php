<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Menu Utama</span>
                </li>
                <li class="{{ route_is('dashboard') ? 'active' : '' }}">
                    <a href="{{route('dashboard')}}"><i class="la la-dashboard"></i> <span> Dashboard</span></a>
                </li>
                {{-- <li class="submenu">
                    <a href="#"><i class="la la-cube"></i> <span> Apps</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        
                        <li><a class="{{ route_is('contacts') ? 'active' : '' }}" href="{{route('contacts')}}">Contacts</a></li>
                    </ul>
                </li> --}}
                <li class="menu-title">
                    <span>Kepegawaian</span>
                </li>
                <li class="submenu">
                    <a href="#" class="{{ route_is(['employees','employees-list']) ? 'active' : '' }} noti-dot"><i class="la la-user"></i> <span> Pegawai</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="{{ route_is('employees') ? 'active' : '' }}" href="{{route('employees')}}">Semua Pegawai</a></li>
                        <li><a class="{{ route_is('holidays') ? 'active' : '' }}" href="{{route('holidays')}}">Libur</a></li>
                        <li><a class="{{ route_is('employees.attendance') ? 'active' : '' }}" href="{{route('employees.attendance')}}">Presensi</a></li>
                        <li><a class="{{ route_is('appeals') ? 'active' : '' }}" href="{{route('appeal')}}">Aju Banding</a></li>
                        <li><a class="{{ route_is('employees.attendanceOutside') ? 'active' : '' }}" href="{{route('employees.attendanceOutside')}}">Presensi diluar kantor</a></li>
                        <li><a class="{{ route_is('leave-type') ? 'active' : '' }}" href="{{route('leave-type')}}">Jenis Cuti</a></li>
                        <li><a class="{{ route_is('employee-leave') ? 'active' : '' }}" href="{{route('employee-leave')}}">Cuti (Pegawai)</a></li>
                        <li><a class="{{ route_is('departments') ? 'active' : '' }}" href="{{route('departments')}}">Divisi / Departemen</a></li>
                        <li><a class="{{ route_is('designations') ? 'active' : '' }}" href="{{route('designations')}}">Role / Tugas</a></li>
                        <li><a class="{{ route_is('overtime') ? 'active' : '' }}" href="{{route('overtime')}}">Lembur</a></li>
                    </ul>
                </li>
                
                <li class="{{ route_is('clients') ? 'active' : '' }}">
                    <a href="{{route('clients')}}"><i class="la la-users"></i> <span>Klien</span></a>
                </li>

                <li class="{{ route_is('announcement') ? 'active' : '' }}">
                    <a href="{{route('announcement')}}"><i class="la la-bullhorn"></i> <span>Pengumuman</span></a>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-rocket"></i> <span> Proyek </span> <span class="menu-arrow"></span></a>
                    <ul style="display: non;">
                        <li>
                            <a class="{{ route_is(['projects','project-list']) ? 'active' : '' }}" href="{{route('projects')}}">Proyek</a>
                        </li>
                    </ul>
                </li>
                
                <li class="{{route_is('leads') ? 'active' : '' }}"> 
                    <a href="{{route('leads')}}"><i class="la la-user-secret"></i> <span>Pemimpin Divisi</span></a>
                </li>

                <li class="{{route_is('tickets') ? 'active' : '' }}"> 
                    <a href="{{route('tickets')}}"><i class="la la-ticket"></i> <span>Permintaan</span></a>
                </li>

                <li class="menu-title"> 
                    <span>HR</span>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-files-o"></i> <span> Akunting </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="{{ route_is('invoices.*') ? 'active' : '' }}" href="{{route('invoices.index')}}">Tagihan</a></li>
                        <li><a class="{{ route_is('expenses') ? 'active' : '' }}" href="{{route('expenses')}}">Pengeluaran</a></li>
                        <li><a class="{{ route_is('provident-fund') ? 'active' : '' }}" href="{{route('provident-fund')}}">Dana Penghematan</a></li>
                        <li><a class="{{ route_is('taxes') ? 'active' : '' }}" href="{{route('taxes')}}">Pajak</a></li>
                    </ul>
                </li>
                
                <li class="{{ route_is('policies') ? 'active' : '' }}">
                    <a href="{{route('policies')}}"><i class="la la-file-pdf-o"></i> <span>Peraturan Perusahaan</span></a>
                </li>
                
                <li class="submenu">
                    <a href="#"><i class="la la-briefcase"></i> <span> Pekerjaan </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="{{ route_is('jobs') ? 'active' : '' }}" href="{{route('jobs')}}"> Manajemen Pekerjaan </a></li>
                        <li><a class="{{ route_is('job-applicants') ? 'active' : '' }}" href="{{route('job-applicants')}}"> Kandidat Pelamar Kerja </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-crosshairs"></i> <span> Target </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="{{ route_is('goal-tracking') ? 'active' : '' }}" href="{{route('goal-tracking')}}"> Daftar Target </a></li>
                        <li><a class="{{ route_is('goal-type') ? 'active' : '' }}" href="{{route('goal-type')}}"> Tipe Target </a></li>
                    </ul>
                </li>
                <li class="{{ route_is('assets') ? 'active' : '' }}"> 
                    <a href="{{route('assets')}}"><i class="la la-object-ungroup"></i> <span>Asset</span></a>
                </li>
                <li>
                    <a class="{{ route_is('activity') ? 'active' : '' }}" href="{{route('activity')}}"><i class="la la-bell"></i> <span>Log Aktivitas</span></a>
                </li>
                <li class="{{ route_is('users') ? 'active' : '' }}">
                    <a href="{{route('users')}}"><i class="la la-user-plus"></i> <span>Users</span></a>
                </li>
              
                <li>
                    <a class="{{ route_is('settings.theme') ? 'active' : '' }}" href="{{route('settings.theme')}}"><i class="la la-cog"></i> <span>Pengaturan</span></a>
                </li>
                <li class="{{ Request::is('backups') ? 'active' : '' }}">
                    <a href="{{ route('backups') }}"
                        ><i class="la la-cloud-upload"></i> <span>Backups </span>
                    </a>
                </li>
                
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
