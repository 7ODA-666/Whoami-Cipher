@extends('layouts.master')

@section('title', 'Playfair Cipher - About | CipherViz')
@section('page-title', 'Playfair Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('playfair.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('playfair.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('playfair.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-gray-800 border border-gray-700 rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-th text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-gray-100">Playfair Cipher</h2>
    </div>

    <div class="prose prose-invert max-w-none">
        <p class="text-gray-300 text-lg leading-relaxed mb-6">
            The Playfair Cipher is a digraph substitution cipher that encrypts pairs of letters (digraphs) instead of single letters. It uses a 5×5 matrix constructed from a keyword to perform the encryption.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">How It Works</h3>
        <p class="text-gray-300 mb-4">
            The cipher uses a 5×5 grid filled with a keyword (with J and I combined). The plaintext is divided into pairs of letters, and each pair is encrypted based on its position in the grid:
        </p>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li>If both letters are in the same row: shift each letter to the right (wrapping around)</li>
            <li>If both letters are in the same column: shift each letter down (wrapping around)</li>
            <li>Otherwise: form a rectangle and swap the letters at the opposite corners</li>
        </ul>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">History</h3>
        <p class="text-gray-300 mb-4">
            Invented by Charles Wheatstone in 1854, it was named after Lord Playfair who promoted its use. The Playfair Cipher was used by British forces in the Boer War and World War I.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Security</h3>
        <p class="text-gray-300 mb-4">
            <strong class="text-gray-200">Algorithm Type:</strong> Substitution Cipher (Digraph)
        </p>
        <p class="text-gray-300 mb-4">
            The Playfair Cipher is more secure than simple substitution ciphers because it encrypts pairs of letters, making frequency analysis more difficult. However, it can still be broken with sufficient ciphertext using known-plaintext attacks or frequency analysis of digraphs.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li><strong>Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong>Space Complexity:</strong> O(1) for the 5×5 matrix</li>
            <li><strong>Key Space:</strong> Depends on keyword length</li>
        </ul>
    </div>
</div>
@endsection

