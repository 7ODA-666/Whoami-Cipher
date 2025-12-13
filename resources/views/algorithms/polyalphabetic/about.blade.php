@extends('layouts.master')

@section('title', 'Polyalphabetic Cipher - About | CipherViz')
@section('page-title', 'Polyalphabetic (Vigenère) Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('polyalphabetic.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('polyalphabetic.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('polyalphabetic.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-gray-800 border border-gray-700 rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-random text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-gray-100">Polyalphabetic (Vigenère) Cipher</h2>
    </div>

    <div class="prose prose-invert max-w-none">
        <p class="text-gray-300 text-lg leading-relaxed mb-6">
            The Vigenère Cipher is a method of encrypting alphabetic text by using a series of interwoven Caesar ciphers based on the letters of a keyword. It is a form of polyalphabetic substitution.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">How It Works</h3>
        <p class="text-gray-300 mb-4">
            The cipher uses a keyword that is repeated to match the length of the plaintext. Each letter of the plaintext is shifted by the corresponding letter of the keyword. For example, with keyword "KEY":
        </p>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li>Plaintext: H E L L O</li>
            <li>Keyword: K E Y K E (repeated)</li>
            <li>Each letter is shifted by the keyword letter's position in the alphabet</li>
        </ul>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">History</h3>
        <p class="text-gray-300 mb-4">
            Named after Blaise de Vigenère, though it was actually invented by Giovan Battista Bellaso in 1553. The Vigenère Cipher was considered unbreakable for over 300 years until Charles Babbage and Friedrich Kasiski independently developed methods to break it in the 19th century.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Security</h3>
        <p class="text-gray-300 mb-4">
            <strong class="text-gray-200">Algorithm Type:</strong> Substitution Cipher (Polyalphabetic)
        </p>
        <p class="text-gray-300 mb-4">
            While more secure than monoalphabetic ciphers, the Vigenère Cipher can be broken using frequency analysis and the Kasiski examination method, especially if the keyword is short or repeated.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li><strong>Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong>Space Complexity:</strong> O(n)</li>
            <li><strong>Key Space:</strong> Depends on keyword length and alphabet size</li>
        </ul>
    </div>
</div>
@endsection

