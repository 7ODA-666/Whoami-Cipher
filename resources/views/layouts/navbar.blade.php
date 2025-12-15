<!-- Navbar -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 lg:mb-8 pb-4 border-b-2 border-light-border dark:border-dark-border">
    <div class="flex items-center gap-3">
        <!-- Mobile Hamburger Menu - Always visible -->
        <button id="mobile-sidebar-toggle" class="lg:hidden flex items-center justify-center w-10 h-10 bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-light-text dark:hover:text-dark-text transition-all duration-200 shadow-sm hover:shadow-md" aria-label="Toggle sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-blue-500 via-purple-500 to-orange-500 bg-clip-text text-transparent">
            @yield('page-title', 'Whoami Cipher')
        </h1>
    </div>
    <div class="flex items-center gap-2">
        <a href="https://github.com/7ODA-666/Whoami-Cipher" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-10 h-10 bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-light-text dark:hover:text-dark-text transition-all duration-200 shadow-sm hover:shadow-md" aria-label="Visit GitHub repository">
            <i class="fab fa-github text-xl"></i>
        </a>
        <button id="theme-toggle" class="flex items-center justify-center w-10 h-10 bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-light-text dark:hover:text-dark-text transition-all duration-200 shadow-sm hover:shadow-md" aria-label="Toggle theme">
            <i class="fas fa-moon text-xl" id="theme-icon"></i>
        </button>
    </div>
</div>

