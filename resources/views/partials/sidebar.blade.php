<aside class="fixed left-0 top-0 h-full w-64 bg-white border-r shadow-lg">
    <!-- Logo / Header -->
    <div class="flex items-center px-6 py-4 border-b">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-lg font-bold text-gray-800">
            <i class="bi bi-house-door-fill text-primary"></i>
            <span>KAS RT</span>
        </a>
    </div>

    <!-- Menu -->
    <nav class="mt-4 px-4">
        <ul class="space-y-2">
            <!-- DASHBOARD -->
            <li>
                <a href="{{ route('dashboard') }}" 
                    class="flex items-center gap-2 px-4 py-2 rounded-md 
                        text-gray-700 hover:bg-gray-100 
                        {{ request()->routeIs('dashboard') ? 'bg-blue-100' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="my-3 border-gray-300">

            <!-- MENU KEUANGAN -->
            <li class="text-xs font-semibold text-gray-700 px-4 mt-2">KEUANGAN</li>
            <li>
                <a href="{{ route('finance.index') }}" 
                    class="flex items-center gap-2 px-4 py-2 rounded-md 
                        text-gray-700 hover:bg-gray-100 
                        {{ request()->routeIs('finance.index') ? 'bg-blue-100' : '' }}">
                    <i class="bi bi-cash-stack text-success"></i>
                    <span>Keuangan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('finance.report.generate') }}" 
                    class="flex items-center gap-2 px-4 py-2 rounded-md 
                        text-gray-700 hover:bg-gray-100 
                        {{ request()->routeIs('finance.report.generate') ? 'bg-blue-100' : '' }}">
                    <i class="bi bi-printer-fill text-danger"></i>
                    <span>Cetak Laporan</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="my-3 border-gray-300">

            <!-- MENU MANAJEMEN -->
            <li class="text-xs font-semibold text-gray-700 px-4 mt-2">MANAJEMEN</li>
            <li>
                <a href="{{ route('categories.index') }}" 
                    class="flex items-center gap-2 px-4 py-2 rounded-md 
                        text-gray-700 hover:bg-gray-100 
                        {{ request()->routeIs('categories.index') ? 'bg-blue-100' : '' }}">
                    <i class="bi bi-folder text-warning"></i>
                    <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="{{ route('rt.index') }}" 
                    class="flex items-center gap-2 px-4 py-2 rounded-md 
                        text-gray-700 hover:bg-gray-100 
                        {{ request()->routeIs('rt.index') ? 'bg-blue-100' : '' }}">
                    <i class="bi bi-geo-alt text-primary"></i>
                    <span>RT</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin-rt.index') }}" 
                    class="flex items-center gap-2 px-4 py-2 rounded-md 
                        text-gray-700 hover:bg-gray-100 
                        {{ request()->routeIs('admin-rt.index') ? 'bg-blue-100' : '' }}">
                    <i class="bi bi-person-badge text-secondary"></i>
                    <span>Akun Admin RT</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="my-3 border-gray-300">

            <!-- MENU NOTIFIKASI -->
            <li class="text-xs font-semibold text-gray-700 px-4 mt-2">NOTIFIKASI</li>
            <li>
                <a href="{{ route('contact_notifications.index') }}" 
                    class="flex items-center gap-2 px-4 py-2 rounded-md 
                        text-gray-700 hover:bg-gray-100 
                        {{ request()->routeIs('contact_notifications.*') ? 'bg-blue-100' : '' }}">
                    <i class="bi bi-bell-fill text-info"></i>
                    <span>Data User</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
