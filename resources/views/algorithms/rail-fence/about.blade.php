@extends('layouts.master')

@section('title', 'Rail Fence Cipher - About | CipherViz')
@section('page-title', 'Rail Fence Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('rail-fence.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('rail-fence.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('rail-fence.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-gray-800 border border-gray-700 rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-wave-square text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-gray-100">Rail Fence Cipher</h2>
    </div>

    <div class="prose prose-invert max-w-none">
        <p class="text-gray-300 text-lg leading-relaxed mb-6">
            The Rail Fence Cipher is a transposition cipher that writes the plaintext in a zigzag pattern along multiple "rails" and then reads it row by row to produce the ciphertext.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">How It Works</h3>
        <p class="text-gray-300 mb-4">
            The plaintext is written diagonally down and up along a series of "rails" (horizontal lines). For example, with 3 rails, the word "HELLO" would be written as:
        </p>
        <pre class="bg-gray-900 p-4 rounded-lg text-gray-100 font-mono mb-4">H . . . O
E . L . .
L . . . .</pre>
        <p class="text-gray-300 mb-4">
            Reading row by row gives: H O E L L
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">History</h3>
        <p class="text-gray-300 mb-4">
            The Rail Fence Cipher is one of the simplest transposition ciphers. It has been used historically but is not known to have been used by any major military or government organization.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Security</h3>
        <p class="text-gray-300 mb-4">
            <strong class="text-gray-200">Algorithm Type:</strong> Transposition Cipher
        </p>
        <p class="text-gray-300 mb-4">
            The Rail Fence Cipher is very weak and can be easily broken by trying all possible numbers of rails. It provides no real security and is mainly used for educational purposes.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li><strong>Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong>Space Complexity:</strong> O(n)</li>
            <li><strong>Key Space:</strong> Limited (typically 2-10 rails)</li>
        </ul>
    </div>
</div>
@endsection

