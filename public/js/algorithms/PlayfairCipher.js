// Playfair Cipher Algorithm Implementation

class PlayfairCipher {
  constructor() {
    this.name = 'Playfair Cipher';
    this.alphabet = 'ABCDEFGHIKLMNOPQRSTUVWXYZ'; // J is merged with I
  }

  validateInput(text) {
    return /^[A-Za-z\s]+$/.test(text);
  }

  validateKey(key) {
    return /^[A-Za-z]+$/.test(key) && key.length > 0;
  }

  prepareText(text) {
    // Remove spaces, convert to uppercase, replace J with I
    let prepared = text.toUpperCase().replace(/\s/g, '').replace(/J/g, 'I');
    
    // Add X between duplicate letters in pairs
    let result = '';
    for (let i = 0; i < prepared.length; i++) {
      result += prepared[i];
      if (i < prepared.length - 1 && prepared[i] === prepared[i + 1]) {
        result += 'X';
      }
    }
    
    // If odd length, add X at the end
    if (result.length % 2 !== 0) {
      result += 'X';
    }
    
    return result;
  }

  buildMatrix(key) {
    const upperKey = key.toUpperCase().replace(/J/g, 'I');
    const matrix = [];
    const used = new Set();
    
    // Add key characters
    for (const char of upperKey) {
      if (!used.has(char) && this.alphabet.includes(char)) {
        matrix.push(char);
        used.add(char);
      }
    }
    
    // Add remaining alphabet characters
    for (const char of this.alphabet) {
      if (!used.has(char)) {
        matrix.push(char);
      }
    }
    
    return matrix;
  }

  findPosition(matrix, char) {
    const index = matrix.indexOf(char);
    return { row: Math.floor(index / 5), col: index % 5 };
  }

  getChar(matrix, row, col) {
    return matrix[row * 5 + col];
  }

  encryptPair(matrix, char1, char2) {
    const pos1 = this.findPosition(matrix, char1);
    const pos2 = this.findPosition(matrix, char2);
    
    let newPos1, newPos2;
    
    // Same row
    if (pos1.row === pos2.row) {
      newPos1 = { row: pos1.row, col: (pos1.col + 1) % 5 };
      newPos2 = { row: pos2.row, col: (pos2.col + 1) % 5 };
    }
    // Same column
    else if (pos1.col === pos2.col) {
      newPos1 = { row: (pos1.row + 1) % 5, col: pos1.col };
      newPos2 = { row: (pos2.row + 1) % 5, col: pos2.col };
    }
    // Rectangle
    else {
      newPos1 = { row: pos1.row, col: pos2.col };
      newPos2 = { row: pos2.row, col: pos1.col };
    }
    
    return {
      char1: this.getChar(matrix, newPos1.row, newPos1.col),
      char2: this.getChar(matrix, newPos2.row, newPos2.col)
    };
  }

  decryptPair(matrix, char1, char2) {
    const pos1 = this.findPosition(matrix, char1);
    const pos2 = this.findPosition(matrix, char2);
    
    let newPos1, newPos2;
    
    // Same row
    if (pos1.row === pos2.row) {
      newPos1 = { row: pos1.row, col: (pos1.col - 1 + 5) % 5 };
      newPos2 = { row: pos2.row, col: (pos2.col - 1 + 5) % 5 };
    }
    // Same column
    else if (pos1.col === pos2.col) {
      newPos1 = { row: (pos1.row - 1 + 5) % 5, col: pos1.col };
      newPos2 = { row: (pos2.row - 1 + 5) % 5, col: pos2.col };
    }
    // Rectangle
    else {
      newPos1 = { row: pos1.row, col: pos2.col };
      newPos2 = { row: pos2.row, col: pos1.col };
    }
    
    return {
      char1: this.getChar(matrix, newPos1.row, newPos1.col),
      char2: this.getChar(matrix, newPos2.row, newPos2.col)
    };
  }

  encrypt(plaintext, key) {
    const matrix = this.buildMatrix(key);
    const prepared = this.prepareText(plaintext);
    let result = '';
    
    for (let i = 0; i < prepared.length; i += 2) {
      const pair = this.encryptPair(matrix, prepared[i], prepared[i + 1]);
      result += pair.char1 + pair.char2;
    }
    
    return result;
  }

  decrypt(ciphertext, key) {
    const matrix = this.buildMatrix(key);
    const upperText = ciphertext.toUpperCase().replace(/\s/g, '');
    let result = '';
    
    for (let i = 0; i < upperText.length; i += 2) {
      const pair = this.decryptPair(matrix, upperText[i], upperText[i + 1]);
      result += pair.char1 + pair.char2;
    }
    
    return result;
  }

  getVisualizationSteps(text, key, mode = 'encrypt') {
    const steps = [];
    const matrix = this.buildMatrix(key);
    const isEncrypt = mode === 'encrypt';

    steps.push({
      html: `<p><strong>Step 1:</strong> Building 5×5 Playfair matrix</p>`,
      delay: 500
    });

    // Display matrix
    let matrixHtml = '<table style="border-collapse: collapse; margin: 1rem 0;"><tbody>';
    for (let row = 0; row < 5; row++) {
      matrixHtml += '<tr>';
      for (let col = 0; col < 5; col++) {
        matrixHtml += `<td style="border: 1px solid var(--border-color); padding: 0.5rem; text-align: center; width: 40px;">${matrix[row * 5 + col]}</td>`;
      }
      matrixHtml += '</tr>';
    }
    matrixHtml += '</tbody></table>';
    
    steps.push({
      html: matrixHtml,
      delay: 1000
    });

    const prepared = isEncrypt ? this.prepareText(text) : text.toUpperCase().replace(/\s/g, '');
    steps.push({
      html: `<p><strong>Step 2:</strong> Prepared text (in pairs): ${prepared.match(/.{1,2}/g).join(' ')}</p>`,
      delay: 800
    });

    let result = '';
    for (let i = 0; i < prepared.length; i += 2) {
      const char1 = prepared[i];
      const char2 = prepared[i + 1];
      const pos1 = this.findPosition(matrix, char1);
      const pos2 = this.findPosition(matrix, char2);
      
      let pair;
      if (isEncrypt) {
        pair = this.encryptPair(matrix, char1, char2);
      } else {
        pair = this.decryptPair(matrix, char1, char2);
      }
      
      result += pair.char1 + pair.char2;
      
      let rule = '';
      if (pos1.row === pos2.row) {
        rule = 'Same row: shift right';
      } else if (pos1.col === pos2.col) {
        rule = 'Same column: shift down';
      } else {
        rule = 'Rectangle: swap columns';
      }
      
      steps.push({
        html: `<p><strong>Pair ${i/2 + 1}:</strong> "${char1}" (${pos1.row},${pos1.col}) and "${char2}" (${pos2.row},${pos2.col}) → ${rule} → "${pair.char1}${pair.char2}"</p>`,
        delay: 1000
      });
    }

    steps.push({
      html: `<p><strong>Result:</strong> ${result}</p>`,
      delay: 500
    });

    return steps;
  }
}

