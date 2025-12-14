@extends('layouts.master')

@section('title', 'Playfair Cipher - About | CipherViz')
@section('page-title', 'Playfair Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('playfair.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('playfair.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('playfair.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-th text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-light-text dark:text-dark-text">Playfair Cipher</h2>
    </div>

    <div class="prose max-w-none">
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-lg leading-relaxed mb-6">
            The Playfair Cipher is a digraph substitution cipher that encrypts pairs of letters (digraphs) using a 5×5 matrix of letters constructed from a keyword. It was the first practical digraph substitution cipher.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">How It Works</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The cipher uses a 5×5 grid filled with the letters of a keyword (removing duplicates) followed by the remaining letters of the alphabet. The letter J is usually omitted or combined with I.
        </p>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li>Create a 5×5 matrix with keyword + remaining alphabet</li>
            <li>Split plaintext into pairs of letters (digraphs)</li>
            <li>Apply transformation rules based on positions in the matrix</li>
            <li>Different rules for same row, same column, or rectangle</li>
        </ul>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">History</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            Invented by Charles Wheatstone in 1854 but popularized by Lord Playfair. It was used extensively by British forces during the Boer War and World War I.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Security</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            <strong class="text-light-text dark:text-dark-text">Algorithm Type:</strong> Digraph Substitution Cipher
        </p>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The Playfair cipher is much more secure than simple substitution ciphers because it encrypts pairs of letters rather than single letters. This makes frequency analysis much more difficult, though it can still be broken with sufficient ciphertext and modern techniques.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li><strong class="text-light-text dark:text-dark-text">Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong class="text-light-text dark:text-dark-text">Space Complexity:</strong> O(1) for the 5×5 matrix</li>
            <li><strong class="text-light-text dark:text-dark-text">Key Space:</strong> 25! possible matrix arrangements</li>
        </ul>
    </div>
</div>
@endsection

