<!-- Navbar -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 lg:mb-8 pb-4 border-b-2 border-gray-700">
    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-blue-500 via-purple-500 to-orange-500 bg-clip-text text-transparent">
        @yield('page-title', 'CipherViz')
    </h1>
    <button id="theme-toggle" class="flex items-center justify-center w-10 h-10 bg-gray-800 border border-gray-700 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors" aria-label="Toggle theme">
        <i class="fas fa-moon text-xl" id="theme-icon"></i>
    </button>
</div>

