// Monoalphabetic Cipher Algorithm Implementation

class MonoalphabeticCipher {
  constructor() {
    this.name = 'Monoalphabetic Cipher';
    this.alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  }

  validateInput(text) {
    return /^[A-Za-z\s]+$/.test(text);
  }

  validateKey(key) {
    if (key.length !== 26) return false;
    const upperKey = key.toUpperCase();
    const uniqueChars = new Set(upperKey);
    if (uniqueChars.size !== 26) return false;
    
    // Check if all characters are letters
    return /^[A-Z]+$/.test(upperKey);
  }

  generateRandomKey() {
    const alphabet = this.alphabet.split('');
    // Fisher-Yates shuffle
    for (let i = alphabet.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [alphabet[i], alphabet[j]] = [alphabet[j], alphabet[i]];
    }
    return alphabet.join('');
  }

  encrypt(plaintext, key) {
    const upperKey = key.toUpperCase();
    const upperText = plaintext.toUpperCase();
    
    return upperText.split('').map(char => {
      if (char === ' ') return char;
      const index = this.alphabet.indexOf(char);
      return index !== -1 ? upperKey[index] : char;
    }).join('');
  }

  decrypt(ciphertext, key) {
    const upperKey = key.toUpperCase();
    const upperText = ciphertext.toUpperCase();
    
    return upperText.split('').map(char => {
      if (char === ' ') return char;
      const index = upperKey.indexOf(char);
      return index !== -1 ? this.alphabet[index] : char;
    }).join('');
  }

  getVisualizationSteps(text, key, mode = 'encrypt') {
    const steps = [];
    const upperKey = key.toUpperCase();
    const upperText = text.toUpperCase();
    const isEncrypt = mode === 'encrypt';

    steps.push({
      html: `<p><strong>Step 1:</strong> ${isEncrypt ? 'Encryption' : 'Decryption'} using substitution key</p>`,
      delay: 500
    });

    steps.push({
      html: `<p><strong>Alphabet:</strong> ${this.alphabet}</p><p><strong>Key:</strong> ${upperKey}</p>`,
      delay: 800
    });

    let result = '';
    upperText.split('').forEach((char, index) => {
      if (char === ' ') {
        result += ' ';
        steps.push({
          html: `<p>Character ${index + 1}: "${char}" (space) → "${char}"</p>`,
          delay: 600
        });
      } else {
        if (isEncrypt) {
          const pos = this.alphabet.indexOf(char);
          const newChar = upperKey[pos];
          result += newChar;
          steps.push({
            html: `<p>Character ${index + 1}: "${char}" → Position ${pos} in alphabet → "${newChar}" from key</p>`,
            delay: 600
          });
        } else {
          const pos = upperKey.indexOf(char);
          const newChar = this.alphabet[pos];
          result += newChar;
          steps.push({
            html: `<p>Character ${index + 1}: "${char}" → Position ${pos} in key → "${newChar}" from alphabet</p>`,
            delay: 600
          });
        }
      }
    });

    steps.push({
      html: `<p><strong>Result:</strong> ${result}</p>`,
      delay: 500
    });

    return steps;
  }
}

