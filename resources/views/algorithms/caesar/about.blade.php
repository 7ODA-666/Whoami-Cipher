@extends('layouts.master')

@section('title', 'Caesar Cipher - About | CipherViz')
@section('page-title', 'Caesar Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('caesar.encryption') }}"
       class="tab px-6 ml-2 mt-2 mb-2 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600">
        Encryption
    </a>
    <a href="{{ route('caesar.decryption') }}"
       class="tab px-6 mt-2 mb-2 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600">
        Decryption
    </a>
    <a href="{{ route('caesar.about') }}"
       class="tab px-6 mt-2 mb-2 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-gray-800 border border-gray-700 rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-key text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-gray-100">Caesar Cipher</h2>
    </div>

    <div class="prose prose-invert max-w-none">
        <p class="text-gray-300 text-lg leading-relaxed mb-6">
            The Caesar Cipher is one of the simplest and most widely known encryption techniques. It is a type of substitution cipher in which each letter in the plaintext is shifted a certain number of places down the alphabet.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">How It Works</h3>
        <p class="text-gray-300 mb-4">
            The encryption process involves shifting each letter of the plaintext by a fixed number of positions in the alphabet. For example, with a shift of 3:
        </p>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li>A becomes D</li>
            <li>B becomes E</li>
            <li>C becomes F</li>
            <li>... and so on</li>
        </ul>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">History</h3>
        <p class="text-gray-300 mb-4">
            Named after Julius Caesar, who reportedly used it to communicate with his generals, the Caesar Cipher is one of the oldest known encryption methods. It dates back to around 100 BC.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Security</h3>
        <p class="text-gray-300 mb-4">
            <strong class="text-gray-200">Algorithm Type:</strong> Substitution Cipher
        </p>
        <p class="text-gray-300 mb-4">
            The Caesar Cipher is extremely vulnerable to cryptanalysis. With only 25 possible keys (shifts 1-25), it can be easily broken by brute force or frequency analysis. It is not suitable for secure communications but serves as an excellent educational tool for understanding basic cryptography concepts.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li><strong>Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong>Space Complexity:</strong> O(n)</li>
            <li><strong>Key Space:</strong> 25 possible keys</li>
        </ul>
    </div>
</div>
@endsection

