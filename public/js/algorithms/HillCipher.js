// Hill Cipher Algorithm Implementation

class HillCipher {
  constructor() {
    this.name = 'Hill Cipher';
    this.modulus = 26;
  }

  validateInput(text) {
    return /^[A-Za-z\s]+$/.test(text);
  }

  validateKey(key, size) {
    const matrix = this.parseMatrix(key, size);
    if (!matrix) return false;
    
    // Check if matrix is invertible (gcd(det, 26) = 1)
    const det = this.determinant(matrix);
    const detMod = ((det % this.modulus) + this.modulus) % this.modulus;
    return this.gcd(detMod, this.modulus) === 1;
  }

  parseMatrix(keyString, size) {
    const values = keyString.trim().split(/\s+/).map(v => parseInt(v));
    if (values.length !== size * size) return null;
    if (values.some(v => isNaN(v))) return null;
    
    const matrix = [];
    for (let i = 0; i < size; i++) {
      matrix.push(values.slice(i * size, (i + 1) * size));
    }
    return matrix;
  }

  determinant(matrix) {
    if (matrix.length === 2) {
      return matrix[0][0] * matrix[1][1] - matrix[0][1] * matrix[1][0];
    } else if (matrix.length === 3) {
      return matrix[0][0] * (matrix[1][1] * matrix[2][2] - matrix[1][2] * matrix[2][1]) -
             matrix[0][1] * (matrix[1][0] * matrix[2][2] - matrix[1][2] * matrix[2][0]) +
             matrix[0][2] * (matrix[1][0] * matrix[2][1] - matrix[1][1] * matrix[2][0]);
    }
    return 0;
  }

  gcd(a, b) {
    while (b !== 0) {
      const temp = b;
      b = a % b;
      a = temp;
    }
    return a;
  }

  modInverse(a, m) {
    a = ((a % m) + m) % m;
    for (let x = 1; x < m; x++) {
      if ((a * x) % m === 1) return x;
    }
    return null;
  }

  matrixInverse(matrix) {
    const det = this.determinant(matrix);
    const detMod = ((det % this.modulus) + this.modulus) % this.modulus;
    const detInv = this.modInverse(detMod, this.modulus);
    
    if (!detInv) return null;
    
    if (matrix.length === 2) {
      const adj = [
        [matrix[1][1], -matrix[0][1]],
        [-matrix[1][0], matrix[0][0]]
      ];
      return adj.map(row => 
        row.map(val => {
          const modVal = ((val * detInv) % this.modulus + this.modulus) % this.modulus;
          return modVal;
        })
      );
    } else if (matrix.length === 3) {
      // Cofactor matrix
      const cofactor = [];
      for (let i = 0; i < 3; i++) {
        cofactor[i] = [];
        for (let j = 0; j < 3; j++) {
          const minor = [];
          for (let x = 0; x < 3; x++) {
            if (x !== i) {
              const row = [];
              for (let y = 0; y < 3; y++) {
                if (y !== j) row.push(matrix[x][y]);
              }
              minor.push(row);
            }
          }
          const sign = (i + j) % 2 === 0 ? 1 : -1;
          cofactor[i][j] = sign * this.determinant(minor);
        }
      }
      // Transpose and multiply by inverse determinant
      const adj = [];
      for (let i = 0; i < 3; i++) {
        adj[i] = [];
        for (let j = 0; j < 3; j++) {
          adj[i][j] = cofactor[j][i];
        }
      }
      return adj.map(row => 
        row.map(val => {
          const modVal = ((val * detInv) % this.modulus + this.modulus) % this.modulus;
          return modVal;
        })
      );
    }
    return null;
  }

  textToNumbers(text) {
    return text.toUpperCase().replace(/\s/g, '').split('').map(c => c.charCodeAt(0) - 65);
  }

  numbersToText(numbers) {
    return numbers.map(n => {
      // Ensure positive modulo result
      const modVal = ((n % this.modulus) + this.modulus) % this.modulus;
      return String.fromCharCode(modVal + 65);
    }).join('');
  }

  matrixMultiply(matrix, vector) {
    const result = [];
    for (let i = 0; i < matrix.length; i++) {
      let sum = 0;
      for (let j = 0; j < vector.length; j++) {
        sum += matrix[i][j] * vector[j];
      }
      // Proper modulo operation to handle negative numbers
      result.push(((sum % this.modulus) + this.modulus) % this.modulus);
    }
    return result;
  }

  encrypt(plaintext, key, size) {
    if (!plaintext || !key) return '';
    const blockSize = parseInt(size);
    if (isNaN(blockSize) || (blockSize !== 2 && blockSize !== 3)) {
      throw new Error('Matrix size must be 2 or 3');
    }
    
    const matrix = this.parseMatrix(key, blockSize);
    if (!matrix) {
      throw new Error('Invalid key matrix format');
    }
    
    // Validate matrix is invertible
    if (!this.validateKey(key, blockSize)) {
      throw new Error('Matrix is not invertible (determinant must be coprime with 26)');
    }
    
    const numbers = this.textToNumbers(plaintext);
    if (numbers.length === 0) return '';
    
    // Pad if necessary
    const paddedNumbers = [...numbers];
    while (paddedNumbers.length % blockSize !== 0) {
      paddedNumbers.push(23); // X
    }
    
    let result = [];
    for (let i = 0; i < paddedNumbers.length; i += blockSize) {
      const block = paddedNumbers.slice(i, i + blockSize);
      const encrypted = this.matrixMultiply(matrix, block);
      result = result.concat(encrypted);
    }
    
    return this.numbersToText(result);
  }

  decrypt(ciphertext, key, size) {
    if (!ciphertext || !key) return '';
    const blockSize = parseInt(size);
    if (isNaN(blockSize) || (blockSize !== 2 && blockSize !== 3)) {
      throw new Error('Matrix size must be 2 or 3');
    }
    
    const matrix = this.parseMatrix(key, blockSize);
    if (!matrix) {
      throw new Error('Invalid key matrix format');
    }
    
    const invMatrix = this.matrixInverse(matrix);
    if (!invMatrix) {
      throw new Error('Matrix is not invertible (determinant must be coprime with 26)');
    }
    
    const numbers = this.textToNumbers(ciphertext);
    if (numbers.length === 0) return '';
    
    // Ciphertext length must be multiple of block size
    if (numbers.length % blockSize !== 0) {
      throw new Error(`Ciphertext length (${numbers.length}) must be a multiple of block size (${blockSize})`);
    }
    
    let result = [];
    for (let i = 0; i < numbers.length; i += blockSize) {
      const block = numbers.slice(i, i + blockSize);
      const decrypted = this.matrixMultiply(invMatrix, block);
      result = result.concat(decrypted);
    }
    
    return this.numbersToText(result);
  }

  generateRandomKey(size) {
    let matrix;
    let det;
    do {
      matrix = [];
      for (let i = 0; i < size; i++) {
        matrix[i] = [];
        for (let j = 0; j < size; j++) {
          matrix[i][j] = Math.floor(Math.random() * 26);
        }
      }
      det = this.determinant(matrix);
      const detMod = ((det % this.modulus) + this.modulus) % this.modulus;
    } while (this.gcd(detMod, this.modulus) !== 1);
    
    return matrix.flat().join(' ');
  }

  getVisualizationSteps(text, key, mode = 'encrypt', size = 2) {
    const steps = [];
    const matrix = this.parseMatrix(key, size);
    const isEncrypt = mode === 'encrypt';

    steps.push({
      html: `<p><strong>Step 1:</strong> ${size}×${size} Key Matrix</p>`,
      delay: 500
    });

    // Display matrix
    let matrixHtml = '<table style="border-collapse: collapse; margin: 1rem 0;"><tbody>';
    for (let row = 0; row < size; row++) {
      matrixHtml += '<tr>';
      for (let col = 0; col < size; col++) {
        matrixHtml += `<td style="border: 1px solid var(--border-color); padding: 0.5rem; text-align: center; width: 50px;">${matrix[row][col]}</td>`;
      }
      matrixHtml += '</tr>';
    }
    matrixHtml += '</tbody></table>';
    
    steps.push({
      html: matrixHtml,
      delay: 1000
    });

    if (!isEncrypt) {
      const invMatrix = this.matrixInverse(matrix);
      steps.push({
        html: `<p><strong>Step 2:</strong> Inverse Matrix (for decryption)</p>`,
        delay: 500
      });
      
      let invMatrixHtml = '<table style="border-collapse: collapse; margin: 1rem 0;"><tbody>';
      for (let row = 0; row < size; row++) {
        invMatrixHtml += '<tr>';
        for (let col = 0; col < size; col++) {
          invMatrixHtml += `<td style="border: 1px solid var(--border-color); padding: 0.5rem; text-align: center; width: 50px;">${invMatrix[row][col]}</td>`;
        }
        invMatrixHtml += '</tr>';
      }
      invMatrixHtml += '</tbody></table>';
      
      steps.push({
        html: invMatrixHtml,
        delay: 1000
      });
    }

    const numbers = this.textToNumbers(text);
    const blockSize = parseInt(size);
    
    // Pad if necessary
    const paddedNumbers = [...numbers];
    while (paddedNumbers.length % blockSize !== 0) {
      paddedNumbers.push(23);
    }

    steps.push({
      html: `<p><strong>Step ${isEncrypt ? 2 : 3}:</strong> Convert text to numbers: ${numbers.join(', ')}${paddedNumbers.length > numbers.length ? ' (padded with X)' : ''}</p>`,
      delay: 800
    });

    let result = [];
    for (let i = 0; i < paddedNumbers.length; i += blockSize) {
      const block = paddedNumbers.slice(i, i + blockSize);
      const workMatrix = isEncrypt ? matrix : this.matrixInverse(matrix);
      
      // Show detailed matrix multiplication
      steps.push({
        html: `<p><strong>Block ${i/blockSize + 1}:</strong> Processing input vector [${block.join(', ')}]</p>`,
        delay: 800
      });
      
      // Show matrix multiplication step by step
      let multiplicationHtml = '<div class="my-4"><p class="mb-2 font-semibold">Matrix Multiplication:</p>';
      multiplicationHtml += '<div class="flex items-center justify-center gap-4 flex-wrap">';
      
      // Matrix
      multiplicationHtml += '<div class="flex flex-col items-center">';
      multiplicationHtml += '<span class="text-xs text-gray-400 mb-1">Key Matrix</span>';
      multiplicationHtml += '<table class="border-collapse"><tbody>';
      for (let row = 0; row < size; row++) {
        multiplicationHtml += '<tr>';
        for (let col = 0; col < size; col++) {
          multiplicationHtml += `<td class="p-2 border border-gray-600 text-center bg-gray-700 font-mono">${workMatrix[row][col]}</td>`;
        }
        multiplicationHtml += '</tr>';
      }
      multiplicationHtml += '</tbody></table>';
      multiplicationHtml += '</div>';
      
      // Multiplication sign
      multiplicationHtml += '<span class="text-2xl text-purple-400 font-bold">×</span>';
      
      // Input vector
      multiplicationHtml += '<div class="flex flex-col items-center">';
      multiplicationHtml += '<span class="text-xs text-gray-400 mb-1">Input Vector</span>';
      multiplicationHtml += '<table class="border-collapse"><tbody>';
      for (let j = 0; j < blockSize; j++) {
        multiplicationHtml += `<tr><td class="p-2 border border-gray-600 text-center bg-blue-700 font-mono font-bold">${block[j]}</td></tr>`;
      }
      multiplicationHtml += '</tbody></table>';
      multiplicationHtml += '</div>';
      
      // Equals sign
      multiplicationHtml += '<span class="text-2xl text-gray-400 font-bold">=</span>';
      
      // Calculate and show result
      const processed = this.matrixMultiply(workMatrix, block);
      result = result.concat(processed);
      
      // Show calculation details
      let calcDetails = '<div class="mt-4 p-3 bg-gray-900 rounded border border-gray-700">';
      calcDetails += '<p class="text-sm font-semibold mb-2 text-blue-400">Calculation Details:</p>';
      for (let row = 0; row < size; row++) {
        let calc = [];
        let sum = 0;
        for (let col = 0; col < size; col++) {
          calc.push(`(${workMatrix[row][col]} × ${block[col]})`);
          sum += workMatrix[row][col] * block[col];
        }
        const modResult = ((sum % this.modulus) + this.modulus) % this.modulus;
        calcDetails += `<p class="text-xs font-mono mb-1">Row ${row + 1}: ${calc.join(' + ')} = ${sum} (mod 26) = <span class="text-green-400 font-bold">${modResult}</span></p>`;
      }
      calcDetails += '</div>';
      
      // Output vector
      multiplicationHtml += '<div class="flex flex-col items-center">';
      multiplicationHtml += '<span class="text-xs text-gray-400 mb-1">Output Vector</span>';
      multiplicationHtml += '<table class="border-collapse"><tbody>';
      for (let j = 0; j < blockSize; j++) {
        multiplicationHtml += `<tr><td class="p-2 border border-gray-600 text-center bg-green-700 font-mono font-bold">${processed[j]}</td></tr>`;
      }
      multiplicationHtml += '</tbody></table>';
      multiplicationHtml += '</div>';
      
      multiplicationHtml += '</div>';
      multiplicationHtml += calcDetails;
      multiplicationHtml += '</div>';
      
      steps.push({
        html: multiplicationHtml,
        delay: 1500
      });
    }

    const resultText = this.numbersToText(result);
    steps.push({
      html: `<p><strong>Result:</strong> ${resultText}</p>`,
      delay: 500
    });

    return steps;
  }
}

