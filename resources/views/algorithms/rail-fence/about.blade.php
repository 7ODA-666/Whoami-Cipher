@extends('layouts.master')

@section('title', 'Rail Fence Cipher - About | CipherViz')
@section('page-title', 'Rail Fence Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('rail-fence.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('rail-fence.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('rail-fence.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-wave-square text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-light-text dark:text-dark-text">Rail Fence Cipher</h2>
    </div>

    <div class="prose max-w-none">
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-lg leading-relaxed mb-6">
            The Rail Fence Cipher is a transposition cipher that writes the plaintext in a zigzag pattern along multiple "rails" and then reads it row by row to create the ciphertext. It's one of the simplest transposition ciphers.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">How It Works</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The cipher arranges the plaintext in a zigzag pattern across multiple horizontal lines (rails), then reads the letters row by row:
        </p>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li>Write the plaintext in a zigzag pattern across the specified number of rails</li>
            <li>Start at the top rail, move diagonally down to the bottom rail</li>
            <li>Then move diagonally up back to the top rail, repeating the pattern</li>
            <li>Read the letters row by row to form the ciphertext</li>
        </ul>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">History</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The Rail Fence Cipher has been used since ancient times. It's a simple form of transposition cipher that doesn't require complex mathematical operations, making it accessible for manual encryption and decryption.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Security</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            <strong class="text-light-text dark:text-dark-text">Algorithm Type:</strong> Transposition Cipher
        </p>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The Rail Fence Cipher provides very limited security. With a small number of possible rail configurations, it can be easily broken by trying all possible numbers of rails. It's mainly of educational value rather than practical security use.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li><strong class="text-light-text dark:text-dark-text">Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong class="text-light-text dark:text-dark-text">Space Complexity:</strong> O(n) for storing the rail pattern</li>
            <li><strong class="text-light-text dark:text-dark-text">Key Space:</strong> Very limited (typically 2-10 possible rail values)</li>
        </ul>
    </div>
</div>
@endsection

