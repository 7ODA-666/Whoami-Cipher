// Caesar Cipher Algorithm Implementation

class CaesarCipher {
  constructor() {
    this.name = 'Caesar Cipher';
  }

  validateInput(text) {
    // Only letters allowed (A-Z, a-z)
    return /^[A-Za-z\s]+$/.test(text);
  }

  validateKey(key) {
    const shift = parseInt(key);
    return !isNaN(shift) && shift >= 1 && shift <= 25;
  }

  encrypt(plaintext, shift) {
    if (!plaintext) return '';
    const shiftNum = parseInt(shift);
    if (isNaN(shiftNum) || shiftNum < 1 || shiftNum > 25) {
      throw new Error('Shift must be between 1 and 25');
    }
    return this.transform(plaintext, shiftNum);
  }

  decrypt(ciphertext, shift) {
    if (!ciphertext) return '';
    const shiftNum = parseInt(shift);
    if (isNaN(shiftNum) || shiftNum < 1 || shiftNum > 25) {
      throw new Error('Shift must be between 1 and 25');
    }
    return this.transform(ciphertext, -shiftNum);
  }

  transform(text, shift) {
    if (!text) return '';
    return text.split('').map(char => {
      if (char === ' ') return char;
      
      const isUpper = char === char.toUpperCase();
      const base = isUpper ? 'A'.charCodeAt(0) : 'a'.charCodeAt(0);
      const code = char.charCodeAt(0) - base;
      const shifted = ((code + shift) % 26 + 26) % 26;
      return String.fromCharCode(shifted + base);
    }).join('');
  }

  getVisualizationSteps(text, key, mode = 'encrypt') {
    const shift = parseInt(key);
    const steps = [];
    const isEncrypt = mode === 'encrypt';
    const operation = isEncrypt ? shift : -shift;

    steps.push({
      html: `
        <div class="mb-4">
          <p class="text-lg font-semibold mb-2">Starting ${mode}ion Process</p>
          <div class="bg-gray-900 p-4 rounded-lg border border-gray-700">
            <p class="mb-2"><strong>Shift Value:</strong> <span class="text-blue-400 font-mono text-xl">${shift}</span></p>
            <p class="mb-2"><strong>Operation:</strong> ${isEncrypt ? 'Add' : 'Subtract'} ${shift} positions</p>
            <p><strong>Modulo:</strong> All results are taken modulo 26</p>
          </div>
        </div>
      `,
      delay: 800
    });

    // Show alphabet mapping
    const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    let alphabetHtml = '<div class="my-4"><p class="font-semibold mb-2">Alphabet Reference:</p>';
    alphabetHtml += '<div class="grid grid-cols-6 sm:grid-cols-8 md:grid-cols-13 gap-1 text-xs font-mono overflow-x-auto">';
    for (let i = 0; i < 26; i++) {
      const shiftedIndex = (i + operation + 26) % 26;
      alphabetHtml += `<div class="p-2 border border-gray-600 text-center bg-gray-800">
        <div class="text-gray-400 text-xs">${i}</div>
        <div class="text-lg font-bold text-blue-400">${alphabet[i]}</div>
        <div class="text-green-400 text-xs">→ ${alphabet[shiftedIndex]}</div>
      </div>`;
    }
    alphabetHtml += '</div></div>';
    
    steps.push({
      html: alphabetHtml,
      delay: 1000
    });

    let result = '';
    const charSteps = [];
    
    text.split('').forEach((char, index) => {
      if (char === ' ') {
        result += ' ';
        charSteps.push({
          char: char,
          html: `<div class="flex items-center gap-3 p-3 bg-gray-900 rounded border border-gray-700">
            <span class="text-2xl font-bold text-gray-400">"${char}"</span>
            <span class="text-gray-500">→</span>
            <span class="text-2xl font-bold text-gray-400">"${char}"</span>
            <span class="ml-auto text-sm text-gray-500">(space preserved)</span>
          </div>`
        });
      } else {
        const isUpper = char === char.toUpperCase();
        const base = isUpper ? 'A'.charCodeAt(0) : 'a'.charCodeAt(0);
        const code = char.charCodeAt(0) - base;
        const shifted = (code + operation + 26) % 26;
        const newChar = String.fromCharCode(shifted + base);
        result += newChar;

        charSteps.push({
          char: char,
          html: `<div class="flex items-center gap-4 p-4 bg-gray-900 rounded-lg border-2 border-blue-600">
            <div class="text-center">
              <div class="text-xs text-gray-400 mb-1">Character ${index + 1}</div>
              <div class="text-3xl font-bold text-blue-400">"${char}"</div>
            </div>
            <div class="text-center">
              <div class="text-xs text-gray-400 mb-1">Position</div>
              <div class="text-xl font-mono text-purple-400">${code}</div>
            </div>
            <div class="text-center">
              <div class="text-xs text-gray-400 mb-1">Operation</div>
              <div class="text-lg font-mono">${code} ${isEncrypt ? '+' : '-'} ${Math.abs(shift)}</div>
            </div>
            <div class="text-center">
              <div class="text-xs text-gray-400 mb-1">Result</div>
              <div class="text-xl font-mono text-green-400">${shifted}</div>
              <div class="text-xs text-orange-400">(mod 26)</div>
            </div>
            <div class="text-center">
              <div class="text-xs text-gray-400 mb-1">Output</div>
              <div class="text-3xl font-bold text-green-400">"${newChar}"</div>
            </div>
          </div>`
        });
      }
    });

    // Show character transformations
    steps.push({
      html: `<p class="text-lg font-semibold mb-3">Character-by-Character Transformation:</p>`,
      delay: 600
    });

    charSteps.forEach((charStep, idx) => {
      steps.push({
        html: charStep.html,
        delay: 700
      });
    });

    // Final result
    steps.push({
      html: `
        <div class="mt-6 p-6 bg-gradient-to-r from-green-600 to-blue-600 rounded-lg border-2 border-green-400">
          <p class="text-sm font-semibold mb-2 text-white opacity-90">Final Result:</p>
          <p class="text-2xl font-bold text-white font-mono">${result}</p>
        </div>
      `,
      delay: 500
    });

    return steps;
  }
}

