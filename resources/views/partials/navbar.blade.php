<nav class="fixed top-0 left-64 right-0 h-16 flex justify-between items-center bg-white border-b shadow-md px-6 z-40">
    <div>
        <h6 class="font-semibold text-gray-800">Halo, {{ auth()->user()->name }}!</h6>
    </div>
    
    <ul class="flex items-center space-x-4">
        <li>
            <a href="{{ route('logout') }}" 
               class="flex items-center gap-2 text-gray-700 hover:text-gray-900"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </li>
    </ul>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</nav>
