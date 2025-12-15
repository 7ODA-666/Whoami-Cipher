@extends('layouts.master')

@section('title', 'About - Monoalphabetic Cipher')
@section('page-title', 'Monoalphabetic Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('monoalphabetic.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('monoalphabetic.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('monoalphabetic.about') }}"
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
                <i class="fas fa-exchange-alt text-4xl text-blue-400"></i>
                <h2 class="text-3xl font-bold text-light-text dark:text-dark-text">Monoalphabetic Cipher</h2>
            </div>
            <p class="text-light-text-secondary dark:text-dark-text-secondary text-lg leading-relaxed mb-4">
                A Monoalphabetic Cipher is a substitution cipher where each letter of the plaintext is replaced by a corresponding letter from a fixed substitution alphabet. Unlike the Caesar Cipher, the substitution alphabet can be any permutation of the 26 letters.
            </p>
            <p class="text-light-text-secondary dark:text-dark-text-secondary leading-relaxed">
                This cipher provides greater security than Caesar ciphers by using a random key alphabet, making frequency analysis more challenging but still possible with sufficient ciphertext.
            </p>
        </div>

        <!-- Right Side - YouTube Video (~50% width) -->
        <div>
            <x-youtube-video
                url="https://youtu.be/Tu_f31Xb-pg?si=lVr2sFxz788GmKH2"
                title="Monoalphabetic Substitution Cipher Explained" />
        </div>
    </div>

    <!-- Continued Content - Full Width -->
    <div class="prose max-w-none">
        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mb-4">How It Works</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The cipher uses a fixed mapping between the standard alphabet and a substitution alphabet. For example:
        </p>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li>Standard: A B C D E F G H I J K L M N O P Q R S T U V W X Y Z</li>
            <li>Substitution: Z Y X W V U T S R Q P O N M L K J I H G F E D C B A</li>
            <li>Each letter is replaced by its corresponding letter in the substitution alphabet</li>
        </ul>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">History</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            Monoalphabetic ciphers have been used for centuries. They are more secure than simple shift ciphers like Caesar, but still vulnerable to frequency analysis since each letter always maps to the same substitute.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Security</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            <strong class="text-light-text dark:text-dark-text">Algorithm Type:</strong> Substitution Cipher
        </p>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            While more secure than the Caesar Cipher (26! possible keys instead of 25), monoalphabetic ciphers are still vulnerable to frequency analysis. Common letters in English (E, T, A, O, I, N) can be identified by their frequency, making the cipher breakable with sufficient ciphertext.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li><strong class="text-light-text dark:text-dark-text">Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong class="text-light-text dark:text-dark-text">Space Complexity:</strong> O(1) for the substitution table</li>
            <li><strong class="text-light-text dark:text-dark-text">Key Space:</strong> 26! (approximately 4 × 10²⁶ possible keys)</li>
        </ul>
    </div>
</div>
@endsection

