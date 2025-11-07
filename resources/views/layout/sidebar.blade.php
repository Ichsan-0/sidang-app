<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="index.html" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{ asset('storage/logo_uin.png') }}" alt="Logo UIN" style="height: 40px;">
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2 text-uppercase">SIGMA</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item {{ request()->is('dashboard-role') ? 'active' : '' }}">
      <a href="{{ url('/dashboard-role') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Analytics">Dashboard</div>
      </a>
    </li>

    <!-- Layouts -->
    @php
    $dataMasterRoutes = ['tahun', 'fakultas','prodi', 'jurusan', 'mata-kuliah', 'dosen', 'mahasiswa', 'pimpinan', 'jenis-penelitian','bidang-peminatan'];
    @endphp
    
    @role('superadmin|pimpinan')
    <li class="menu-item {{ in_array(request()->segment(1), $dataMasterRoutes) ? 'open active' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-layout"></i>
        <div data-i18n="Layouts">Data Master</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item {{ request()->is('tahun') ? 'active' : '' }}">
          <a href="{{ route('tahun.index') }}" class="menu-link">
            <div data-i18n="Without menu">Tahun Ajaran</div>
          </a>
        </li>
        <li class="menu-item {{ request()->is('fakultas') ? 'active' : '' }}">
          <a href="{{ route('fakultas.index') }}" class="menu-link">
            <div data-i18n="Without menu">Fakultas</div>
          </a>
        </li>
        <li class="menu-item {{ request()->is('prodi') ? 'active' : '' }}">
          <a href="{{ route('prodi.index') }}" class="menu-link">
            <div data-i18n="Without menu">Data Prodi</div>
          </a>
        </li>
        <li class="menu-item {{ request()->is('pimpinan') ? 'active' : '' }}">
          <a href="{{ route('pimpinan.index') }}" class="menu-link">
            <div data-i18n="Without menu">Pimpinan</div>
          </a>
        </li>
        <li class="menu-item {{ request()->is('jenis-penelitian') ? 'active' : '' }}">
          <a href="{{ route('jenis-penelitian.index') }}" class="menu-link">
            <div data-i18n="Without menu">Jenis Penelitian</div>
          </a>
        </li>
        <li class="menu-item {{ request()->is('bidang-peminatan') ? 'active' : '' }}">
          <a href="{{ route('bidang-peminatan.index') }}" class="menu-link">
            <div data-i18n="Without menu">Bidang Penelitian</div>
          </a>
        </li>
      </ul>
    </li>
    @endrole

    @role('superadmin')
    <li class="menu-item {{ request()->is('user') ? 'active' : '' }}">
      <a href="{{ route('user.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div data-i18n="user">User</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('role-permission') ? 'active' : '' }}">
      <a href="{{ route('role-permission.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-lock-open"></i>
        <div data-i18n="role-permission">Role & Permission</div>
      </a>
    </li>
    @endrole

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Tugas Akhir</span>
    </li>
    
    <li class="menu-item {{ request()->is('bank-judul') ? 'active' : '' }}">
      <a href="{{ route('bank-judul.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-upload"></i>
        <div data-i18n="Pengajuan Judul">Bank Judul</div>
      </a>
    </li>
    
    <li class="menu-item {{ request()->is('tugas-akhir') ? 'active' : '' }}">
      <a href="{{ route('tugas-akhir.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-book"></i>
        <div data-i18n="Pengajuan Judul">Pengajuan Judul</div>
      </a>
    </li>
    
    <li class="menu-item {{ request()->is('seminar') ? 'active' : '' }}">
      <a href="{{ route('seminar') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-calendar-check"></i>
        <div data-i18n="Seminar">Seminar Proposal</div>
      </a>
    </li>
    
    <li class="menu-item {{ request()->is('sidang') ? 'active' : '' }}">
      <a href="{{ route('sidang.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-check-shield"></i>
        <div data-i18n="Sidang">Sidang Munaqasha</div>
      </a>
    </li>
    
    @if(!auth()->user()->hasRole('mahasiswa'))
    <li class="menu-item {{ request()->is('laporan') ? 'active' : '' }}">
      <a href="{{ route('laporan.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div data-i18n="Laporan">Laporan</div>
      </a>
    </li>
    @endif
  </ul>
</aside>
<!-- / Menu -->