@extends('layouts.master')

@section('title', 'About - Caesar Cipher')
@section('page-title', 'Caesar Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('caesar.encryption') }}"
       class="tab px-6 ml-2 mt-2 mb-2 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border">
        Encryption
    </a>
    <a href="{{ route('caesar.decryption') }}"
       class="tab px-6 mt-2 mb-2 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border">
        Decryption
    </a>
    <a href="{{ route('caesar.about') }}"
       class="tab px-6 mt-2 mb-2 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-xl p-6 lg:p-8 shadow-xl">
    <!-- Top Two-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start mb-8 lg:mb-12">
        <!-- Left Side - Title & Introduction (~50% width) -->
        <div>
            <div class="flex items-center gap-4 mb-6">
                <i class="fas fa-key text-4xl text-blue-400"></i>
                <h2 class="text-3xl font-bold text-light-text dark:text-dark-text">Caesar Cipher</h2>
            </div>
            <p class="text-light-text-secondary dark:text-dark-text-secondary text-lg leading-relaxed mb-4">
                The Caesar Cipher is one of the simplest and most widely known encryption techniques. It is a type of substitution cipher in which each letter in the plaintext is shifted a certain number of places down the alphabet.
            </p>
            <p class="text-light-text-secondary dark:text-dark-text-secondary leading-relaxed">
                Named after Julius Caesar, who used it in his private correspondence, this cipher is a classic example of symmetric encryption where the same key is used for both encryption and decryption.
            </p>
        </div>

        <!-- Right Side - YouTube Video (~50% width) -->
        <div>
            <x-youtube-video
                url="https://youtu.be/YB41BoVlLVU?si=gc4R-oNJdNJCiJ8F"
                title="Understanding the Caesar Cipher" />
        </div>
    </div>

    <!-- Continued Content - Full Width -->
    <div class="prose max-w-none">
        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mb-4">How It Works</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The encryption process involves shifting each letter of the plaintext by a fixed number of positions in the alphabet. For example, with a shift of 3:
        </p>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li>A becomes D</li>
            <li>B becomes E</li>
            <li>C becomes F</li>
            <li>... and so on</li>
        </ul>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">History</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            Named after Julius Caesar, who reportedly used it to communicate with his generals, the Caesar Cipher is one of the oldest known encryption methods. It dates back to around 100 BC.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Security</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            <strong class="text-light-text dark:text-dark-text">Algorithm Type:</strong> Substitution Cipher
        </p>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The Caesar Cipher is extremely vulnerable to cryptanalysis. With only 25 possible keys (shifts 1-25), it can be easily broken by brute force or frequency analysis. It is not suitable for secure communications but serves as an excellent educational tool for understanding basic cryptography concepts.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li><strong class="text-light-text dark:text-dark-text">Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong class="text-light-text dark:text-dark-text">Space Complexity:</strong> O(n)</li>
            <li><strong class="text-light-text dark:text-dark-text">Key Space:</strong> 25 possible keys</li>
        </ul>
    </div>
</div>
@endsection

