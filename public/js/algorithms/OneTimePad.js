// One-Time Pad (OTP) Cipher Algorithm Implementation

class OneTimePadCipher {
  constructor() {
    this.name = 'One-Time Pad (OTP)';
    this.alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  }

  validateInput(text) {
    return /^[A-Za-z\s]+$/.test(text);
  }

  validateKey(key, text) {
    const textLength = text.replace(/\s/g, '').length;
    const keyLength = key.replace(/\s/g, '').length;
    return keyLength === textLength && /^[A-Za-z\s]+$/.test(key);
  }

  generateRandomKey(length) {
    let key = '';
    for (let i = 0; i < length; i++) {
      const randomIndex = Math.floor(Math.random() * 26);
      key += this.alphabet[randomIndex];
    }
    return key;
  }

  encrypt(plaintext, key) {
    if (!plaintext || !key) return '';
    const upperText = plaintext.toUpperCase();
    const upperKey = key.toUpperCase().replace(/\s/g, '');
    
    // Validate key length matches text length (excluding spaces)
    const textLength = upperText.replace(/\s/g, '').length;
    if (upperKey.length !== textLength) {
      throw new Error(`Key length (${upperKey.length}) must match text length (${textLength}) excluding spaces`);
    }
    
    let keyIndex = 0;
    return upperText.split('').map(char => {
      if (char === ' ') return char;
      
      const textPos = this.alphabet.indexOf(char);
      if (textPos === -1) return char; // Invalid character, return as-is
      
      const keyChar = upperKey[keyIndex++];
      const keyPos = this.alphabet.indexOf(keyChar);
      if (keyPos === -1) return char; // Invalid key character
      
      const newPos = (textPos + keyPos) % 26;
      return this.alphabet[newPos];
    }).join('');
  }

  decrypt(ciphertext, key) {
    if (!ciphertext || !key) return '';
    const upperText = ciphertext.toUpperCase();
    const upperKey = key.toUpperCase().replace(/\s/g, '');
    
    // Validate key length matches text length (excluding spaces)
    const textLength = upperText.replace(/\s/g, '').length;
    if (upperKey.length !== textLength) {
      throw new Error(`Key length (${upperKey.length}) must match text length (${textLength}) excluding spaces`);
    }
    
    let keyIndex = 0;
    return upperText.split('').map(char => {
      if (char === ' ') return char;
      
      const textPos = this.alphabet.indexOf(char);
      if (textPos === -1) return char; // Invalid character, return as-is
      
      const keyChar = upperKey[keyIndex++];
      const keyPos = this.alphabet.indexOf(keyChar);
      if (keyPos === -1) return char; // Invalid key character
      
      const newPos = ((textPos - keyPos) % 26 + 26) % 26;
      return this.alphabet[newPos];
    }).join('');
  }

  getVisualizationSteps(text, key, mode = 'encrypt') {
    const steps = [];
    const upperText = text.toUpperCase();
    const upperKey = key.toUpperCase().replace(/\s/g, '');
    const isEncrypt = mode === 'encrypt';

    steps.push({
      html: `<p><strong>Step 1:</strong> ${isEncrypt ? 'Encryption' : 'Decryption'} using One-Time Pad</p>`,
      delay: 500
    });

    steps.push({
      html: `<p><strong>Key (same length as text):</strong> ${upperKey}</p><p><strong>Text:</strong> ${upperText}</p>`,
      delay: 800
    });

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
        const keyChar = upperKey[keyIndex];
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

