// Rail Fence Cipher Algorithm Implementation

class RailFenceCipher {
  constructor() {
    this.name = 'Rail Fence Cipher';
  }

  validateInput(text) {
    return /^[A-Za-z\s]+$/.test(text);
  }

  validateKey(key) {
    const rails = parseInt(key);
    return !isNaN(rails) && rails >= 2 && rails <= 10;
  }

  encrypt(plaintext, rails) {
    if (!plaintext) return '';
    const numRails = parseInt(rails);
    if (isNaN(numRails) || numRails < 2 || numRails > 10) {
      throw new Error('Number of rails must be between 2 and 10');
    }
    
    const text = plaintext.replace(/\s/g, '').toUpperCase();
    if (text.length === 0) return '';
    
    const fence = Array(numRails).fill(null).map(() => []);
    
    let direction = 1;
    let rail = 0;
    
    for (let i = 0; i < text.length; i++) {
      fence[rail].push(text[i]);
      rail += direction;
      
      if (rail === 0 || rail === numRails - 1) {
        direction *= -1;
      }
    }
    
    return fence.map(row => row.join('')).join('');
  }

  decrypt(ciphertext, rails) {
    if (!ciphertext) return '';
    const numRails = parseInt(rails);
    if (isNaN(numRails) || numRails < 2 || numRails > 10) {
      throw new Error('Number of rails must be between 2 and 10');
    }
    
    const text = ciphertext.replace(/\s/g, '').toUpperCase();
    if (text.length === 0) return '';
    
    // Step 1: Determine which rail each position belongs to (zigzag pattern)
    const positions = [];
    let direction = 1;
    let rail = 0;
    
    for (let i = 0; i < text.length; i++) {
      positions.push(rail);
      rail += direction;
      
      if (rail === 0 || rail === numRails - 1) {
        direction *= -1;
      }
    }
    
    // Step 2: Count how many characters go to each rail
    const railCounts = Array(numRails).fill(0);
    for (let i = 0; i < positions.length; i++) {
      railCounts[positions[i]]++;
    }
    
    // Step 3: Fill fence with characters in order
    const fence = Array(numRails).fill(null).map(() => []);
    let charIndex = 0;
    for (let r = 0; r < numRails; r++) {
      for (let i = 0; i < railCounts[r]; i++) {
        fence[r].push(text[charIndex++]);
      }
    }
    
    // Step 4: Read in zigzag pattern to reconstruct original
    let result = '';
    direction = 1;
    rail = 0;
    const railIndices = Array(numRails).fill(0);
    
    for (let i = 0; i < text.length; i++) {
      result += fence[rail][railIndices[rail]++];
      rail += direction;
      
      if (rail === 0 || rail === numRails - 1) {
        direction *= -1;
      }
    }
    
    return result;
  }

  getVisualizationSteps(text, key, mode = 'encrypt') {
    const steps = [];
    const numRails = parseInt(key);
    const cleanText = text.replace(/\s/g, '').toUpperCase();
    const isEncrypt = mode === 'encrypt';

    steps.push({
      html: `<p><strong>Step 1:</strong> ${isEncrypt ? 'Encryption' : 'Decryption'} using ${numRails} rails</p>`,
      delay: 500
    });

    steps.push({
      html: `<p><strong>Text:</strong> ${cleanText}</p>`,
      delay: 500
    });

    if (isEncrypt) {
      const fence = Array(numRails).fill(null).map(() => []);
      let direction = 1;
      let rail = 0;
      
      // Show zigzag pattern
      let patternHtml = '<p><strong>Zigzag Pattern:</strong></p><pre style="font-family: monospace; line-height: 1.5;">';
      for (let r = 0; r < numRails; r++) {
        patternHtml += 'Rail ' + (r + 1) + ': ';
        for (let i = 0; i < cleanText.length; i++) {
          patternHtml += ' ';
        }
        patternHtml += '\n';
      }
      patternHtml += '</pre>';
      
      steps.push({
        html: patternHtml,
        delay: 800
      });

      // Fill fence
      for (let i = 0; i < cleanText.length; i++) {
        fence[rail].push(cleanText[i]);
        
        let railHtml = '<p><strong>Position ' + (i + 1) + ':</strong> Character "' + cleanText[i] + '" → Rail ' + (rail + 1) + '</p>';
        railHtml += '<pre style="font-family: monospace; line-height: 1.5;">';
        for (let r = 0; r < numRails; r++) {
          railHtml += 'Rail ' + (r + 1) + ': ';
          for (let j = 0; j < fence[r].length; j++) {
            railHtml += fence[r][j] + ' ';
          }
          railHtml += '\n';
        }
        railHtml += '</pre>';
        
        steps.push({
          html: railHtml,
          delay: 1000
        });
        
        rail += direction;
        if (rail === 0 || rail === numRails - 1) {
          direction *= -1;
        }
      }

      const result = fence.map(row => row.join('')).join('');
      steps.push({
        html: `<p><strong>Step 2:</strong> Reading rail by rail</p>`,
        delay: 500
      });
      
      steps.push({
        html: `<p><strong>Result:</strong> ${result}</p>`,
        delay: 500
      });
    } else {
      // Decryption visualization
      steps.push({
        html: `<p><strong>Step 2:</strong> Distributing characters to rails</p>`,
        delay: 500
      });

      const fence = Array(numRails).fill(null).map(() => []);
      const positions = [];
      let direction = 1;
      let rail = 0;
      
      for (let i = 0; i < cleanText.length; i++) {
        positions.push(rail);
        rail += direction;
        if (rail === 0 || rail === numRails - 1) {
          direction *= -1;
        }
      }

      let charIndex = 0;
      for (let r = 0; r < numRails; r++) {
        const count = positions.filter(p => p === r).length;
        for (let i = 0; i < count; i++) {
          fence[r].push(cleanText[charIndex++]);
        }
      }

      let fenceHtml = '<pre style="font-family: monospace; line-height: 1.5;">';
      for (let r = 0; r < numRails; r++) {
        fenceHtml += 'Rail ' + (r + 1) + ': ' + fence[r].join(' ') + '\n';
      }
      fenceHtml += '</pre>';
      
      steps.push({
        html: fenceHtml,
        delay: 1000
      });

      steps.push({
        html: `<p><strong>Step 3:</strong> Reading in zigzag pattern</p>`,
        delay: 500
      });

      let result = '';
      direction = 1;
      rail = 0;
      const railIndices = Array(numRails).fill(0);
      
      for (let i = 0; i < cleanText.length; i++) {
        result += fence[rail][railIndices[rail]++];
        rail += direction;
        if (rail === 0 || rail === numRails - 1) {
          direction *= -1;
        }
      }

      steps.push({
        html: `<p><strong>Result:</strong> ${result}</p>`,
        delay: 500
      });
    }

    return steps;
  }
}

