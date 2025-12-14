@extends('layouts.master')

@section('title', 'Rail Fence Cipher - Encryption | CipherViz')
@section('page-title', 'Rail Fence Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('rail-fence.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('rail-fence.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('rail-fence.about') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border mt-2 mb-2">
        About
    </a>
</div>

<!-- Visualization Section -->
<div class="bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-xl p-6 mb-8 shadow-xl">
    <div class="flex items-center justify-between mb-4">
        <label class="flex items-center gap-3 cursor-pointer">
            <div class="switch">
                <input type="checkbox" class="viz-toggle" checked />
                <span class="slider"></span>
            </div>
            <span class="text-light-text dark:text-dark-text font-semibold">Show Visualization</span>
        </label>
    </div>
    <div class="visualization-content min-h-[200px] p-4 bg-light-bg dark:bg-dark-bg rounded-lg border border-light-border dark:border-dark-border"></div>
</div>

<!-- Tool Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
    <div class="bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-xl p-4 lg:p-6 shadow-xl">
        <label for="input-text" class="block mb-2 text-light-text dark:text-dark-text font-semibold">Plaintext</label>
        <textarea
            id="input-text"
            class="w-full p-3 lg:p-4 bg-light-bg dark:bg-dark-bg border border-light-border dark:border-dark-border rounded-lg text-light-text dark:text-dark-text font-mono resize-y focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm lg:text-base"
            placeholder="Enter plaintext (letters only)"
            rows="6"
        ></textarea>

        <label for="rails-input" class="block mt-4 mb-2 text-light-text dark:text-dark-text font-semibold">Number of Rails (2-10)</label>
        <input
            type="number"
            id="rails-input"
            min="2"
            max="10"
            value="3"
            class="w-full p-3 bg-light-bg dark:bg-dark-bg border border-light-border dark:border-dark-border rounded-lg text-light-text dark:text-dark-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm lg:text-base"
            placeholder="Number of rails"
        />
        <p class="text-xs text-light-text-secondary dark:text-dark-text-secondary mt-2">The number of horizontal lines (rails) to write the text in zigzag pattern.</p>
    </div>

    <div class="bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-xl p-4 lg:p-6 shadow-xl">
        <label for="output-text" class="block mb-2 text-light-text dark:text-dark-text font-semibold">Ciphertext</label>
        <textarea
            id="output-text"
            readonly
            class="w-full p-3 lg:p-4 bg-light-bg dark:bg-dark-bg border border-light-border dark:border-dark-border rounded-lg text-light-text dark:text-dark-text font-mono resize-y cursor-not-allowed opacity-75 text-sm lg:text-base"
            rows="6"
        ></textarea>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mt-4">
            <button
                onclick="executeEncrypt()"
                class="flex-1 px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl text-sm sm:text-base"
            >
                Encrypt
            </button>
            <button
                class="copy-btn px-4 sm:px-6 py-2 sm:py-3 bg-light-card dark:bg-dark-card hover:bg-gray-100 dark:hover:bg-gray-600 text-light-text-secondary dark:text-dark-text-secondary hover:text-light-text dark:hover:text-dark-text font-semibold rounded-lg transition-colors border border-light-border dark:border-dark-border text-sm sm:text-base"
            >
                Copy
            </button>
            <button
                class="clear-btn px-4 sm:px-6 py-2 sm:py-3 bg-light-card dark:bg-dark-card hover:bg-gray-100 dark:hover:bg-gray-600 text-light-text-secondary dark:text-dark-text-secondary hover:text-light-text dark:hover:text-dark-text font-semibold rounded-lg transition-colors border border-light-border dark:border-dark-border text-sm sm:text-base"
            >
                Clear
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/crypto-ajax.js') }}"></script>
<script>
function executeEncrypt() {
    const inputField = document.getElementById('input-text');
    const keyField = document.getElementById('rails-input');
    const outputField = document.getElementById('output-text');
    const vizContent = document.querySelector('.visualization-content');

    executeCryptoAjax('rail-fence', 'encrypt', inputField, keyField, outputField, vizContent);
}
</script>
@endpush
@endsection

