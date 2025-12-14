@extends('layouts.master')

@section('title', 'Home - Whoami')

@section('page-title', 'Whoami Cipher')

@section('content')
<div class="text-center py-16 px-8 bg-gradient-to-br from-blue-600 via-purple-600 to-orange-600 rounded-xl mb-12 text-white shadow-2xl">
    <h1 class="text-5xl font-bold mb-4">Welcome to Master Whoami Cipher</h1>
    <p class="text-xl opacity-90">Explore and learn about classical cryptography algorithms through interactive visualization</p>
</div>

<h2 class="text-2xl font-bold mb-4 bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent">Substitution Techniques</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
    <a href="{{ route('caesar.encryption') }}"
       class="block bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg p-6 hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-key text-2xl text-blue-400"></i>
            <h3 class="text-xl font-bold text-blue-400">Caesar Cipher</h3>
        </div>
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-sm">A simple substitution cipher where each letter is shifted by a fixed number of positions in the alphabet.</p>
    </a>

    <a href="{{ route('monoalphabetic.encryption') }}"
       class="block bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg p-6 hover:border-purple-500 hover:shadow-lg hover:shadow-purple-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-exchange-alt text-2xl text-purple-400"></i>
            <h3 class="text-xl font-bold text-purple-400">Monoalphabetic Cipher</h3>
        </div>
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-sm">A substitution cipher using a fixed permutation of the alphabet for the entire message.</p>
    </a>

    <a href="{{ route('polyalphabetic.encryption') }}"
       class="block bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg p-6 hover:border-orange-500 hover:shadow-lg hover:shadow-orange-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-random text-2xl text-orange-400"></i>
            <h3 class="text-xl font-bold text-orange-400">Polyalphabetic (Vigenère) Cipher</h3>
        </div>
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-sm">A cipher that uses multiple substitution alphabets based on a keyword, making it more secure than monoalphabetic ciphers.</p>
    </a>

    <a href="{{ route('one-time-pad.encryption') }}"
       class="block bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg p-6 hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-shield-alt text-2xl text-blue-400"></i>
            <h3 class="text-xl font-bold text-blue-400">One-Time Pad (OTP)</h3>
        </div>
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-sm">The only theoretically unbreakable cipher, using a random key of the same length as the plaintext.</p>
    </a>

    <a href="{{ route('playfair.encryption') }}"
       class="block bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg p-6 hover:border-purple-500 hover:shadow-lg hover:shadow-purple-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-th text-2xl text-purple-400"></i>
            <h3 class="text-xl font-bold text-purple-400">Playfair Cipher</h3>
        </div>
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-sm">A digraph substitution cipher that encrypts pairs of letters using a 5×5 matrix constructed from a keyword.</p>
    </a>

    <a href="{{ route('hill.encryption') }}"
       class="block bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg p-6 hover:border-orange-500 hover:shadow-lg hover:shadow-orange-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-table text-2xl text-orange-400"></i>
            <h3 class="text-xl font-bold text-orange-400">Hill Cipher</h3>
        </div>
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-sm">A polygraphic substitution cipher based on linear algebra, encrypting blocks of letters using matrix multiplication.</p>
    </a>
</div>

<h2 class="text-2xl font-bold mt-12 mb-4 bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent">Transposition Techniques</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
    <a href="{{ route('rail-fence.encryption') }}"
       class="block bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg p-6 hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-wave-square text-2xl text-blue-400"></i>
            <h3 class="text-xl font-bold text-blue-400">Rail Fence Cipher</h3>
        </div>
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-sm">A transposition cipher that writes the plaintext in a zigzag pattern along multiple "rails" and reads it row by row.</p>
    </a>

    <a href="{{ route('row-column-transposition.encryption') }}"
       class="block bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg p-6 hover:border-purple-500 hover:shadow-lg hover:shadow-purple-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-columns text-2xl text-purple-400"></i>
            <h3 class="text-xl font-bold text-purple-400">Row-Column Transposition</h3>
        </div>
        <p class="text-light-text-secondary dark:text-dark-text-secondary text-sm">A transposition cipher that writes the plaintext row by row and reads it column by column based on a keyword's alphabetical order.</p>
    </a>
</div>
@endsection

