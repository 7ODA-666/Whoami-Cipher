<!-- Navbar -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 lg:mb-8 pb-4 border-b-2 border-light-border dark:border-dark-border">
    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-blue-500 via-purple-500 to-orange-500 bg-clip-text text-transparent">
        @yield('page-title', 'Whoami Cipher')
    </h1>
    <button id="theme-toggle" class="flex items-center justify-center w-10 h-10 bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-light-text dark:hover:text-dark-text transition-all duration-200 shadow-sm hover:shadow-md" aria-label="Toggle theme">
        <i class="fas fa-moon text-xl" id="theme-icon"></i>
    </button>
</div>

