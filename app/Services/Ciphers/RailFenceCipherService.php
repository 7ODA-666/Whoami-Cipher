<?php

namespace App\Services\Ciphers;

class RailFenceCipherService
{
    public function validateInput(string $text): bool
    {
        return preg_match('/^[A-Za-z\s]+$/', $text) === 1;
    }

    public function validateKey($key): bool
    {
        $rails = (int) $key;
        return $rails >= 2 && $rails <= 10;
    }

    public function encrypt(string $plaintext, int $rails): string
    {
        if (empty($plaintext)) {
            return '';
        }

        if ($rails < 2 || $rails > 10) {
            throw new \InvalidArgumentException('Number of rails must be between 2 and 10');
        }

        $text = strtoupper(preg_replace('/\s+/', '', $plaintext));
        if (empty($text)) {
            return '';
        }

        $fence = array_fill(0, $rails, []);

        $direction = 1;
        $rail = 0;

        for ($i = 0; $i < (strlen($text)); $i++) {
            $fence[$rail][] = $text[$i];
            $rail += $direction;

            if ($rail === 0 || $rail === $rails - 1) {
                $direction *= -1;
            }
        }

        return implode('', array_map(function ($row) {
            return implode('', $row);
        }, $fence));
    }

    public function decrypt(string $ciphertext, int $rails): string
    {
        if (empty($ciphertext)) {
            return '';
        }

        if ($rails < 2 || $rails > 10) {
            throw new \InvalidArgumentException('Number of rails must be between 2 and 10');
        }

        $text = strtoupper(preg_replace('/\s+/', '', $ciphertext));
        if (empty($text)) {
            return '';
        }

        // Step 1: Determine which rail each position belongs to
        $positions = [];
        $direction = 1;
        $rail = 0;

        for ($i = 0; $i < (strlen($text)); $i++) {
            $positions[] = $rail;
            $rail += $direction;

            if ($rail === 0 || $rail === $rails - 1) {
                $direction *= -1;
            }
        }

        // Step 2: Count how many characters go to each rail
        $railCounts = array_fill(0, $rails, 0);
        foreach ($positions as $pos) {
            $railCounts[$pos]++;
        }

        // Step 3: Fill fence with characters in order
        $fence = array_fill(0, $rails, []);
        $charIndex = 0;
        for ($r = 0; $r < $rails; $r++) {
            for ($i = 0; $i < $railCounts[$r]; $i++) {
                $fence[$r][] = $text[$charIndex++];
            }
        }

        // Step 4: Read in zigzag pattern to reconstruct original
        $result = '';
        $direction = 1;
        $rail = 0;
        $railIndices = array_fill(0, $rails, 0);

        for ($i = 0; $i < (strlen($text)); $i++) {
            $result .= $fence[$rail][$railIndices[$rail]++];
            $rail += $direction;

            if ($rail === 0 || $rail === $rails - 1) {
                $direction *= -1;
            }
        }

        return $result;
    }

    public function getVisualizationSteps(string $text, int $rails, string $mode = 'encrypt'): array
    {
        $steps = [];
        $cleanText = strtoupper(preg_replace('/\s+/', '', $text));
        $isEncrypt = $mode === 'encrypt';

        $steps[] = [
            'html' => '<div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold">
                            <strong>Step 1:</strong> ' . ($isEncrypt ? 'Encryption' : 'Decryption') . ' using
                            <span class="text-blue-600 dark:text-blue-400">' . $rails . ' rails</span>
                        </p>
                      </div>',
            'delay' => 500
        ];

        $steps[] = [
            'html' => '<div class="p-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg mb-3">
                        <p class="text-light-text-secondary dark:text-dark-text-secondary">
                            <strong>Text:</strong>
                            <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-800 dark:text-gray-200">' . $cleanText . '</span>
                        </p>
                      </div>',
            'delay' => 500
        ];

        if ($isEncrypt) {
            return $this->getEncryptionVisualization($cleanText, $rails, $steps);
        } else {
            return $this->getDecryptionVisualization($cleanText, $rails, $steps);
        }
    }

    private function getEncryptionVisualization(string $cleanText, int $rails, array $steps): array
    {
        $textLength = strlen($cleanText);

        // Create a 2D grid to represent the fence
        $fence = array_fill(0, $rails, array_fill(0, $textLength, '.'));

        // Track the zigzag path
        $direction = 1;
        $rail = 0;

        // Fill the fence following the zigzag pattern
        for ($i = 0; $i < $textLength; $i++) {
            $fence[$rail][$i] = $cleanText[$i];

            // Generate visualization step for current character placement
            $stepHtml = $this->generateFenceVisualization($fence, $rails, $textLength, $i, $rail, $cleanText[$i]);

            $steps[] = [
                'html' => $stepHtml,
                'delay' => 1000
            ];

            // Move to next rail
            $rail += $direction;
            if ($rail === 0 || $rail === $rails - 1) {
                $direction *= -1;
            }
        }

        // Show final reading step
        $steps[] = [
            'html' => '<div class="p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold">
                            <strong>Step 2:</strong> Reading characters rail by rail to form ciphertext
                        </p>
                      </div>',
            'delay' => 500
        ];

        $result = '';
        $finalHtml = '<div class="p-4 bg-light-card dark:bg-dark-card border border-dark-border dark:border-light-border rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold mb-3">Reading Order:</p>';

        for ($r = 0; $r < $rails; $r++) {
            $railChars = '';
            for ($i = 0; $i < $textLength; $i++) {
                if ($fence[$r][$i] !== '.') {
                    $railChars .= $fence[$r][$i];
                    $result .= $fence[$r][$i];
                }
            }
            if (!empty($railChars)) {
                $finalHtml .= '<p class="text-light-text-secondary dark:text-dark-text-secondary mb-2">
                                Rail ' . ($r + 1) . ':
                                <span class="font-mono bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded text-blue-800 dark:text-blue-200 font-semibold">' . $railChars . '</span>
                              </p>';
            }
        }
        $finalHtml .= '</div>';

        $steps[] = [
            'html' => $finalHtml,
            'delay' => 1000
        ];

        $steps[] = [
            'html' => '<div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
                        <p class="text-light-text dark:text-dark-text font-bold text-lg">
                            <strong>Final Result:</strong>
                            <span class="font-mono bg-green-100 dark:bg-green-800 px-3 py-2 rounded text-green-800 dark:text-green-200">' . $result . '</span>
                        </p>
                      </div>',
            'delay' => 500
        ];

        return $steps;
    }

    private function getDecryptionVisualization(string $cleanText, int $rails, array $steps): array
    {
        $textLength = strlen($cleanText);

        // Step 1: Show how we determine the zigzag pattern
        $steps[] = [
            'html' => '<div class="p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold">
                            <strong>Step 2:</strong> First, determine the zigzag pattern positions
                        </p>
                      </div>',
            'delay' => 500
        ];

        // Create position mapping
        $positions = [];
        $direction = 1;
        $rail = 0;

        for ($i = 0; $i < $textLength; $i++) {
            $positions[] = $rail;
            $rail += $direction;
            if ($rail === 0 || $rail === $rails - 1) {
                $direction *= -1;
            }
        }

        // Show the pattern
        $patternHtml = '<div class="p-4 bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg mb-4">
                          <p class="text-light-text dark:text-dark-text font-semibold mb-3">Zigzag Pattern:</p>
                          <pre class="font-mono text-sm leading-relaxed bg-light-bg dark:bg-dark-bg p-4 border border-light-border dark:border-dark-border rounded text-light-text dark:text-dark-text overflow-x-auto">';

        for ($r = 0; $r < $rails; $r++) {
            $patternHtml .= 'Rail ' . ($r + 1) . ': ';
            for ($i = 0; $i < $textLength; $i++) {
                if ($positions[$i] === $r) {
                    $patternHtml .= sprintf('%2d ', $i + 1);
                } else {
                    $patternHtml .= ' . ';
                }
            }
            $patternHtml .= "\n";
        }
        $patternHtml .= '</pre></div>';

        $steps[] = [
            'html' => $patternHtml,
            'delay' => 1000
        ];

        // Step 2: Distribute characters to rails
        $steps[] = [
            'html' => '<div class="p-4 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-700 rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold">
                            <strong>Step 3:</strong> Distribute ciphertext characters to their respective rails
                        </p>
                      </div>',
            'delay' => 500
        ];

        $fence = array_fill(0, $rails, []);
        $charIndex = 0;

        for ($r = 0; $r < $rails; $r++) {
            $count = count(array_filter($positions, function ($p) use ($r) {
                return $p === $r;
            }));
            for ($i = 0; $i < $count; $i++) {
                $fence[$r][] = $cleanText[$charIndex++];
            }
        }

        $distributionHtml = '<div class="p-4 bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg mb-4">
                              <p class="text-light-text dark:text-dark-text font-semibold mb-3">Character Distribution:</p>';
        $charIndex = 0;
        for ($r = 0; $r < $rails; $r++) {
            $count = count(array_filter($positions, function ($p) use ($r) {
                return $p === $r;
            }));
            if ($count > 0) {
                $railChars = substr($cleanText, $charIndex, $count);
                $distributionHtml .= '<p class="text-light-text-secondary dark:text-dark-text-secondary mb-2">
                                      Rail ' . ($r + 1) . ':
                                      <span class="font-mono bg-amber-100 dark:bg-amber-800 px-2 py-1 rounded text-amber-800 dark:text-amber-200 font-semibold">' . implode(' ', str_split($railChars)) . '</span>
                                    </p>';
                $charIndex += $count;
            }
        }
        $distributionHtml .= '</div>';

        $steps[] = [
            'html' => $distributionHtml,
            'delay' => 1000
        ];

        // Step 3: Read in zigzag order
        $steps[] = [
            'html' => '<div class="p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold">
                            <strong>Step 4:</strong> Read characters following the original zigzag pattern
                        </p>
                      </div>',
            'delay' => 500
        ];

        $result = '';
        $direction = 1;
        $rail = 0;
        $railIndices = array_fill(0, $rails, 0);

        // Create final fence visualization
        $finalFence = array_fill(0, $rails, array_fill(0, $textLength, '.'));

        for ($i = 0; $i < $textLength; $i++) {
            $char = $fence[$rail][$railIndices[$rail]];
            $finalFence[$rail][$i] = $char;
            $result .= $char;
            $railIndices[$rail]++;

            $rail += $direction;
            if ($rail === 0 || $rail === $rails - 1) {
                $direction *= -1;
            }
        }

        $finalHtml = $this->generateFenceVisualization($finalFence, $rails, $textLength, -1, -1, '', true);
        $steps[] = [
            'html' => $finalHtml,
            'delay' => 1000
        ];

        $steps[] = [
            'html' => '<div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
                        <p class="text-light-text dark:text-dark-text font-bold text-lg">
                            <strong>Final Result:</strong>
                            <span class="font-mono bg-green-100 dark:bg-green-800 px-3 py-2 rounded text-green-800 dark:text-green-200">' . $result . '</span>
                        </p>
                      </div>',
            'delay' => 500
        ];

        return $steps;
    }

    private function generateFenceVisualization(array $fence, int $rails, int $textLength, int $currentPos = -1, int $currentRail = -1, string $currentChar = '', bool $isComplete = false): string
    {
        $html = '<div class="p-4 bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg mb-4">';

        if ($currentPos >= 0) {
            $html .= '<div class="mb-3 p-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded">
                        <p class="text-light-text dark:text-dark-text text-sm">
                            <strong>Position ' . ($currentPos + 1) . ':</strong> Character
                            <span class="font-mono bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded text-blue-800 dark:text-blue-200">"' . $currentChar . '"</span>
                            placed on
                            <span class="font-semibold text-blue-600 dark:text-blue-400">Rail ' . ($currentRail + 1) . '</span>
                        </p>
                      </div>';
        }

        $html .= '<div class="bg-light-bg dark:bg-dark-bg border border-dark-border dark:border-light-border rounded p-3 overflow-x-auto">
                    <pre class="font-mono text-xs sm:text-sm leading-relaxed text-dark-text dark:text-light-text whitespace-pre border-none bg-transparent ">';

        // Add position numbers header
        $html .= '<span class="text-light-text-secondary dark:text-dark-text-secondary">Pos: </span>';
        for ($i = 0; $i < $textLength; $i++) {
            $html .= sprintf('<span class="text-light-text-secondary dark:text-dark-text-secondary">%2d </span>', $i + 1);
        }
        $html .= "\n";
        $html .= '<span class="text-dark-border dark:text-light-border">' . str_repeat('-', 5 + $textLength * 3) . '</span>' . "\n";

        // Generate each rail
        for ($r = 0; $r < $rails; $r++) {
            $html .= sprintf('<span class="text-light-text-secondary dark:text-dark-text-secondary">R%d: </span> ', $r + 1);

            for ($i = 0; $i < $textLength; $i++) {
                $char = $fence[$r][$i];

                // Highlight current position
                if ($i === $currentPos && $r === $currentRail) {
                    $html .= '<span class="bg-blue-600 text-white px-1 rounded font-bold">' . $char . '</span> ';
                } elseif ($char !== '.') {
                    $html .= '<span class="text-blue-600 dark:text-blue-400 font-bold">' . $char . '</span>  ';
                } else {
                    $html .= '<span class="text-light-text-secondary dark:text-dark-text-secondary">.</span>  ';
                }
            }
            $html .= "\n";
        }

        $html .= '</pre></div>';

        if ($isComplete) {
            $html .= '<div class="mt-3 p-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded">
                        <p class="text-light-text-secondary dark:text-dark-text-secondary text-sm italic">
                            Complete Rail Fence pattern showing the decrypted message
                        </p>
                      </div>';
        }

        $html .= '</div>';

        return $html;
    }
}

