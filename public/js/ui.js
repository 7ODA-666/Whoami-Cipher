// CipherViz - UI Interaction Functions (Updated for Tailwind CSS)

// Initialize UI on page load
document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
  initThemeToggle();
  initTabs();
  initCopyButtons();
  initClearButtons();
  initVisualizationToggle();
});

// Sidebar Toggle
function initSidebar() {
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('toggle-sidebar');
  const mainContent = document.getElementById('main-content');

  if (!sidebar || !toggleBtn) return;

  function isMobile() {
    return window.innerWidth < 1024; // lg breakpoint
  }

  function closeSidebar() {
    if (isMobile()) {
      // On mobile: hide off-screen completely
      sidebar.classList.add('mobile-hidden');
      sidebar.classList.remove('collapsed');

      if (mainContent) {
        mainContent.classList.remove('sidebar-expanded', 'sidebar-collapsed');
      }

      // Remove overlay if exists
      const overlay = document.getElementById('sidebar-overlay');
      if (overlay) overlay.remove();
    } else {
      // On desktop: collapse to icons only (NOT hidden)
      sidebar.classList.add('collapsed');
      sidebar.classList.remove('mobile-hidden');

      if (mainContent) {
        mainContent.classList.remove('sidebar-expanded');
        mainContent.classList.add('sidebar-collapsed');
      }
    }

    localStorage.setItem('sidebarCollapsed', 'true');
  }

  function openSidebar() {
    if (isMobile()) {
      // On mobile: show sidebar at full width
      sidebar.classList.remove('mobile-hidden', 'collapsed');

      if (mainContent) {
        mainContent.classList.remove('sidebar-expanded', 'sidebar-collapsed');
      }

      // Add overlay on mobile
      const overlay = document.createElement('div');
      overlay.id = 'sidebar-overlay';
      overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden';
      overlay.addEventListener('click', closeSidebar);
      document.body.appendChild(overlay);
    } else {
      // On desktop: expand to full width
      sidebar.classList.remove('collapsed', 'mobile-hidden');

      if (mainContent) {
        mainContent.classList.remove('sidebar-collapsed');
        mainContent.classList.add('sidebar-expanded');
      }
    }

    localStorage.setItem('sidebarCollapsed', 'false');
  }

  function toggleSidebar() {
    // Check if sidebar is collapsed/hidden
    const isCollapsed = sidebar.classList.contains('collapsed') ||
                        sidebar.classList.contains('mobile-hidden');

    if (isCollapsed) {
      openSidebar();
    } else {
      closeSidebar();
    }
  }

  // Initialize sidebar state on page load
  // Clean up any existing classes first to prevent conflicts
  sidebar.classList.remove('collapsed', 'mobile-hidden');
  if (mainContent) {
    mainContent.classList.remove('sidebar-expanded', 'sidebar-collapsed');
  }

  const savedState = localStorage.getItem('sidebarCollapsed');

  if (isMobile()) {
    // Mobile: hide by default
    closeSidebar();
  } else {
    // Desktop: show expanded or collapsed based on saved state
    if (savedState === 'true') {
      closeSidebar(); // Will collapse to icons
    } else {
      openSidebar(); // Will show fully expanded
    }
  }

  toggleBtn.addEventListener('click', toggleSidebar);

  // Handle window resize - maintain appropriate state
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

      if (isCollapsed) {
        closeSidebar();
      } else {
        openSidebar();
      }
    }, 250);
  });
}

// Theme Toggle - Fully functional light/dark mode
function initThemeToggle() {
  const themeToggle = document.getElementById('theme-toggle');
  const html = document.documentElement;

  // Get saved theme or default to dark
  const savedTheme = localStorage.getItem('theme') || 'dark';
  html.classList.toggle('dark', savedTheme === 'dark');

  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      const isDark = html.classList.contains('dark');
      const newTheme = isDark ? 'light' : 'dark';

      html.classList.toggle('dark', newTheme === 'dark');
      localStorage.setItem('theme', newTheme);

      // Update icon
      updateThemeIcon(themeToggle, newTheme);
    });

    // Set initial icon
    updateThemeIcon(themeToggle, savedTheme);
  }
}

function updateThemeIcon(button, theme) {
  const icon = button.querySelector('#theme-icon');
  if (icon) {
    if (theme === 'dark') {
      icon.className = 'fas fa-moon text-xl';
    } else {
      icon.className = 'fas fa-sun text-xl';
    }
  }
}

// Tab Navigation
function initTabs() {
  // Find tabs - try multiple selectors to ensure we catch them
  const tabs = document.querySelectorAll('.tab[data-target], button.tab[data-target], [data-target].tab');
  const tabContents = document.querySelectorAll('[id$="-tab"]');

  if (tabs.length === 0 || tabContents.length === 0) {
    console.warn('Tabs or tab contents not found');
    return;
  }

  // Initialize: hide all tab contents except the one marked as active
  tabContents.forEach(tc => {
    if (!tc.classList.contains('block')) {
      tc.classList.add('hidden');
      tc.classList.remove('block');
    }
  });

  // Function to update tab styles
  function updateTabStyles(activeTab) {
    tabs.forEach(t => {
      if (t === activeTab) {
        // Active style: gradient background, white text, shadow
        t.classList.remove('bg-gray-700', 'text-gray-300', 'border-gray-600');
        t.classList.add('active-tab', 'bg-gradient-to-r', 'from-blue-600', 'to-purple-600', 'text-white', 'shadow-lg', 'hover:shadow-xl', 'border-transparent');
      } else {
        // Inactive style: gray background, gray text
        t.classList.remove('active-tab', 'bg-gradient-to-r', 'from-blue-600', 'to-purple-600', 'text-white', 'shadow-lg', 'hover:shadow-xl', 'border-transparent');
        t.classList.add('bg-gray-700', 'text-gray-300', 'border-gray-600');
      }
    });
  }

  // Initialize active tab styling based on current state
  let foundActive = false;
  tabs.forEach(tab => {
    const targetId = tab.getAttribute('data-target');
    const targetContent = document.getElementById(targetId);
    const isActive = targetContent && targetContent.classList.contains('block') && !targetContent.classList.contains('hidden');

    if (isActive && !foundActive) {
      updateTabStyles(tab);
      foundActive = true;
    }
  });

  // If no active tab found, activate the first one
  if (!foundActive && tabs.length > 0) {
    const firstTab = tabs[0];
    const firstTargetId = firstTab.getAttribute('data-target');
    const firstTargetContent = document.getElementById(firstTargetId);
    if (firstTargetContent) {
      firstTargetContent.classList.remove('hidden');
      firstTargetContent.classList.add('block');
      updateTabStyles(firstTab);
    }
  }

  // Add click event listeners - use event delegation for reliability
  const tabsContainer = document.querySelector('.flex.gap-3.mb-6');
  if (tabsContainer) {
    tabsContainer.addEventListener('click', (e) => {
      const tab = e.target.closest('.tab[data-target]');
      if (!tab) return;

      e.preventDefault();
      e.stopPropagation();

      const targetId = tab.getAttribute('data-target');
      if (!targetId) {
        console.warn('Tab missing data-target attribute');
        return;
      }

      const targetContent = document.getElementById(targetId);
      if (!targetContent) {
        console.warn('Tab target not found:', targetId);
        return;
      }

      // Hide all tab contents
      tabContents.forEach(tc => {
        tc.classList.remove('block');
        tc.classList.add('hidden');
      });

      // Show target content
      targetContent.classList.remove('hidden');
      targetContent.classList.add('block');

      // Update tab styles
      updateTabStyles(tab);
    });
  } else {
    // Fallback: direct event listeners
    tabs.forEach(tab => {
      tab.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        const targetId = tab.getAttribute('data-target');
        if (!targetId) {
          console.warn('Tab missing data-target attribute');
          return;
        }

        const targetContent = document.getElementById(targetId);
        if (!targetContent) {
          console.warn('Tab target not found:', targetId);
          return;
        }

        // Hide all tab contents
        tabContents.forEach(tc => {
          tc.classList.remove('block');
          tc.classList.add('hidden');
        });

        // Show target content
        targetContent.classList.remove('hidden');
        targetContent.classList.add('block');

        // Update tab styles
        updateTabStyles(tab);
      });
    });
  }
}

// Copy to Clipboard
function initCopyButtons() {
  const copyButtons = document.querySelectorAll('.copy-btn');

  copyButtons.forEach(btn => {
    btn.addEventListener('click', async () => {
      const outputField = btn.closest('.bg-gray-800')?.querySelector('textarea[readonly]') ||
                         btn.parentElement?.previousElementSibling?.querySelector('textarea[readonly]');
      if (outputField && outputField.value) {
        try {
          await navigator.clipboard.writeText(outputField.value);
          showCopyNotification();
        } catch (err) {
          // Fallback for older browsers
          outputField.select();
          document.execCommand('copy');
          showCopyNotification();
        }
      }
    });
  });
}

function showCopyNotification() {
  // Remove existing notification if any
  const existing = document.querySelector('.copy-notification');
  if (existing) {
    existing.remove();
  }

  // Create notification
  const notification = document.createElement('div');
  notification.className = 'copy-notification fixed bottom-8 right-8 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg shadow-2xl z-50 opacity-0 transform translate-y-4 transition-all duration-300';
  notification.textContent = 'Copied to clipboard!';
  document.body.appendChild(notification);

  // Show notification
  setTimeout(() => {
    notification.classList.remove('opacity-0', 'translate-y-4');
    notification.classList.add('opacity-100', 'translate-y-0');
  }, 10);

  // Hide and remove notification after 2 seconds
  setTimeout(() => {
    notification.classList.remove('opacity-100', 'translate-y-0');
    notification.classList.add('opacity-0', 'translate-y-4');
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, 2000);
}

// Clear All Fields
function initClearButtons() {
  const clearButtons = document.querySelectorAll('.clear-btn');

  clearButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      // Find the appropriate container - try multiple approaches
      let container = btn.closest('[id$="-tab"]'); // Old approach for backward compatibility

      if (!container) {
        // New approach: find container at the document level for algorithm pages
        container = document.querySelector('#main-content') || document.body;
      }

      if (container) {
        // Clear all input fields (text, number, password, etc.)
        const inputs = container.querySelectorAll('input, textarea');
        inputs.forEach(input => {
          if (input.type !== 'checkbox' &&
              input.type !== 'radio' &&
              !input.disabled &&
              !input.readOnly &&
              !input.classList.contains('viz-toggle')) {
            input.value = '';
          }
        });

        // Reset all select elements to their first option
        const selects = container.querySelectorAll('select');
        selects.forEach(select => {
          if (!select.disabled) {
            select.selectedIndex = 0;
            // Trigger change event in case other scripts depend on it
            select.dispatchEvent(new Event('change', { bubbles: true }));
          }
        });

        // Clear visualization content completely
        const vizContents = container.querySelectorAll('.visualization-content');
        vizContents.forEach(vizContent => {
          vizContent.innerHTML = '';
          // Remove any dynamic classes that might have been added
          vizContent.className = 'visualization-content min-h-[200px] p-4 bg-gray-900 rounded-lg border border-gray-700';
        });

        // Clear any dynamically generated matrix input containers (for Hill cipher)
        const matrixContainers = container.querySelectorAll('[id*="matrix-input-container"]');
        matrixContainers.forEach(matrixContainer => {
          matrixContainer.innerHTML = '';
        });

        // Reset any error messages or success notifications
        const errorElements = container.querySelectorAll('.error-message, .success-message, .alert');
        errorElements.forEach(el => {
          el.remove();
        });

        // Remove any fixed position notifications (error, success, etc.)
        const notifications = document.querySelectorAll('.error-notification, .success-notification, .notification');
        notifications.forEach(notification => {
          notification.remove();
        });

        // Clear any temporary state or data attributes
        const elementsWithData = container.querySelectorAll('[data-temp], [data-step], [data-animation]');
        elementsWithData.forEach(el => {
          // Remove temporary data attributes
          Object.keys(el.dataset).forEach(key => {
            if (key.startsWith('temp') || key.startsWith('step') || key.startsWith('animation')) {
              delete el.dataset[key];
            }
          });
        });
      }
    });
  });
}

// Visualization Toggle
function initVisualizationToggle() {
  const vizToggles = document.querySelectorAll('.viz-toggle');

  vizToggles.forEach(toggle => {
    toggle.addEventListener('change', (e) => {
      const vizSection = toggle.closest('.bg-gray-800');
      const vizContent = vizSection?.querySelector('.visualization-content');

      if (vizContent) {
        if (e.target.checked) {
          vizContent.classList.remove('hidden');
          vizContent.classList.add('block');
        } else {
          vizContent.classList.remove('block');
          vizContent.classList.add('hidden');
        }
      }
    });
  });
}

// Execute encryption/decryption
function executeCrypto(algorithmClass, mode, inputField, keyField, outputField, vizContent, extraParam = null) {
  const input = inputField.value.trim();
  let key = keyField.value.trim();

  // Validate input
  if (!input) {
    showError('Please enter text to process.');
    return;
  }

  // Special handling for Hill Cipher - get matrix from inputs
  const algorithm = new algorithmClass();
  if (algorithm.name === 'Hill Cipher') {
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
      } else {
        showError(`Please fill all ${size * size} matrix cells with values between 0-25.`);
        return;
      }
    } else {
      // Fallback to text input if container not found
      if (!key) {
        showError('Please enter the key matrix values.');
        return;
      }
    }

    extraParam = size;
  }

  // Special handling for OTP - key must match text length
  if (algorithm.name === 'One-Time Pad (OTP)') {
    const textLength = input.replace(/\s/g, '').length;
    if (!key || key.replace(/\s/g, '').length !== textLength) {
      showError(`Key must be exactly ${textLength} characters long (same as text length without spaces).`);
      return;
    }
  }

  // Validate key (algorithm-specific validation will be done in the class)
  if (!key && algorithm.name !== 'One-Time Pad (OTP)') {
    showError('Please enter a key.');
    return;
  }

  try {
    // Validate input and key
    if (!algorithm.validateInput(input)) {
      showError('Invalid input. Please enter only letters (A-Z, a-z) and spaces.');
      return;
    }

    // Special validation for OTP
    if (algorithm.name === 'One-Time Pad (OTP)') {
      if (!algorithm.validateKey(key, input)) {
        showError('Invalid key. Key must be the same length as the text (excluding spaces) and contain only letters.');
        return;
      }
    } else if (algorithm.validateKey) {
      const isValid = extraParam ? algorithm.validateKey(key, extraParam) : algorithm.validateKey(key);
      if (!isValid) {
        showError('Invalid key format. Please check the key requirements.');
        return;
      }
    }

    // Execute encryption or decryption
    let result;
    if (mode === 'encrypt') {
      result = extraParam ? algorithm.encrypt(input, key, extraParam) : algorithm.encrypt(input, key);
    } else {
      result = extraParam ? algorithm.decrypt(input, key, extraParam) : algorithm.decrypt(input, key);
    }

    if (!result) {
      showError('Encryption/decryption returned empty result. Please check your input and key.');
      return;
    }

    outputField.value = result;

    // Update visualization if enabled (only if encryption/decryption succeeded)
    if (vizContent && !vizContent.classList.contains('hidden')) {
      try {
        const steps = extraParam ? algorithm.getVisualizationSteps(input, key, mode, extraParam) : algorithm.getVisualizationSteps(input, key, mode);
        if (steps && Array.isArray(steps) && steps.length > 0) {
          renderVisualization(vizContent, steps, algorithm.constructor.name);
        } else {
          // If visualization fails, disable it silently
          vizContent.innerHTML = '<p class="text-gray-400">Visualization unavailable</p>';
        }
      } catch (vizError) {
        // If visualization breaks, disable it immediately
        console.warn('Visualization error:', vizError);
        vizContent.innerHTML = '<p class="text-gray-400">Visualization disabled due to error</p>';
      }
    }
  } catch (error) {
    showError('Error: ' + error.message);
    console.error(error);
  }
}

// Show error notification
function showError(message) {
  // Remove existing notification if any
  const existing = document.querySelector('.error-notification');
  if (existing) {
    existing.remove();
  }

  // Create notification
  const notification = document.createElement('div');
  notification.className = 'error-notification fixed bottom-8 right-8 bg-red-600 text-white px-6 py-3 rounded-lg shadow-2xl z-50 opacity-0 transform translate-y-4 transition-all duration-300';
  notification.textContent = message;
  document.body.appendChild(notification);

  // Show notification
  setTimeout(() => {
    notification.classList.remove('opacity-0', 'translate-y-4');
    notification.classList.add('opacity-100', 'translate-y-0');
  }, 10);

  // Hide and remove notification after 3 seconds
  setTimeout(() => {
    notification.classList.remove('opacity-100', 'translate-y-0');
    notification.classList.add('opacity-0', 'translate-y-4');
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, 3000);
}

// Render visualization steps - now uses advanced visualization engine
function renderVisualization(container, steps, algorithmName) {
  // Use the advanced visualization engine if available
  if (typeof VisualizationEngine !== 'undefined') {
    // Load visualization.js if not already loaded
    if (!window.visualizationEngineLoaded) {
      const script = document.createElement('script');
      script.src = '/js/visualization.js';
      script.onload = () => {
        window.visualizationEngineLoaded = true;
        renderVisualization(container, steps, algorithmName);
      };
      document.head.appendChild(script);
      return;
    }

    // Use advanced visualization engine
    const engine = new VisualizationEngine(container);
    engine.render(steps, algorithmName);
    return;
  }

  // Fallback to basic visualization
  if (!container) {
    console.warn('Visualization container not found');
    return;
  }

  container.innerHTML = '';

  if (!steps || !Array.isArray(steps) || steps.length === 0) {
    container.innerHTML = '<p class="text-gray-400">No visualization available for this operation.</p>';
    return;
  }

  // Add header
  const header = document.createElement('div');
  header.className = 'mb-4 pb-3 border-b border-gray-600 dark:border-gray-700';
  header.innerHTML = `<h3 class="text-lg font-semibold text-gray-200 dark:text-gray-300">Step-by-Step ${algorithmName.replace('Cipher', '')} Process</h3>`;
  container.appendChild(header);

  let currentStep = 0;
  const stepElements = [];

  function showStep() {
    if (currentStep >= steps.length) {
      if (stepElements.length > 0) {
        stepElements[stepElements.length - 1].classList.add('ring-2', 'ring-green-500', 'ring-opacity-50');
      }
      return;
    }

    const step = steps[currentStep];
    const stepElement = document.createElement('div');
    stepElement.className = 'visualization-step mb-3 p-4 bg-gray-700 dark:bg-gray-800 rounded-lg border border-gray-600 dark:border-gray-700 text-gray-200 dark:text-gray-300 transition-all duration-300';

    let html = step.html || step.description || '';
    html = html.replace(/<table/g, '<table class="w-full border-collapse my-2"');
    html = html.replace(/<td/g, '<td class="p-2 border border-gray-500 dark:border-gray-600 text-center"');
    html = html.replace(/<th/g, '<th class="p-2 border border-gray-500 dark:border-gray-600 bg-gray-600 dark:bg-gray-700 font-semibold"');

    stepElement.innerHTML = html;

    const stepNumber = document.createElement('div');
    stepNumber.className = 'text-xs text-gray-400 dark:text-gray-500 mb-2 font-mono';
    stepNumber.textContent = `Step ${currentStep + 1} of ${steps.length}`;
    stepElement.insertBefore(stepNumber, stepElement.firstChild);

    container.appendChild(stepElement);
    stepElements.push(stepElement);

    stepElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    stepElement.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');

    if (currentStep > 0) {
      stepElements[currentStep - 1].classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
    }

    currentStep++;

    if (currentStep < steps.length) {
      setTimeout(showStep, step.delay || 1000);
    } else {
      setTimeout(() => {
        stepElement.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
        stepElement.classList.add('ring-2', 'ring-green-500', 'ring-opacity-50');
      }, 500);
    }
  }

  showStep();
}

