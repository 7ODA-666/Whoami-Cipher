// Row-Column Transposition Cipher Algorithm Implementation

class RowColumnTranspositionCipher {
  constructor() {
    this.name = 'Row-Column Transposition Cipher';
  }

  validateInput(text) {
    return /^[A-Za-z\s]+$/.test(text);
  }

  validateKey(key) {
    return /^[A-Za-z]+$/.test(key) && key.length > 0;
  }

  getColumnOrder(key) {
    const upperKey = key.toUpperCase();
    const keyChars = upperKey.split('').map((char, index) => ({
      char,
      originalIndex: index
    }));
    
    // Sort by character, then by original index for ties
    keyChars.sort((a, b) => {
      if (a.char !== b.char) {
        return a.char.localeCompare(b.char);
      }
      return a.originalIndex - b.originalIndex;
    });
    
    return keyChars.map(item => item.originalIndex);
  }

  encrypt(plaintext, key) {
    const text = plaintext.replace(/\s/g, '').toUpperCase();
    const keyLength = key.length;
    const numRows = Math.ceil(text.length / keyLength);
    
    // Create grid
    const grid = [];
    let textIndex = 0;
    for (let row = 0; row < numRows; row++) {
      grid[row] = [];
      for (let col = 0; col < keyLength; col++) {
        if (textIndex < text.length) {
          grid[row][col] = text[textIndex++];
        } else {
          grid[row][col] = 'X'; // Padding
        }
      }
    }
    
    // Get column order
    const columnOrder = this.getColumnOrder(key);
    
    // Read column by column
    let result = '';
    for (const colIndex of columnOrder) {
      for (let row = 0; row < numRows; row++) {
        result += grid[row][colIndex];
      }
    }
    
    return result;
  }

  decrypt(ciphertext, key) {
    const text = ciphertext.replace(/\s/g, '').toUpperCase();
    const keyLength = key.length;
    const numRows = Math.ceil(text.length / keyLength);
    
    // Get column order
    const columnOrder = this.getColumnOrder(key);
    
    // Create inverse order map
    const inverseOrder = Array(keyLength);
    columnOrder.forEach((originalCol, newIndex) => {
      inverseOrder[originalCol] = newIndex;
    });
    
    // Fill grid column by column
    const grid = Array(numRows).fill(null).map(() => Array(keyLength));
    let textIndex = 0;
    
    for (const colIndex of columnOrder) {
      for (let row = 0; row < numRows; row++) {
        if (textIndex < text.length) {
          grid[row][colIndex] = text[textIndex++];
        }
      }
    }
    
    // Read row by row
    let result = '';
    for (let row = 0; row < numRows; row++) {
      for (let col = 0; col < keyLength; col++) {
        if (grid[row][col]) {
          result += grid[row][col];
        }
      }
    }
    
    // Remove padding X's at the end
    return result.replace(/X+$/, '');
  }

  getVisualizationSteps(text, key, mode = 'encrypt') {
    const steps = [];
    const cleanText = text.replace(/\s/g, '').toUpperCase();
    const upperKey = key.toUpperCase();
    const isEncrypt = mode === 'encrypt';

    steps.push({
      html: `<p><strong>Step 1:</strong> ${isEncrypt ? 'Encryption' : 'Decryption'} using Row-Column Transposition</p>`,
      delay: 500
    });

    steps.push({
      html: `<p><strong>Keyword:</strong> ${upperKey}</p><p><strong>Text:</strong> ${cleanText}</p>`,
      delay: 500
    });

    if (isEncrypt) {
      const keyLength = key.length;
      const numRows = Math.ceil(cleanText.length / keyLength);
      
      steps.push({
        html: `<p><strong>Step 2:</strong> Writing text row by row into ${numRows}×${keyLength} grid</p>`,
        delay: 500
      });

      // Create grid
      const grid = [];
      let textIndex = 0;
      for (let row = 0; row < numRows; row++) {
        grid[row] = [];
        for (let col = 0; col < keyLength; col++) {
          if (textIndex < cleanText.length) {
            grid[row][col] = cleanText[textIndex++];
          } else {
            grid[row][col] = 'X';
          }
        }
      }

      // Display grid
      let gridHtml = '<table style="border-collapse: collapse; margin: 1rem 0;"><thead><tr><th></th>';
      for (let i = 0; i < keyLength; i++) {
        gridHtml += `<th style="border: 1px solid var(--border-color); padding: 0.5rem; background: var(--bg-secondary);">${upperKey[i]}</th>`;
      }
      gridHtml += '</tr></thead><tbody>';
      for (let row = 0; row < numRows; row++) {
        gridHtml += '<tr><td style="border: 1px solid var(--border-color); padding: 0.5rem; background: var(--bg-secondary); font-weight: bold;">Row ' + (row + 1) + '</td>';
        for (let col = 0; col < keyLength; col++) {
          gridHtml += `<td style="border: 1px solid var(--border-color); padding: 0.5rem; text-align: center;">${grid[row][col]}</td>`;
        }
        gridHtml += '</tr>';
      }
      gridHtml += '</tbody></table>';
      
      steps.push({
        html: gridHtml,
        delay: 1000
      });

      const columnOrder = this.getColumnOrder(key);
      steps.push({
        html: `<p><strong>Step 3:</strong> Column order based on keyword alphabetical order: ${columnOrder.map(i => upperKey[i]).join(' → ')}</p>`,
        delay: 800
      });

      steps.push({
        html: `<p><strong>Step 4:</strong> Reading column by column in order</p>`,
        delay: 500
      });

      let result = '';
      for (const colIndex of columnOrder) {
        for (let row = 0; row < numRows; row++) {
          result += grid[row][colIndex];
        }
      }

      steps.push({
        html: `<p><strong>Result:</strong> ${result}</p>`,
        delay: 500
      });
    } else {
      // Decryption
      const keyLength = key.length;
      const numRows = Math.ceil(cleanText.length / keyLength);
      
      const columnOrder = this.getColumnOrder(key);
      steps.push({
        html: `<p><strong>Step 2:</strong> Column order: ${columnOrder.map(i => upperKey[i]).join(' → ')}</p>`,
        delay: 500
      });

      steps.push({
        html: `<p><strong>Step 3:</strong> Filling grid column by column (inverse order)</p>`,
        delay: 500
      });

      const grid = Array(numRows).fill(null).map(() => Array(keyLength));
      let textIndex = 0;
      
      for (const colIndex of columnOrder) {
        for (let row = 0; row < numRows; row++) {
          if (textIndex < cleanText.length) {
            grid[row][colIndex] = cleanText[textIndex++];
          }
        }
      }

      let gridHtml = '<table style="border-collapse: collapse; margin: 1rem 0;"><thead><tr><th></th>';
      for (let i = 0; i < keyLength; i++) {
        gridHtml += `<th style="border: 1px solid var(--border-color); padding: 0.5rem; background: var(--bg-secondary);">${upperKey[i]}</th>`;
      }
      gridHtml += '</tr></thead><tbody>';
      for (let row = 0; row < numRows; row++) {
        gridHtml += '<tr><td style="border: 1px solid var(--border-color); padding: 0.5rem; background: var(--bg-secondary); font-weight: bold;">Row ' + (row + 1) + '</td>';
        for (let col = 0; col < keyLength; col++) {
          gridHtml += `<td style="border: 1px solid var(--border-color); padding: 0.5rem; text-align: center;">${grid[row][col] || ''}</td>`;
        }
        gridHtml += '</tr>';
      }
      gridHtml += '</tbody></table>';
      
      steps.push({
        html: gridHtml,
        delay: 1000
      });

      steps.push({
        html: `<p><strong>Step 4:</strong> Reading row by row</p>`,
        delay: 500
      });

      let result = '';
      for (let row = 0; row < numRows; row++) {
        for (let col = 0; col < keyLength; col++) {
          if (grid[row][col]) {
            result += grid[row][col];
          }
        }
      }

      result = result.replace(/X+$/, '');
      steps.push({
        html: `<p><strong>Result:</strong> ${result}</p>`,
        delay: 500
      });
    }

    return steps;
  }
}

