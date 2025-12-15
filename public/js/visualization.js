// Advanced Visualization System for Cryptography Algorithms
// Provides realistic, step-by-step educational visualizations

class VisualizationEngine {
  constructor(container) {
    this.container = container;
    this.currentStep = 0;
    this.steps = [];
    this.stepElements = [];
    this.isPlaying = false;
    this.speed = 1000; // milliseconds between steps

    // Check for external speed control
    this.externalSpeedControl = document.getElementById('viz-speed-control');
    this.externalSpeedDisplay = document.getElementById('viz-speed-display');

    if (this.externalSpeedControl) {
      this.speed = parseInt(this.externalSpeedControl.value) || 1000;
      this.setupExternalSpeedControl();
    }
  }

  setupExternalSpeedControl() {
    if (this.externalSpeedControl && this.externalSpeedDisplay) {
      // Set initial display
      this.externalSpeedDisplay.textContent = this.speed + 'ms';

      // Add event listener for speed changes
      this.externalSpeedControl.addEventListener('input', (e) => {
        this.speed = parseInt(e.target.value);
        this.externalSpeedDisplay.textContent = this.speed + 'ms';
      });
    }
  }

  render(steps, algorithmName) {
    if (!this.container) {
      console.warn('Visualization container not found');
      return;
    }

    this.container.innerHTML = '';
    this.steps = steps || [];
    this.currentStep = 0;
    this.stepElements = [];

    if (!this.steps || this.steps.length === 0) {
      this.container.innerHTML = '<p class="text-light-text-secondary dark:text-dark-text-secondary text-center py-8">No visualization available for this operation.</p>';
      return;
    }

    // Create header
    this.createHeader(algorithmName);

    // Create controls
    this.createControls();

    // Create steps container
    const stepsContainer = document.createElement('div');
    stepsContainer.id = 'viz-steps-container';
    stepsContainer.className = 'space-y-4 mt-4';
    this.container.appendChild(stepsContainer);

    // Start animation
    this.play();
  }

  createHeader(algorithmName) {
    const header = document.createElement('div');
    header.className = 'mb-6 pb-4 border-b border-light-border dark:border-dark-border';
    header.innerHTML = `
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-xl font-bold text-light-text dark:text-dark-text mb-1">
            Step-by-Step ${algorithmName.replace('Cipher', '')} Process
          </h3>
          <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Interactive visualization of the encryption/decryption process</p>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-xs text-light-text-secondary dark:text-dark-text-secondary font-mono" id="viz-step-counter">Step 0 of ${this.steps.length}</span>
        </div>
      </div>
    `;
    this.container.appendChild(header);
  }

  createControls() {
    const controls = document.createElement('div');
    controls.className = 'flex items-center gap-3 mb-4 p-3 bg-light-card dark:bg-dark-card rounded-lg border border-light-border dark:border-dark-border';

    // Build controls HTML - exclude speed control if external control exists
    let controlsHTML = `
      <button id="viz-play-pause" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-semibold">
        <i class="fas fa-play mr-2"></i>Play
      </button>
      <button id="viz-prev" class="px-4 py-2 bg-light-card dark:bg-dark-card hover:bg-gray-100 dark:hover:bg-gray-600 text-light-text-secondary dark:text-dark-text-secondary hover:text-light-text dark:hover:text-dark-text rounded-lg transition-colors text-sm font-semibold border border-light-border dark:border-dark-border" disabled>
        <i class="fas fa-step-backward mr-2"></i>Previous
      </button>
      <button id="viz-next" class="px-4 py-2 bg-light-card dark:bg-dark-card hover:bg-gray-100 dark:hover:bg-gray-600 text-light-text-secondary dark:text-dark-text-secondary hover:text-light-text dark:hover:text-dark-text rounded-lg transition-colors text-sm font-semibold border border-light-border dark:border-dark-border">
        <i class="fas fa-step-forward mr-2"></i>Next
      </button>
      <button id="viz-reset" class="px-4 py-2 bg-light-card dark:bg-dark-card hover:bg-gray-100 dark:hover:bg-gray-600 text-light-text-secondary dark:text-dark-text-secondary hover:text-light-text dark:hover:text-dark-text rounded-lg transition-colors text-sm font-semibold border border-light-border dark:border-dark-border">
        <i class="fas fa-redo mr-2"></i>Reset
      </button>
      <div class="flex-1"></div>`;

    // Only add speed control if external control doesn't exist
    if (!this.externalSpeedControl) {
      controlsHTML += `
      <div class="flex items-center gap-2">
        <label class="text-xs text-light-text-secondary dark:text-dark-text-secondary">Speed:</label>
        <input type="range" id="viz-speed" min="200" max="3000" value="${this.speed}" step="100" class="w-24">
        <span class="text-xs text-light-text-secondary dark:text-dark-text-secondary font-mono w-12" id="viz-speed-value">${this.speed}ms</span>
      </div>`;
    }

    controls.innerHTML = controlsHTML;
    this.container.appendChild(controls);

    // Attach event listeners
    document.getElementById('viz-play-pause').addEventListener('click', () => this.togglePlayPause());
    document.getElementById('viz-prev').addEventListener('click', () => this.previousStep());
    document.getElementById('viz-next').addEventListener('click', () => this.nextStep());
    document.getElementById('viz-reset').addEventListener('click', () => this.reset());

    // Only add speed control listener if internal control exists
    const speedControl = document.getElementById('viz-speed');
    if (speedControl) {
      speedControl.addEventListener('input', (e) => {
        this.speed = parseInt(e.target.value);
        document.getElementById('viz-speed-value').textContent = this.speed + 'ms';
      });
    }
  }

  play() {
    if (this.isPlaying) return;

    this.isPlaying = true;
    const playPauseBtn = document.getElementById('viz-play-pause');
    if (playPauseBtn) {
      playPauseBtn.innerHTML = '<i class="fas fa-pause mr-2"></i>Pause';
      playPauseBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
      playPauseBtn.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
    }

    this.showNextStep();
  }

  pause() {
    this.isPlaying = false;
    const playPauseBtn = document.getElementById('viz-play-pause');
    if (playPauseBtn) {
      playPauseBtn.innerHTML = '<i class="fas fa-play mr-2"></i>Play';
      playPauseBtn.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
      playPauseBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
    }
  }

  togglePlayPause() {
    if (this.isPlaying) {
      this.pause();
    } else {
      this.play();
    }
  }

  showNextStep() {
    if (this.currentStep >= this.steps.length) {
      this.pause();
      this.highlightFinalResult();
      return;
    }

    const step = this.steps[this.currentStep];
    this.displayStep(step);

    this.currentStep++;
    this.updateControls();
    this.updateCounter();

    if (this.isPlaying && this.currentStep < this.steps.length) {
      setTimeout(() => this.showNextStep(), this.speed);
    } else if (this.currentStep >= this.steps.length) {
      this.pause();
      this.highlightFinalResult();
    }
  }

  previousStep() {
    if (this.currentStep <= 0) return;

    this.pause();
    this.currentStep--;

    // Remove last step element
    if (this.stepElements[this.currentStep]) {
      this.stepElements[this.currentStep].remove();
      this.stepElements.splice(this.currentStep, 1);
    }

    this.updateControls();
    this.updateCounter();
  }

  nextStep() {
    if (this.currentStep >= this.steps.length) return;

    this.pause();
    this.showNextStep();
  }

  reset() {
    this.pause();
    this.currentStep = 0;
    this.stepElements = [];
    const stepsContainer = document.getElementById('viz-steps-container');
    if (stepsContainer) {
      stepsContainer.innerHTML = '';
    }
    this.updateControls();
    this.updateCounter();
  }

  displayStep(step) {
    const stepsContainer = document.getElementById('viz-steps-container');
    if (!stepsContainer) return;

    const stepElement = document.createElement('div');
    stepElement.className = 'visualization-step p-5 bg-light-card dark:bg-dark-card rounded-lg border-2 border-blue-500 shadow-lg transform transition-all duration-500';
    stepElement.style.opacity = '0';
    stepElement.style.transform = 'translateY(20px)';

    // Parse and enhance HTML
    let html = step.html || step.description || '';

    // Enhance tables for better visibility
    html = this.enhanceTables(html);

    // Add step number badge
    const stepNumber = document.createElement('div');
    stepNumber.className = 'inline-flex items-center px-3 py-1 mb-3 bg-blue-600 text-white text-xs font-bold rounded-full';
    stepNumber.textContent = `Step ${this.currentStep + 1}`;
    stepElement.appendChild(stepNumber);

    // Add content
    const content = document.createElement('div');
    content.className = 'text-light-text dark:text-dark-text';
    content.innerHTML = html;
    stepElement.appendChild(content);

    stepsContainer.appendChild(stepElement);
    this.stepElements.push(stepElement);

    // Animate in
    setTimeout(() => {
      stepElement.style.opacity = '1';
      stepElement.style.transform = 'translateY(0)';
    }, 10);

    // Highlight current step
    this.highlightStep(stepElement);

    // Scroll to show new step
    stepElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  enhanceTables(html) {
    // Enhance matrix tables with theme-aware classes
    html = html.replace(/<table/g, '<table class="w-full border-collapse my-4 mx-auto max-w-md"');
    html = html.replace(/<td([^>]*)>/g, '<td$1 class="p-3 border border-light-border dark:border-dark-border text-center bg-light-bg dark:bg-dark-bg font-mono text-lg font-semibold text-light-text dark:text-dark-text">');
    html = html.replace(/<th([^>]*)>/g, '<th$1 class="p-3 border border-light-border dark:border-dark-border text-center bg-light-card dark:bg-dark-card font-bold text-light-text dark:text-dark-text">');
    html = html.replace(/<tr([^>]*)>/g, '<tr$1 class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">');

    // Enhance code/math expressions with theme-aware colors
    html = html.replace(/(\d+)\s*([+\-×])\s*(\d+)\s*=\s*(\d+)/g,
      '<span class="font-mono text-blue-600 dark:text-blue-400">$1</span> <span class="text-purple-600 dark:text-purple-400 font-bold">$2</span> <span class="font-mono text-blue-600 dark:text-blue-400">$3</span> <span class="text-light-text-secondary dark:text-dark-text-secondary">=</span> <span class="font-mono text-green-600 dark:text-green-400 font-bold text-xl">$4</span>');

    // Enhance modulo operations
    html = html.replace(/\(mod\s+(\d+)\)/g, '<span class="text-orange-600 dark:text-orange-400 font-mono text-sm">(mod $1)</span>');

    // Enhance character mappings
    html = html.replace(/"([A-Z])"/g, '<span class="font-mono text-xl text-blue-600 dark:text-blue-400 font-bold">"$1"</span>');

    return html;
  }

  highlightStep(stepElement) {
    // Remove highlight from previous steps
    this.stepElements.forEach((el, index) => {
      if (index < this.currentStep - 1) {
        el.classList.remove('border-blue-500', 'ring-4', 'ring-blue-500', 'ring-opacity-50');
        el.classList.add('border-light-border', 'dark:border-dark-border');
      }
    });

    // Highlight current step
    stepElement.classList.remove('border-light-border', 'dark:border-dark-border');
    stepElement.classList.add('border-blue-500', 'ring-4', 'ring-blue-500', 'ring-opacity-50');
  }

  highlightFinalResult() {
    if (this.stepElements.length > 0) {
      const lastStep = this.stepElements[this.stepElements.length - 1];
      lastStep.classList.remove('border-blue-500', 'ring-blue-500');
      lastStep.classList.add('border-green-500', 'ring-4', 'ring-green-500', 'ring-opacity-50');
    }
  }

  updateControls() {
    const prevBtn = document.getElementById('viz-prev');
    const nextBtn = document.getElementById('viz-next');

    if (prevBtn) {
      prevBtn.disabled = this.currentStep <= 0;
    }

    if (nextBtn) {
      nextBtn.disabled = this.currentStep >= this.steps.length;
    }
  }

  updateCounter() {
    const counter = document.getElementById('viz-step-counter');
    if (counter) {
      counter.textContent = `Step ${this.currentStep} of ${this.steps.length}`;
    }
  }
}

// Enhanced renderVisualization function
function renderVisualization(container, steps, algorithmName) {
  if (!container) {
    console.warn('Visualization container not found');
    return;
  }

  // Clear existing visualization
  container.innerHTML = '';

  if (!steps || !Array.isArray(steps) || steps.length === 0) {
    container.innerHTML = '<p class="text-light-text-secondary dark:text-dark-text-secondary text-center py-8">No visualization available for this operation.</p>';
    return;
  }

  // Create and use visualization engine
  const engine = new VisualizationEngine(container);
  engine.render(steps, algorithmName);
}

