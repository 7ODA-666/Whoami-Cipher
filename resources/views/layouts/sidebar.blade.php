<!-- Sidebar -->
<aside id="sidebar" class="fixed left-0 top-0 h-screen w-72 bg-gray-800 border-r border-gray-700 sidebar-transition z-50 overflow-y-auto overflow-x-hidden">
    <div class="p-6 border-b border-gray-700 flex items-center justify-between">
        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent sidebar-text-transition">
            CipherViz
        </h1>
        <button id="toggle-sidebar" class="text-gray-300 hover:text-white hover:bg-gray-700 p-2 rounded-lg transition-colors" aria-label="Toggle sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
    
    <nav class="py-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-700 transition-colors {{ request()->routeIs('home') ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600' : '' }}">
            <i class="fas fa-home text-xl"></i>
            <span class="sidebar-text-transition">Home</span>
        </a>
        
        <div class="mt-6">
            <div class="px-6 py-2 text-xs font-semibold uppercase tracking-wider text-gray-400 sidebar-text-transition">
                Substitution Techniques
            </div>
            
            @php
                $currentRoute = request()->route()->getName();
                $isCaesar = strpos($currentRoute, 'caesar.') === 0;
                $isMonoalphabetic = strpos($currentRoute, 'monoalphabetic.') === 0;
                $isPolyalphabetic = strpos($currentRoute, 'polyalphabetic.') === 0;
                $isOneTimePad = strpos($currentRoute, 'one-time-pad.') === 0;
                $isPlayfair = strpos($currentRoute, 'playfair.') === 0;
                $isHill = strpos($currentRoute, 'hill.') === 0;
            @endphp
            
            <a href="{{ route('caesar.encryption') }}" 
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-700 transition-colors {{ $isCaesar ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600' : '' }}">
                <i class="fas fa-key text-xl"></i>
                <span class="sidebar-text-transition">Caesar Cipher</span>
            </a>
            
            <a href="{{ route('monoalphabetic.encryption') }}" 
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-700 transition-colors {{ $isMonoalphabetic ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600' : '' }}">
                <i class="fas fa-exchange-alt text-xl"></i>
                <span class="sidebar-text-transition">Monoalphabetic</span>
            </a>
            
            <a href="{{ route('polyalphabetic.encryption') }}" 
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-700 transition-colors {{ $isPolyalphabetic ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600' : '' }}">
                <i class="fas fa-random text-xl"></i>
                <span class="sidebar-text-transition">Polyalphabetic</span>
            </a>
            
            <a href="{{ route('one-time-pad.encryption') }}" 
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-700 transition-colors {{ $isOneTimePad ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600' : '' }}">
                <i class="fas fa-shield-alt text-xl"></i>
                <span class="sidebar-text-transition">One-Time Pad</span>
            </a>
            
            <a href="{{ route('playfair.encryption') }}" 
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-700 transition-colors {{ $isPlayfair ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600' : '' }}">
                <i class="fas fa-th text-xl"></i>
                <span class="sidebar-text-transition">Playfair Cipher</span>
            </a>
            
            <a href="{{ route('hill.encryption') }}" 
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-700 transition-colors {{ $isHill ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600' : '' }}">
                <i class="fas fa-table text-xl"></i>
                <span class="sidebar-text-transition">Hill Cipher</span>
            </a>
        </div>
        
        <div class="mt-6">
            <div class="px-6 py-2 text-xs font-semibold uppercase tracking-wider text-gray-400 sidebar-text-transition">
                Transposition Techniques
            </div>
            
            @php
                $isRailFence = strpos($currentRoute, 'rail-fence.') === 0;
                $isRowColumn = strpos($currentRoute, 'row-column-transposition.') === 0;
            @endphp
            
            <a href="{{ route('rail-fence.encryption') }}" 
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-700 transition-colors {{ $isRailFence ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600' : '' }}">
                <i class="fas fa-wave-square text-xl"></i>
                <span class="sidebar-text-transition">Rail Fence</span>
            </a>
            
            <a href="{{ route('row-column-transposition.encryption') }}" 
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-700 transition-colors {{ $isRowColumn ? 'text-white bg-gradient-to-r from-blue-600 to-purple-600' : '' }}">
                <i class="fas fa-columns text-xl"></i>
                <span class="sidebar-text-transition">Row-Column Transposition</span>
            </a>
        </div>
    </nav>
</aside>

