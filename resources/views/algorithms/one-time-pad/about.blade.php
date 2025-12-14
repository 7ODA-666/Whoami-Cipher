@extends('layouts.master')

@section('title', 'One-Time Pad - About | CipherViz')
@section('page-title', 'One-Time Pad (OTP)')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('one-time-pad.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('one-time-pad.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('one-time-pad.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-shield-alt text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-light-text dark:text-dark-text">One-Time Pad (OTP)</h2>
    </div>

    <div class="prose max-w-none">
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-lg leading-relaxed mb-6">
            The One-Time Pad (OTP) is the only theoretically unbreakable cipher when used correctly. It requires a key that is at least as long as the plaintext, truly random, never reused, and kept completely secret.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">How It Works</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The OTP uses modular addition to combine each character of the plaintext with the corresponding character of the key:
        </p>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li>Each character is converted to a number (A=0, B=1, ..., Z=25)</li>
            <li>Encryption: (Plaintext + Key) mod 26</li>
            <li>Decryption: (Ciphertext - Key + 26) mod 26</li>
            <li>The key must be exactly the same length as the message</li>
        </ul>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Perfect Secrecy</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            When used properly, the One-Time Pad provides perfect secrecy. Every possible plaintext is equally likely for any given ciphertext, making cryptanalysis impossible without the key.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Security Requirements</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            <strong class="text-light-text dark:text-dark-text">Algorithm Type:</strong> Stream Cipher (Perfect Secrecy)
        </p>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            For perfect security, the OTP requires:
        </p>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li><strong class="text-light-text dark:text-dark-text">Random:</strong> The key must be truly random</li>
            <li><strong class="text-light-text dark:text-dark-text">Length:</strong> Key must be at least as long as the plaintext</li>
            <li><strong class="text-light-text dark:text-dark-text">Unique:</strong> Each key must be used only once</li>
            <li><strong class="text-light-text dark:text-dark-text">Secret:</strong> The key must be kept completely secret</li>
        </ul>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Practical Limitations</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            Despite its perfect security, the OTP has significant practical limitations: key distribution, key management, and the requirement for keys as long as the message make it impractical for most applications.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li><strong class="text-light-text dark:text-dark-text">Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong class="text-light-text dark:text-dark-text">Space Complexity:</strong> O(n) for the key</li>
            <li><strong class="text-light-text dark:text-dark-text">Security:</strong> Theoretically unbreakable (information-theoretic security)</li>
        </ul>
    </div>
</div>
@endsection
