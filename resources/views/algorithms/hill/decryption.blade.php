@extends('layouts.master')

@section('title', 'Decryption - Hill Cipher')
@section('page-title', 'Hill Cipher')

@section('content')
<!-- Tabs -->
<div class="flex gap-3 mb-6 lg:mb-8 overflow-x-auto">
    <a href="{{ route('hill.encryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-light-card dark:bg-dark-card text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-light-text dark:hover:text-dark-text border-2 border-light-border dark:border-dark-border ml-2 mt-2 mb-2">
        Encryption
    </a>
    <a href="{{ route('hill.decryption') }}"
       class="tab px-6 py-2.5 rounded-full font-semibold transition-all whitespace-nowrap bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg active-tab border-2 border-transparent mt-2 mb-2">
        Decryption
    </a>
    <a href="{{ route('hill.about') }}"
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
        <div class="flex items-center gap-3">
            <label class="text-sm font-semibold text-light-text-secondary dark:text-dark-text-secondary">Speed:</label>
            <input type="range" id="viz-speed-control" min="200" max="3000" value="1000" step="100"
                   class="w-20 h-2 bg-light-border dark:bg-dark-border rounded-lg appearance-none cursor-pointer slider-thumb">
            <span class="text-xs font-mono text-light-text-secondary dark:text-dark-text-secondary w-12" id="viz-speed-display">1000ms</span>
        </div>
    </div>
    <div class="visualization-content min-h-[200px] p-4 bg-light-bg dark:bg-dark-bg rounded-lg border border-light-border dark:border-dark-border"></div>
</div>

<!-- Tool Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
    <div class="bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-xl p-4 lg:p-6 shadow-xl">
        <label for="ciphertext-input" class="block mb-2 text-light-text dark:text-dark-text font-semibold">Ciphertext</label>
        <textarea
            id="ciphertext-input"
            class="w-full p-3 lg:p-4 bg-light-bg dark:bg-dark-bg border border-light-border dark:border-dark-border rounded-lg text-light-text dark:text-dark-text font-mono resize-y focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm lg:text-base"
            placeholder="Enter ciphertext (letters only)"
            rows="6"
        ></textarea>

        <!-- Matrix Key Section -->
        <div class="matrix-key-section mt-6">
            <label class="block mb-3 text-light-text dark:text-dark-text font-semibold">Key Matrix</label>

            <!-- Top Row: Size Select + Generate Button -->
            <div class="flex gap-3 mb-4">
                <select id="hill-matrix-size-decrypt"
                        class="flex-1 px-4 py-2 bg-light-bg dark:bg-dark-bg border border-light-border dark:border-dark-border rounded-lg text-light-text dark:text-dark-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm font-semibold cursor-pointer hover:border-light-border dark:hover:border-dark-border transition-colors">
                    <option value="2">2 × 2</option>
                    <option value="3">3 × 3</option>
                </select>
            </div>

            <!-- Matrix Input Container -->
            <div class="bg-light-bg dark:bg-dark-bg border border-light-border dark:border-dark-border rounded-lg p-4">
                <div class="text-center mb-3">
                    <span class="text-light-text-secondary dark:text-dark-text-secondary text-sm">Matrix Values (0-25)</span>
                </div>
                <div id="matrix-input-container-decrypt" class="matrix-grid size2"></div>
            </div>
        </div>
    </div>

    <div class="bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-xl p-4 lg:p-6 shadow-xl">
        <label for="plaintext-output" class="block mb-2 text-light-text dark:text-dark-text font-semibold">Plaintext</label>
        <textarea
            id="plaintext-output"
            readonly
            class="w-full p-3 lg:p-4 bg-light-bg dark:bg-dark-bg border border-light-border dark:border-dark-border rounded-lg text-light-text dark:text-dark-text font-mono resize-y cursor-not-allowed opacity-75 text-sm lg:text-base"
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
function initHillMatrix(sizeSelectId, containerId) {
    const sizeSelect = document.getElementById(sizeSelectId);
    const container = document.getElementById(containerId);

    if (!sizeSelect || !container) return;

    function createMatrixInputs(size) {
        container.className = `matrix-grid size${size}`;
        container.innerHTML = '';

        for (let i = 0; i < size; i++) {
            for (let j = 0; j < size; j++) {
                const input = document.createElement('input');
                input.type = 'number';
                input.min = '0';
                input.max = '25';
                input.value = '0';
                input.className = 'matrix-cell';
                input.placeholder = '0';
                input.setAttribute('data-row', i);
                input.setAttribute('data-col', j);

                // Add input validation
                input.addEventListener('input', function() {
                    let val = parseInt(this.value);
                    if (isNaN(val) || val < 0) {
                        this.value = '0';
                    } else if (val > 25) {
                        this.value = '25';
                    }
                });

                container.appendChild(input);
            }
        }
    }

    sizeSelect.addEventListener('change', () => {
        createMatrixInputs(parseInt(sizeSelect.value));
    });

    createMatrixInputs(parseInt(sizeSelect.value));
}

initHillMatrix('hill-matrix-size-decrypt', 'matrix-input-container-decrypt');

function generateHillKeyDecrypt() {
    const size = parseInt(document.getElementById('hill-matrix-size-decrypt').value);
    const container = document.getElementById('matrix-input-container-decrypt');
    const inputs = container.querySelectorAll('input');
    const button = event.target.closest('button');

    // Disable button and show loading state
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Generating...</span>';

    // Disable all inputs
    inputs.forEach(input => {
        input.disabled = true;
        input.classList.add('opacity-50', 'cursor-not-allowed');
    });

    fetch('{{ route("hill.generate.key") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ size: size })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const values = data.key.split(' ').map(v => parseInt(v.trim()));
            inputs.forEach((input, index) => {
                if (index < values.length) {
                    input.value = values[index];
                    // Add success animation
                    input.style.transition = 'all 0.3s ease';
                    input.style.borderColor = 'rgb(34, 197, 94)';
                    input.style.boxShadow = '0 0 0 3px rgba(34, 197, 94, 0.2)';

                    setTimeout(() => {
                        input.style.borderColor = '';
                        input.style.boxShadow = '';
                    }, 1500);
                }
            });
        } else {
            alert('Error generating key: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to generate key. Please try again.');
    })
    .finally(() => {
        // Re-enable button with original content
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-dice"></i><span>Generate</span>';

        // Re-enable inputs
        inputs.forEach(input => {
            input.disabled = false;
            input.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    });
}

function executeDecrypt() {
    const inputField = document.getElementById('ciphertext-input');
    const keyField = document.createElement('input');
    const outputField = document.getElementById('plaintext-output');
    const vizContent = document.querySelector('.visualization-content');
    const size = parseInt(document.getElementById('hill-matrix-size-decrypt').value);

    executeCryptoAjax('hill', 'decrypt', inputField, keyField, outputField, vizContent, { size: size });
}
</script>
@endpush
@endsection

