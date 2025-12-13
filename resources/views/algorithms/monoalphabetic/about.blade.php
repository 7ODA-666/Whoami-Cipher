@extends('layouts.master')

@section('title', 'Monoalphabetic Cipher - About | CipherViz')
@section('page-title', 'Monoalphabetic Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('monoalphabetic.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('monoalphabetic.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('monoalphabetic.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-gray-800 border border-gray-700 rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-exchange-alt text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-gray-100">Monoalphabetic Cipher</h2>
    </div>

    <div class="prose prose-invert max-w-none">
        <p class="text-gray-300 text-lg leading-relaxed mb-6">
            A Monoalphabetic Cipher is a substitution cipher where each letter of the plaintext is replaced by a corresponding letter from a fixed substitution alphabet. Unlike the Caesar Cipher, the substitution alphabet can be any permutation of the 26 letters.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">How It Works</h3>
        <p class="text-gray-300 mb-4">
            The cipher uses a fixed mapping between the standard alphabet and a substitution alphabet. For example:
        </p>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li>Standard: A B C D E F G H I J K L M N O P Q R S T U V W X Y Z</li>
            <li>Substitution: Z Y X W V U T S R Q P O N M L K J I H G F E D C B A</li>
            <li>Each letter is replaced by its corresponding letter in the substitution alphabet</li>
        </ul>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">History</h3>
        <p class="text-gray-300 mb-4">
            Monoalphabetic ciphers have been used for centuries. They are more secure than simple shift ciphers like Caesar, but still vulnerable to frequency analysis since each letter always maps to the same substitute.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Security</h3>
        <p class="text-gray-300 mb-4">
            <strong class="text-gray-200">Algorithm Type:</strong> Substitution Cipher
        </p>
        <p class="text-gray-300 mb-4">
            While more secure than the Caesar Cipher (26! possible keys instead of 25), monoalphabetic ciphers are still vulnerable to frequency analysis. Common letters in English (E, T, A, O, I, N) can be identified by their frequency, making the cipher breakable with sufficient ciphertext.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li><strong>Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong>Space Complexity:</strong> O(1) for the substitution table</li>
            <li><strong>Key Space:</strong> 26! (approximately 4 × 10²⁶ possible keys)</li>
        </ul>
    </div>
</div>
@endsection

