<ul class="navbar-nav sidebar sidebar-dark accordion toggled" id="accordionSidebar" style="background-color: #CC59AA">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin SB</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item {{ request()->is('program*') ? 'active' : '' }}">
        <a href="{{ route('program') }}" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>Program</span>
        </a>
    </li>
    <li class="nav-item {{ request()->is('donasi*') ? 'active' : '' }}">
        <a href="{{ route('donasi') }}" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>Donasi</span>
        </a>
    </li>
    <li class="nav-item {{ request()->is('doa*') ? 'active' : '' }}">
        <a href="{{ route('doa.donasi') }}" class="nav-link">
            <i class="fas fa-fw fa-user"></i>
            <span>Doa Donatur</span>
        </a>
    </li>
    <li class="nav-item {{ request()->is('fundraiser') ? 'active' : '' }}">
        <a href="{{ route('fundraiser') }}" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>Fundraiser</span>
        </a>
    </li>
    <li class="nav-item {{ request()->is('admin*') || request()->is('mitra*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="false" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-user"></i>
            <span>Users</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar" style="">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">User List:</h6>
                <a class="collapse-item {{ request()->is('mitra*') ? 'active' : '' }}"
                    href="{{ route('mitra') }}">Mitra</a>
                <a class="collapse-item {{ request()->is('admin*') ? 'active' : '' }}"
                    href="{{ route('admin') }}">Admin</a>
            </div>
        </div>
    </li>
    {{-- <li class="nav-item {{ (request()->is('amal*')) ? 'active' : '' }}">
        <a href="{{ route('amal') }}" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>Kotak Amal</span>
        </a>
    </li> --}}
    {{-- <li class="nav-item {{ request()->is('qurban*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#subQurban" aria-expanded="false" aria-controls="subQurban">
            <i class="fas fa-fw fa-user"></i>
            <span>Qurban</span>
        </a>
        <div id="subQurban" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar" style="">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menu Qurban</h6>
                <a class="collapse-item {{ (request()->is('qurban/transaction*')) ? 'active' : '' }}" href="{{ route('qurban.transaction.index') }}">Transaksi</a>
                <a class="collapse-item {{ (request()->is('qurban/page*')) ? 'active' : '' }}" href="{{ route('qurban.page.index') }}">Landing Page</a>
                <a class="collapse-item {{ (request()->is('qurban/package*')) ? 'active' : '' }}" href="{{ route('qurban.package.index') }}">Paket</a>
            </div>
        </div>
    </li> --}}
    <li class="nav-item {{ request()->is('news*') ? 'active' : '' }}">
        <a href="{{ route('news') }}" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>Berita Penyaluran</span>
        </a>
    </li>
    {{-- <li class="nav-item {{ (request()->is('withdraw*')) ? 'active' : '' }}">
        <a href="{{ route('withdraw') }}" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>Pencairan</span>
        </a>
    </li> --}}
    <li class="nav-item {{ request()->is('report*') ? 'active' : '' }}">
        <a href="{{ route('report') }}" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>Laporan</span>
        </a>
    </li>
    <li class="nav-item {{ request()->is('channel*') ? 'active' : '' }}">
        <a href="{{ route('channel') }}" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>Channel Pembayaran</span>
        </a>
    </li>
    <li class="nav-item {{ request()->is('page*') ? 'active' : '' }}">
        <a href="{{ route('page') }}" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>Pages Content</span>
        </a>
    </li>
    {{-- <li class="nav-item {{ (request()->is('mitra*')) ? 'active' : '' }}">
        <a href="{{ route('mitra') }}" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>User Mitra</span>
        </a>
    </li>
    <li class="nav-item {{ (request()->is('admin*')) ? 'active' : '' }}">
        <a href="{{ route('admin') }}" class="nav-link">
            <i class="fas fa-fw fa-cog"></i>
            <span>User Admin</span>
        </a>
    </li> --}}
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
