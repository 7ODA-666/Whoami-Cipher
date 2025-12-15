@extends('layouts.master')

@section('title', 'About - Row-Column Transposition')
@section('page-title', 'Row-Column Transposition Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('row-column-transposition.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('row-column-transposition.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('row-column-transposition.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
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
                <i class="fas fa-columns text-4xl text-blue-400"></i>
                <h2 class="text-3xl font-bold text-light-text dark:text-dark-text">Row-Column Transposition Cipher</h2>
            </div>
            <p class="text-light-text-secondary dark:text-dark-text-secondary text-lg leading-relaxed mb-4">
                The Row-Column Transposition Cipher is a transposition cipher that writes the plaintext into a grid row by row, then reads it column by column based on a keyword's alphabetical order. It's more secure than simple transposition methods.
            </p>
            <p class="text-light-text-secondary dark:text-dark-text-secondary leading-relaxed">
                Also known as Columnar Transposition, this cipher provides better security than the Rail Fence cipher by using a keyword to determine the column reading order.
            </p>
        </div>

        <!-- Right Side - YouTube Video (~50% width) -->
        <div>
            <x-youtube-video
                url="https://www.youtube.com/watch?v=XdUJQa6QQFQ"
                title="Columnar Transposition Cipher Tutorial" />
        </div>
    </div>

    <!-- Continued Content - Full Width -->
    <div class="prose max-w-none">
        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mb-4">How It Works</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The cipher arranges the plaintext in a rectangular grid and reorders columns based on a keyword:
        </p>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li>Write the plaintext in rows under the keyword columns</li>
            <li>Number the keyword letters in alphabetical order</li>
            <li>Read the columns in the order determined by the keyword</li>
            <li>Concatenate all column readings to form the ciphertext</li>
        </ul>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Example Process</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            For keyword "ZEBRA" (alphabetical order: A=1, B=2, E=3, R=4, Z=5), columns are read in order: 5th, 2nd, 3rd, 4th, 1st (Z-E-B-R-A → A-B-E-R-Z).
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">History</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            Row-Column Transposition has been used in various forms throughout history. It was popular during both World Wars as it could be performed manually while providing reasonable security for field communications.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Security</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            <strong class="text-light-text dark:text-dark-text">Algorithm Type:</strong> Columnar Transposition Cipher
        </p>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The Row-Column Transposition provides moderate security. It's more secure than simple transposition but can be broken with cryptanalysis techniques, especially if the keyword length is known or if multiple messages use the same key.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li><strong class="text-light-text dark:text-dark-text">Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong class="text-light-text dark:text-dark-text">Space Complexity:</strong> O(n) for storing the grid</li>
            <li><strong class="text-light-text dark:text-dark-text">Key Space:</strong> Factorial of keyword length (k!)</li>
        </ul>
    </div>
</div>
@endsection

