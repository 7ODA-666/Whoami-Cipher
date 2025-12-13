// SPA (Single Page Application) Navigation Handler
// Handles dynamic content loading without page reloads

class SPANavigator {
  constructor() {
    this.init();
  }

  init() {
    // Intercept all internal links
    document.addEventListener('click', (e) => {
      const link = e.target.closest('a[href]');
      if (!link) return;

      const href = link.getAttribute('href');
      
      // Skip if:
      // - No href
      // - External link (http/https)
      // - Anchor link (#)
      // - JavaScript link (javascript:)
      // - Mailto/tel links
      // - Explicitly marked to skip SPA
      // - Ctrl/Cmd click (open in new tab)
      // - Middle mouse button
      if (!href || 
          href.startsWith('http://') || 
          href.startsWith('https://') ||
          href.startsWith('//') ||
          href.startsWith('#') ||
          href.startsWith('javascript:') ||
          href.startsWith('mailto:') ||
          href.startsWith('tel:') ||
          link.hasAttribute('data-no-spa') ||
          link.hasAttribute('target') ||
          e.ctrlKey ||
          e.metaKey ||
          e.button === 1) {
        return;
      }
      
      // Only handle internal links (same origin)
      if (href.startsWith('/')) {
        e.preventDefault();
        this.navigate(href);
      }
    });

    // Handle browser back/forward buttons
    window.addEventListener('popstate', (e) => {
      if (e.state && e.state.url) {
        this.loadContent(e.state.url, false); // false = don't push to history
      }
    });

    // Initialize on page load
    this.setupInitialState();
  }

  setupInitialState() {
    // Store initial page state
    if (!window.history.state) {
      window.history.replaceState({ url: window.location.pathname }, '', window.location.pathname);
    }
  }

  async navigate(url) {
    // Update URL immediately for better UX
    window.history.pushState({ url: url }, '', url);
    
    // Load content
    await this.loadContent(url, false);
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  async loadContent(url, pushState = true) {
    try {
      // Show loading indicator
      this.showLoading();

      // Fetch the page content with CSRF token if available
      const headers = {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'text/html',
      };
      
      const csrfToken = document.querySelector('meta[name="csrf-token"]');
      if (csrfToken) {
        headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
      }

      // Fetch the page content
      const response = await fetch(url, {
        headers: headers,
        credentials: 'same-origin'
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const html = await response.text();
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');

      // Extract main content
      const newMainContent = doc.querySelector('#main-content');
      const currentMainContent = document.querySelector('#main-content');

      if (newMainContent && currentMainContent) {
        // Update page title
        const newTitle = doc.querySelector('title');
        if (newTitle) {
          document.title = newTitle.textContent;
        }

        // Update sidebar active state
        this.updateSidebarActiveState(url);

        // Fade out current content
        currentMainContent.style.opacity = '0';
        currentMainContent.style.transition = 'opacity 0.2s ease';

        // Wait for fade out
        await new Promise(resolve => setTimeout(resolve, 200));

      // Store scroll position
      const scrollPos = window.scrollY;

      // Replace content
      currentMainContent.innerHTML = newMainContent.innerHTML;

      // Restore scroll position
      window.scrollTo(0, 0);

      // Fade in new content first
      currentMainContent.style.opacity = '1';

      // Reinitialize UI components after a brief delay
      this.reinitializeComponents();

        // Update URL if needed
        if (pushState) {
          window.history.pushState({ url: url }, '', url);
        }
      }
    } catch (error) {
      console.error('Error loading content:', error);
      this.showError('Failed to load page. Please refresh.');
    } finally {
      this.hideLoading();
    }
  }

  updateSidebarActiveState(url) {
    // Extract algorithm from URL
    const match = url.match(/\/([^\/]+)(?:\/([^\/]+))?/);
    if (match) {
      const algorithm = match[1];
      
      // Update sidebar links
      const sidebarLinks = document.querySelectorAll('#sidebar a');
      sidebarLinks.forEach(link => {
        link.classList.remove('text-white', 'bg-gradient-to-r', 'from-blue-600', 'to-purple-600');
        link.classList.add('text-gray-300');
        
        const linkHref = link.getAttribute('href');
        if (linkHref && linkHref.includes(algorithm)) {
          link.classList.remove('text-gray-300');
          link.classList.add('text-white', 'bg-gradient-to-r', 'from-blue-600', 'to-purple-600');
        }
      });
    }
  }

  reinitializeComponents() {
    // Small delay to ensure DOM is ready
    setTimeout(() => {
      // Reinitialize tabs
      if (typeof initTabs === 'function') {
        initTabs();
      }

      // Reinitialize copy buttons
      if (typeof initCopyButtons === 'function') {
        initCopyButtons();
      }

      // Reinitialize clear buttons
      if (typeof initClearButtons === 'function') {
        initClearButtons();
      }

      // Reinitialize visualization toggle
      if (typeof initVisualizationToggle === 'function') {
        initVisualizationToggle();
      }

      // Reinitialize algorithm-specific scripts
      this.reinitializeAlgorithmScripts();
      
      // Trigger custom event for other components
      window.dispatchEvent(new CustomEvent('spa:content-loaded'));
    }, 100);
  }

  reinitializeAlgorithmScripts() {
    // Execute all scripts in the main content area
    const mainContent = document.querySelector('#main-content');
    if (!mainContent) return;

    // Find all script tags (both inline and with src)
    const scripts = Array.from(mainContent.querySelectorAll('script'));
    
    scripts.forEach(script => {
      // Skip if already processed
      if (script.dataset.spaProcessed) return;
      script.dataset.spaProcessed = 'true';
      
      const newScript = document.createElement('script');
      
      // Copy all attributes
      Array.from(script.attributes).forEach(attr => {
        if (attr.name !== 'data-spa-processed') {
          newScript.setAttribute(attr.name, attr.value);
        }
      });
      
      // Copy content for inline scripts
      if (script.textContent) {
        newScript.textContent = script.textContent;
      }
      
      // For scripts with src, wait for load
      if (script.src) {
        newScript.onload = () => {
          // Script loaded, can now remove old one
          if (script.parentNode) {
            script.parentNode.removeChild(script);
          }
        };
        newScript.onerror = () => {
          console.error('Failed to load script:', script.src);
        };
      }
      
      // Insert new script
      script.parentNode.insertBefore(newScript, script);
      
      // Remove old script if it's inline
      if (!script.src) {
        script.parentNode.removeChild(script);
      }
    });
  }

  showLoading() {
    // Create or show loading overlay
    let loader = document.getElementById('spa-loader');
    if (!loader) {
      loader = document.createElement('div');
      loader.id = 'spa-loader';
      loader.className = 'fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50 flex items-center justify-center';
      loader.innerHTML = `
        <div class="bg-gray-800 rounded-lg p-6 flex items-center gap-4">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
          <span class="text-white text-lg">Loading...</span>
        </div>
      `;
      document.body.appendChild(loader);
    }
    loader.style.display = 'flex';
  }

  hideLoading() {
    const loader = document.getElementById('spa-loader');
    if (loader) {
      loader.style.display = 'none';
    }
  }

  showError(message) {
    // Show error notification
    const errorDiv = document.createElement('div');
    errorDiv.className = 'fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-xl z-50';
    errorDiv.textContent = message;
    document.body.appendChild(errorDiv);

    setTimeout(() => {
      errorDiv.remove();
    }, 5000);
  }
}

// Initialize SPA navigator when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  window.spaNavigator = new SPANavigator();
});

