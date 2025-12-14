@extends('layouts.master')

@section('title', 'Hill Cipher - About | CipherViz')
@section('page-title', 'Hill Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('hill.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('hill.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('hill.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-table text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-light-text dark:text-dark-text">Hill Cipher</h2>
    </div>

    <div class="prose max-w-none">
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-lg leading-relaxed mb-6">
            The Hill Cipher is a polygraphic substitution cipher based on linear algebra. It encrypts blocks of letters using matrix multiplication, making it more secure than simple substitution ciphers.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">How It Works</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The Hill Cipher uses matrix multiplication to encrypt blocks of text. Each block of letters is converted to numbers, multiplied by a key matrix, and the result is converted back to letters using modulo 26 arithmetic.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">History</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            Invented by Lester S. Hill in 1929, the Hill Cipher was one of the first polygraphic ciphers to be practical. It was used by the US military during World War II.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Security</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            <strong class="text-light-text dark:text-dark-text">Algorithm Type:</strong> Substitution Cipher (Polygraphic)
        </p>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The Hill Cipher is vulnerable to known-plaintext attacks if the key matrix is small. For a 2×2 matrix, only 4 pairs of plaintext-ciphertext are needed to break it. Larger matrices provide better security but are more computationally intensive.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li><strong class="text-light-text dark:text-dark-text">Time Complexity:</strong> O(n²) for matrix multiplication, O(n) overall where n is the text length</li>
            <li><strong class="text-light-text dark:text-dark-text">Space Complexity:</strong> O(n)</li>
            <li><strong class="text-light-text dark:text-dark-text">Key Space:</strong> Depends on matrix size (2×2 or 3×3)</li>
        </ul>
    </div>
</div>
@endsection

