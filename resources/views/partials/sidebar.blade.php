<aside class="fixed left-0 top-0 h-full w-64 bg-white border-r shadow-lg">
    <!-- Logo / Header -->
    <div class="flex items-center px-6 py-4 border-b">
        @if(auth()->user()->role === 'super_admin')
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-lg font-bold text-gray-800">
        @else
            <a href="{{ route('dashboardRT') }}" class="flex items-center gap-2 text-lg font-bold text-gray-800">
        @endif
            <i class="bi bi-house-door-fill text-primary"></i>
            <span>KAS RT</span>
        </a>
    </div>

    <!-- Menu -->
    <nav class="mt-4 px-4">
        <ul class="space-y-2">
            <!-- DASHBOARD -->
            <li>
                @if(auth()->user()->role === 'super_admin')
                    <a href="{{ route('dashboard') }}"
                @else
                    <a href="{{ route('dashboardRT') }}" 
                @endif
                    class="flex items-center gap-2 px-4 py-2 rounded-md 
                        text-gray-700 hover:bg-gray-100 
                        {{ request()->routeIs(auth()->user()->role === 'super_admin' ? 'dashboard' : 'dashboardRT') ? 'bg-blue-100' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="my-3 border-gray-300">

            @if(auth()->user()->role === 'super_admin')
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
                    <a href="{{ route('report.index') }}" 
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

                <!-- <hr class="my-3 border-gray-300">
                <li class="text-xs font-semibold text-gray-700 px-4 mt-2">NOTIFIKASI</li>
                <li>
                    <a href="{{ route('contact_notifications.index') }}" 
                        class="flex items-center gap-2 px-4 py-2 rounded-md 
                            text-gray-700 hover:bg-gray-100 
                            {{ request()->routeIs('contact_notifications.*') ? 'bg-blue-100' : '' }}">
                        <i class="bi bi-bell-fill text-info"></i>
                        <span>Data User</span>
                    </a>
                </li> -->

                <!-- Divider -->
                <hr class="my-3 border-gray-300">

                <!-- MENU LAINNYA -->
                <li class="text-xs font-semibold text-gray-700 px-4 mt-2">LAINNYA</li>

                <!-- MENU TRASH BIN -->
                <li>
                    <a href="{{ route('superadmin.trashbin.index') }}" 
                        class="flex items-center gap-2 px-4 py-2 rounded-md 
                            text-gray-700 hover:bg-gray-100 
                            {{ request()->routeIs('trashbin.index') ? 'bg-blue-100' : '' }}">
                        <i class="bi bi-trash text-danger"></i>
                        <span>Sampah</span>
                    </a>
                </li>

            @else
                <!-- MENU ADMIN RT -->
                <li class="text-xs font-semibold text-gray-700 px-4 mt-2">KEUANGAN</li>
                <li>
                    <a href="{{ route('incomes.index') }}" 
                        class="flex items-center gap-2 px-4 py-2 rounded-md 
                            text-gray-700 hover:bg-gray-100">
                        <i class="bi bi-cash-stack text-success"></i>
                        <span>Pemasukan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('expenses.index') }}" 
                        class="flex items-center gap-2 px-4 py-2 rounded-md 
                            text-gray-700 hover:bg-gray-100">
                        <i class="bi bi-cart-dash text-danger"></i>
                        <span>Pengeluaran</span>
                    </a>
                </li>
                <li>
                    <a href="#" 
                        class="flex items-center gap-2 px-4 py-2 rounded-md 
                            text-gray-700 hover:bg-gray-100">
                        <i class="bi bi-file-earmark-bar-graph text-primary"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                </li>

                <!-- Divider -->
                <hr class="my-3 border-gray-300">

                <!-- MENU KEGIATAN -->
                <li class="text-xs font-semibold text-gray-700 px-4 mt-2">KEGIATAN</li>
                <li>
                    <a href="#" 
                        class="flex items-center gap-2 px-4 py-2 rounded-md 
                            text-gray-700 hover:bg-gray-100">
                        <i class="bi bi-calendar-event text-warning"></i>
                        <span>Log Kegiatan</span>
                    </a>
                </li>

                <!-- MENU TRASH BIN -->
                <li>
                    <a href="{{ route('trash-bin.index') }}" 
                        class="flex items-center gap-2 px-4 py-2 rounded-md 
                            text-gray-700 hover:bg-gray-100 
                            {{ request()->routeIs('trashbin.index') ? 'bg-blue-100' : '' }}">
                        <i class="bi bi-trash text-danger"></i>
                        <span>Sampah</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</aside>
