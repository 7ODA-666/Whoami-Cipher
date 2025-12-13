@extends('layouts.master')

@section('title', 'Row-Column Transposition - About | CipherViz')
@section('page-title', 'Row-Column Transposition Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('row-column-transposition.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('row-column-transposition.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('row-column-transposition.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-gray-800 border border-gray-700 rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-columns text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-gray-100">Row-Column Transposition Cipher</h2>
    </div>

    <div class="prose prose-invert max-w-none">
        <p class="text-gray-300 text-lg leading-relaxed mb-6">
            The Row-Column Transposition Cipher is a transposition cipher that writes the plaintext row by row into a grid and then reads it column by column according to a keyword's alphabetical order.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">How It Works</h3>
        <p class="text-gray-300 mb-4">
            The encryption process involves:
        </p>
        <ol class="list-decimal list-inside text-gray-300 mb-6 space-y-2">
            <li>Writing the plaintext row by row into a grid with width equal to the keyword length</li>
            <li>Determining the column order based on the alphabetical order of the keyword letters</li>
            <li>Reading the grid column by column in the determined order to produce the ciphertext</li>
        </ol>
        <p class="text-gray-300 mb-4">
            For decryption, the process is reversed: the ciphertext is written column by column and read row by row.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">History</h3>
        <p class="text-gray-300 mb-4">
            Row-Column Transposition has been used in various forms throughout history. It was used by military forces and is a common example in cryptography education.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Security</h3>
        <p class="text-gray-300 mb-4">
            <strong class="text-gray-200">Algorithm Type:</strong> Transposition Cipher
        </p>
        <p class="text-gray-300 mb-4">
            While more secure than simple transposition ciphers, Row-Column Transposition can be broken through cryptanalysis, especially if the keyword is short or if patterns in the plaintext are known. The security depends on the keyword length and complexity.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li><strong>Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong>Space Complexity:</strong> O(n) for the grid</li>
            <li><strong>Key Space:</strong> Depends on keyword length and alphabet size</li>
        </ul>
    </div>
</div>
@endsection

