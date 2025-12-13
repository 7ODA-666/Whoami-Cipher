@extends('layouts.master')

@section('title', 'Caesar Cipher - Decryption | CipherViz')
@section('page-title', 'Caesar Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('caesar.encryption') }}"
       class="tab px-6 ml-2 mt-2 mb-2 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600">
        Encryption
    </a>
    <a href="{{ route('caesar.decryption') }}"
       class="tab px-6 mt-2 mb-2 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent">
        Decryption
    </a>
    <a href="{{ route('caesar.about') }}"
       class="tab px-6 mt-2 mb-2 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gray-700 text-gray-300 hover:bg-gray-600 hover:text-white border-2 border-gray-600">
        About
    </a>
</div>

<!-- Visualization Section -->
<div class="bg-gray-800 border border-gray-700 rounded-xl p-6 mb-8 shadow-xl">
    <div class="flex items-center justify-between mb-4">
        <label class="flex items-center gap-3 cursor-pointer">
            <div class="switch">
                <input type="checkbox" class="viz-toggle" checked />
                <span class="slider bg-gray-600"></span>
            </div>
            <span class="text-gray-200 font-semibold">Show Visualization</span>
        </label>
    </div>
    <div class="visualization-content min-h-[200px] p-4 bg-gray-900 rounded-lg border border-gray-700"></div>
</div>

<!-- Tool Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 lg:p-6 shadow-xl">
        <label for="ciphertext-input" class="block mb-2 text-gray-200 font-semibold">Ciphertext</label>
        <textarea
            id="ciphertext-input"
            class="w-full p-3 lg:p-4 bg-gray-900 border border-gray-700 rounded-lg text-gray-100 font-mono resize-y focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm lg:text-base"
            placeholder="Enter ciphertext (letters only)"
            rows="6"
        ></textarea>

        <label for="key-input-decrypt" class="block mt-4 mb-2 text-gray-200 font-semibold">Shift Key (1-25)</label>
        <input
            type="number"
            id="key-input-decrypt"
            min="1"
            max="25"
            class="w-full p-3 bg-gray-900 border border-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm lg:text-base"
            placeholder="Enter shift (1-25)"
        />
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 lg:p-6 shadow-xl">
        <label for="plaintext-output" class="block mb-2 text-gray-200 font-semibold">Plaintext</label>
        <textarea
            id="plaintext-output"
            readonly
            class="w-full p-3 lg:p-4 bg-gray-900 border border-gray-700 rounded-lg text-gray-100 font-mono resize-y cursor-not-allowed opacity-75 text-sm lg:text-base"
            rows="6"
        ></textarea>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mt-4">
            <button
                onclick="executeDecrypt()"
                class="flex-1 px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl text-sm sm:text-base"
            >
                Decrypt
            </button>
            <button
                class="copy-btn px-4 sm:px-6 py-2 sm:py-3 bg-gray-700 hover:bg-gray-600 text-gray-200 font-semibold rounded-lg transition-colors border border-gray-600 text-sm sm:text-base"
            >
                Copy
            </button>
            <button
                class="clear-btn px-4 sm:px-6 py-2 sm:py-3 bg-gray-700 hover:bg-gray-600 text-gray-200 font-semibold rounded-lg transition-colors border border-gray-600 text-sm sm:text-base"
            >
                Clear
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/crypto-ajax.js') }}"></script>
<script>
function executeDecrypt() {
    const inputField = document.getElementById('ciphertext-input');
    const keyField = document.getElementById('key-input-decrypt');
    const outputField = document.getElementById('plaintext-output');
    const vizContent = document.querySelector('.visualization-content');

    executeCryptoAjax('caesar', 'decrypt', inputField, keyField, outputField, vizContent);
}
</script>
@endpush
@endsection

