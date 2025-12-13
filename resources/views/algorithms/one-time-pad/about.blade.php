@extends('layouts.master')

@section('title', 'One-Time Pad - About | CipherViz')
@section('page-title', 'One-Time Pad (OTP)')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('one-time-pad.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('one-time-pad.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600 mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('one-time-pad.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-gray-800 border border-gray-700 rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-shield-alt text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-gray-100">One-Time Pad (OTP)</h2>
    </div>

    <div class="prose prose-invert max-w-none">
        <p class="text-gray-300 text-lg leading-relaxed mb-6">
            The One-Time Pad is the only theoretically unbreakable encryption method when used correctly. It requires a random key that is at least as long as the message and is used only once.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">How It Works</h3>
        <p class="text-gray-300 mb-4">
            Each character of the plaintext is combined with the corresponding character of a random key using modular arithmetic. The key must be:
        </p>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li>Truly random (not pseudorandom)</li>
            <li>At least as long as the plaintext</li>
            <li>Used only once (never reused)</li>
            <li>Kept completely secret</li>
        </ul>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">History</h3>
        <p class="text-gray-300 mb-4">
            The One-Time Pad was invented in 1917 by Gilbert Vernam and Joseph Mauborgne. It was used extensively during World War II, most notably in the "hotline" between Washington and Moscow during the Cold War.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Security</h3>
        <p class="text-gray-300 mb-4">
            <strong class="text-gray-200">Algorithm Type:</strong> Substitution Cipher
        </p>
        <p class="text-gray-300 mb-4">
            When properly implemented, the One-Time Pad provides perfect secrecy (information-theoretic security). This means that even with unlimited computational resources, an attacker cannot determine the plaintext from the ciphertext alone. However, practical challenges include key distribution and generation of truly random keys.
        </p>

        <h3 class="text-2xl font-bold text-gray-100 mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-gray-300 mb-6 space-y-2">
            <li><strong>Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong>Space Complexity:</strong> O(n) for the key</li>
            <li><strong>Key Space:</strong> Infinite (truly random keys)</li>
            <li><strong>Security:</strong> Information-theoretically secure</li>
        </ul>
    </div>
</div>
@endsection

