// Polyalphabetic (Vigenère) Cipher Algorithm Implementation

class PolyalphabeticCipher {
  constructor() {
    this.name = 'Polyalphabetic (Vigenère) Cipher';
    this.alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  }

  validateInput(text) {
    return /^[A-Za-z\s]+$/.test(text);
  }

  validateKey(key) {
    return /^[A-Za-z]+$/.test(key) && key.length > 0;
  }

  prepareKey(key, length) {
    const upperKey = key.toUpperCase().replace(/\s/g, '');
    let keyStream = '';
    for (let i = 0; i < length; i++) {
      keyStream += upperKey[i % upperKey.length];
    }
    return keyStream;
  }

  encrypt(plaintext, key) {
    if (!plaintext || !key) return '';
    const upperText = plaintext.toUpperCase();
    const textLength = upperText.replace(/\s/g, '').length;
    const keyStream = this.prepareKey(key, textLength);
    
    let keyIndex = 0;
    return upperText.split('').map(char => {
      if (char === ' ') return char;
      
      const textPos = this.alphabet.indexOf(char);
      if (textPos === -1) return char; // Invalid character
      
      const keyChar = keyStream[keyIndex++];
      const keyPos = this.alphabet.indexOf(keyChar);
      if (keyPos === -1) return char; // Invalid key character
      
      const newPos = (textPos + keyPos) % 26;
      return this.alphabet[newPos];
    }).join('');
  }

  decrypt(ciphertext, key) {
    if (!ciphertext || !key) return '';
    const upperText = ciphertext.toUpperCase();
    const textLength = upperText.replace(/\s/g, '').length;
    const keyStream = this.prepareKey(key, textLength);
    
    let keyIndex = 0;
    return upperText.split('').map(char => {
      if (char === ' ') return char;
      
      const textPos = this.alphabet.indexOf(char);
      if (textPos === -1) return char; // Invalid character
      
      const keyChar = keyStream[keyIndex++];
      const keyPos = this.alphabet.indexOf(keyChar);
      if (keyPos === -1) return char; // Invalid key character
      
      const newPos = ((textPos - keyPos) % 26 + 26) % 26;
      return this.alphabet[newPos];
    }).join('');
  }

  getVisualizationSteps(text, key, mode = 'encrypt') {
    const steps = [];
    const upperText = text.toUpperCase();
    const upperKey = key.toUpperCase();
    const isEncrypt = mode === 'encrypt';

    steps.push({
      html: `<p><strong>Step 1:</strong> ${isEncrypt ? 'Encryption' : 'Decryption'} using Vigenère cipher</p>`,
      delay: 500
    });

    steps.push({
      html: `<p><strong>Keyword:</strong> ${upperKey}</p><p><strong>Text:</strong> ${upperText}</p>`,
      delay: 800
    });

    const keyStream = this.prepareKey(key, upperText.replace(/\s/g, '').length);
    let keyIndex = 0;
    let result = '';

    upperText.split('').forEach((char, index) => {
      if (char === ' ') {
        result += ' ';
        steps.push({
          html: `<p>Position ${index + 1}: "${char}" (space) → "${char}"</p>`,
          delay: 600
        });
      } else {
        const textPos = this.alphabet.indexOf(char);
        const keyChar = keyStream[keyIndex];
        const keyPos = this.alphabet.indexOf(keyChar);
        
        if (isEncrypt) {
          const newPos = (textPos + keyPos) % 26;
          const newChar = this.alphabet[newPos];
          result += newChar;
          steps.push({
            html: `<p>Position ${index + 1}: "${char}" (${textPos}) + "${keyChar}" (${keyPos}) = ${newPos} (mod 26) → "${newChar}"</p>`,
            delay: 700
          });
        } else {
          const newPos = (textPos - keyPos + 26) % 26;
          const newChar = this.alphabet[newPos];
          result += newChar;
          steps.push({
            html: `<p>Position ${index + 1}: "${char}" (${textPos}) - "${keyChar}" (${keyPos}) = ${newPos} (mod 26) → "${newChar}"</p>`,
            delay: 700
          });
        }
        keyIndex++;
      }
    });

    steps.push({
      html: `<p><strong>Result:</strong> ${result}</p>`,
      delay: 500
    });

    return steps;
  }
}

