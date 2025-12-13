// AJAX-based cryptography operations
// Replaces client-side algorithm logic with server-side processing

function executeCryptoAjax(algorithm, mode, inputField, keyField, outputField, vizContent, extraData = {}) {
  const input = inputField.value.trim();
  let key = keyField ? keyField.value.trim() : '';
  
  // Validate input
  if (!input) {
    showError('Please enter text to process.');
    return;
  }
  
  // Special handling for Hill Cipher - get matrix from inputs
  if (algorithm === 'hill') {
    const sizeSelectId = mode === 'encrypt' ? 'hill-matrix-size' : 'hill-matrix-size-decrypt';
    const containerId = mode === 'encrypt' ? 'matrix-input-container' : 'matrix-input-container-decrypt';
    
    const sizeSelect = document.getElementById(sizeSelectId);
    if (!sizeSelect) {
      showError('Hill Cipher matrix size selector not found.');
      return;
    }
    const size = parseInt(sizeSelect.value);
    if (isNaN(size) || (size !== 2 && size !== 3)) {
      showError('Invalid Hill Cipher matrix size selected.');
      return;
    }
    
    // Get matrix values from input fields
    const container = document.getElementById(containerId);
    if (container) {
      const inputs = container.querySelectorAll('input[type="number"]');
      if (inputs.length === size * size) {
        const values = Array.from(inputs).map(inp => {
          const val = parseInt(inp.value);
          if (isNaN(val) || val < 0 || val > 25) {
            return '0';
          }
          return val.toString();
        });
        key = values.join(' ');
        extraData.size = size;
      } else {
        showError(`Please fill all ${size * size} matrix cells with values between 0-25.`);
        return;
      }
    } else {
      if (!key) {
        showError('Please enter the key matrix values.');
        return;
      }
    }
  }
  
  // Validate key for non-Hill ciphers
  if (!key && algorithm !== 'hill') {
    showError('Please enter a key.');
    return;
  }
  
  // Show loading state
  outputField.value = 'Processing...';
  outputField.disabled = true;
  
  // Prepare request data
  const requestData = {
    text: input,
    key: key,
    _token: document.querySelector('meta[name="csrf-token"]')?.content
  };
  
  // Add extra data (e.g., size for Hill Cipher)
  Object.assign(requestData, extraData);
  
  // Determine route based on algorithm and mode
  const routeName = `${algorithm}.process.${mode === 'encrypt' ? 'encrypt' : 'decrypt'}`;
  const routeUrl = getRouteUrl(routeName);
  
  // Make AJAX request
  fetch(routeUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': requestData._token
    },
    body: JSON.stringify(requestData)
  })
  .then(response => response.json())
  .then(data => {
    outputField.disabled = false;
    
    if (data.success) {
      outputField.value = data.result;
      
      // Update visualization if enabled
      if (vizContent && !vizContent.classList.contains('hidden') && data.visualization) {
        renderVisualization(vizContent, data.visualization, algorithm);
      }
    } else {
      showError(data.error || 'An error occurred during processing.');
      outputField.value = '';
    }
  })
  .catch(error => {
    outputField.disabled = false;
    outputField.value = '';
    showError('Network error: ' + error.message);
    console.error('Crypto AJAX error:', error);
  });
}

// Helper function to get route URL
function getRouteUrl(routeName) {
  // Map route names to URLs based on Laravel route structure
  const routeMap = {
    'caesar.process.encrypt': '/caesar/encrypt',
    'caesar.process.decrypt': '/caesar/decrypt',
    'hill.process.encrypt': '/hill/encrypt',
    'hill.process.decrypt': '/hill/decrypt',
    'rail-fence.process.encrypt': '/rail-fence/encrypt',
    'rail-fence.process.decrypt': '/rail-fence/decrypt',
    'polyalphabetic.process.encrypt': '/polyalphabetic/encrypt',
    'polyalphabetic.process.decrypt': '/polyalphabetic/decrypt',
    'one-time-pad.process.encrypt': '/one-time-pad/encrypt',
    'one-time-pad.process.decrypt': '/one-time-pad/decrypt',
    'monoalphabetic.process.encrypt': '/monoalphabetic/encrypt',
    'monoalphabetic.process.decrypt': '/monoalphabetic/decrypt',
    'playfair.process.encrypt': '/playfair/encrypt',
    'playfair.process.decrypt': '/playfair/decrypt',
    'row-column-transposition.process.encrypt': '/row-column-transposition/encrypt',
    'row-column-transposition.process.decrypt': '/row-column-transposition/decrypt'
  };
  
  return routeMap[routeName] || '#';
}

