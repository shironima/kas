<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - KAS RT')</title>

    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->

    <!-- Gunakan Tailwind dari Vite -->
    @vite('resources/css/app.css')

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">

    @stack('styles')
</head>
<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="fixed lg:w-60 w-56 h-screen bg-white shadow-md z-20">
        @include('partials.sidebar')
    </aside>

    <!-- Konten Utama -->
    <div class="flex-1 lg:ml-60 ml-56">
        <!-- Navbar -->
        <header class="fixed top-0 w-full bg-white shadow-md px-6 py-4 z-10 lg:ml-60 ml-56">
            @include('partials.navbar')
        </header>

        <!-- Page Content -->
        <main class="p-6 mt-20 container mx-auto max-w-full overflow-auto">
            @yield('content')
        </main>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>

    <!-- Inisialisasi DataTables -->
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: { first: "Pertama", last: "Terakhir", next: "›", previous: "‹" },
                    zeroRecords: "Tidak ada data yang ditemukan",
                    infoEmpty: "Tidak ada data yang tersedia",
                    infoFiltered: "(disaring dari _MAX_ total data)"
                }
            });

            // Styling tambahan DataTables
            $(".dataTables_wrapper").addClass("mt-4");
            $(".dataTables_length").addClass("mb-4");
            $(".dataTables_filter input").addClass("rounded-lg p-2 border border-gray-300 focus:ring focus:ring-blue-300");
        });
    </script>

    @stack('scripts')

</body>
</html>
