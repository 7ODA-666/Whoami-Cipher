// Global Speed Control Handler
// Manages the speed control that appears before visualization starts

document.addEventListener('DOMContentLoaded', function() {
    const speedControl = document.getElementById('viz-speed-control');
    const speedDisplay = document.getElementById('viz-speed-display');

    if (speedControl && speedDisplay) {
        // Initialize the display
        speedDisplay.textContent = speedControl.value + 'ms';

        // Handle speed changes
        speedControl.addEventListener('input', function(e) {
            const value = e.target.value;
            speedDisplay.textContent = value + 'ms';

            // Update the slider background to show progress
            const percentage = ((value - speedControl.min) / (speedControl.max - speedControl.min)) * 100;

            // Update background gradient to show current position
            if (document.documentElement.classList.contains('dark')) {
                speedControl.style.background = `linear-gradient(to right, #3b82f6 0%, #3b82f6 ${percentage}%, #4b5563 ${percentage}%, #4b5563 100%)`;
            } else {
                speedControl.style.background = `linear-gradient(to right, #3b82f6 0%, #3b82f6 ${percentage}%, #e5e7eb ${percentage}%, #e5e7eb 100%)`;
            }
        });

        // Initialize the background on load
        const initialPercentage = ((speedControl.value - speedControl.min) / (speedControl.max - speedControl.min)) * 100;
        if (document.documentElement.classList.contains('dark')) {
            speedControl.style.background = `linear-gradient(to right, #3b82f6 0%, #3b82f6 ${initialPercentage}%, #4b5563 ${initialPercentage}%, #4b5563 100%)`;
        } else {
            speedControl.style.background = `linear-gradient(to right, #3b82f6 0%, #3b82f6 ${initialPercentage}%, #e5e7eb ${initialPercentage}%, #e5e7eb 100%)`;
        }

        // Update background on theme change
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const percentage = ((speedControl.value - speedControl.min) / (speedControl.max - speedControl.min)) * 100;
                    if (document.documentElement.classList.contains('dark')) {
                        speedControl.style.background = `linear-gradient(to right, #3b82f6 0%, #3b82f6 ${percentage}%, #4b5563 ${percentage}%, #4b5563 100%)`;
                    } else {
                        speedControl.style.background = `linear-gradient(to right, #3b82f6 0%, #3b82f6 ${percentage}%, #e5e7eb ${percentage}%, #e5e7eb 100%)`;
                    }
                }
            });
        });

        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    }
});
