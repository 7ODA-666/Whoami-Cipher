@extends('layouts.master')

@section('title', 'Polyalphabetic Cipher - About | CipherViz')
@section('page-title', 'Polyalphabetic (Vigenère) Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('polyalphabetic.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('polyalphabetic.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('polyalphabetic.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        About
    </a>
</div>

<!-- About Section -->
<div class="bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-xl p-6 lg:p-8 shadow-xl">
    <div class="flex items-center gap-4 mb-6">
        <i class="fas fa-random text-4xl text-blue-400"></i>
        <h2 class="text-3xl font-bold text-light-text dark:text-dark-text">Polyalphabetic (Vigenère) Cipher</h2>
    </div>

    <div class="prose max-w-none">
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-lg leading-relaxed mb-6">
            The Polyalphabetic Cipher, most famously implemented as the Vigenère Cipher, uses multiple substitution alphabets based on a keyword. This makes it much more secure than simple monoalphabetic ciphers.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">How It Works</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The cipher uses a keyword that is repeated to match the length of the plaintext. Each letter of the keyword determines which Caesar cipher shift to use for that position:
        </p>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li>Each letter in the keyword corresponds to a shift value (A=0, B=1, C=2, etc.)</li>
            <li>The keyword is repeated cyclically to match the plaintext length</li>
            <li>Each plaintext letter is shifted by the corresponding keyword letter value</li>
            <li>This creates multiple substitution alphabets within one message</li>
        </ul>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">History</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            Described by Giovan Battista Bellaso in 1553 and later misattributed to Blaise de Vigenère in the 19th century. It was called "le chiffre indéchiffrable" (the indecipherable cipher) for about 300 years until Friedrich Kasiski developed a method to break it in 1863.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Security</h3>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            <strong class="text-light-text dark:text-dark-text">Algorithm Type:</strong> Polyalphabetic Substitution Cipher
        </p>
        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-4">
            The Vigenère cipher is much more secure than monoalphabetic ciphers because it uses multiple substitution alphabets. However, it can be broken using frequency analysis techniques like the Kasiski examination or index of coincidence, especially with shorter keywords or longer texts.
        </p>

        <h3 class="text-2xl font-bold text-light-text dark:text-dark-text mt-8 mb-4">Complexity</h3>
        <ul class="list-disc list-inside text-light-text-secondary dark:text-dark-text-secondary mb-6 space-y-2">
            <li><strong class="text-light-text dark:text-dark-text">Time Complexity:</strong> O(n) where n is the length of the text</li>
            <li><strong class="text-light-text dark:text-dark-text">Space Complexity:</strong> O(k) where k is the length of the keyword</li>
            <li><strong class="text-light-text dark:text-dark-text">Security:</strong> Depends on keyword length and randomness</li>
        </ul>
    </div>
</div>
@endsection

