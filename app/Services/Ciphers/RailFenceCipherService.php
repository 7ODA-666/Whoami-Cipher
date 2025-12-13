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

        for ($i = 0; $i < strlen($text); $i++) {
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

        for ($i = 0; $i < strlen($text); $i++) {
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

        for ($i = 0; $i < strlen($text); $i++) {
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
            'html' => '<p><strong>Step 1:</strong> ' . ($isEncrypt ? 'Encryption' : 'Decryption') . ' using ' . $rails . ' rails</p>',
            'delay' => 500
        ];

        $steps[] = [
            'html' => '<p><strong>Text:</strong> ' . $cleanText . '</p>',
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
            'html' => '<p><strong>Step 2:</strong> Reading characters rail by rail to form ciphertext</p>',
            'delay' => 500
        ];

        $result = '';
        $finalHtml = '<div style="background: #1e2937; padding: 15px; border-radius: 5px; margin: 10px 0;">';
        $finalHtml .= '<p><strong>Reading Order:</strong></p>';

        for ($r = 0; $r < $rails; $r++) {
            $railChars = '';
            for ($i = 0; $i < $textLength; $i++) {
                if ($fence[$r][$i] !== '.') {
                    $railChars .= $fence[$r][$i];
                    $result .= $fence[$r][$i];
                }
            }
            if (!empty($railChars)) {
                $finalHtml .= '<p>Rail ' . ($r + 1) . ': <strong style="color: #007bff;">' . $railChars . '</strong></p>';
            }
        }
        $finalHtml .= '</div>';

        $steps[] = [
            'html' => $finalHtml,
            'delay' => 1000
        ];

        $steps[] = [
            'html' => '<p><strong>Final Result:</strong> <span style="font-size: 1.2em; color: #28a745; font-weight: bold;">' . $result . '</span></p>',
            'delay' => 500
        ];

        return $steps;
    }

    private function getDecryptionVisualization(string $cleanText, int $rails, array $steps): array
    {
        $textLength = strlen($cleanText);

        // Step 1: Show how we determine the zigzag pattern
        $steps[] = [
            'html' => '<p><strong>Step 2:</strong> First, determine the zigzag pattern positions</p>',
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
        $patternHtml = '<div style="background: #1e2937; padding: 15px; border-radius: 5px; margin: 10px 0;">';
        $patternHtml .= '<p><strong>Zigzag Pattern:</strong></p>';
        $patternHtml .= '<pre style="font-family: monospace; font-size: 14px; line-height: 1.8;">';

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
            'html' => '<p><strong>Step 3:</strong> Distribute ciphertext characters to their respective rails</p>',
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

        $distributionHtml = '<div style="background: #1e2937; padding: 15px; border-radius: 5px; margin: 10px 0;">';
        $distributionHtml .= '<p><strong>Character Distribution:</strong></p>';
        $charIndex = 0;
        for ($r = 0; $r < $rails; $r++) {
            $count = count(array_filter($positions, function ($p) use ($r) {
                return $p === $r;
            }));
            if ($count > 0) {
                $railChars = substr($cleanText, $charIndex, $count);
                $distributionHtml .= '<p>Rail ' . ($r + 1) . ': <strong style="color: #856404;">' . implode(' ', str_split($railChars)) . '</strong></p>';
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
            'html' => '<p><strong>Step 4:</strong> Read characters following the original zigzag pattern</p>',
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
            'html' => '<p><strong>Final Result:</strong> <span style="font-size: 1.2em; color: #28a745; font-weight: bold;">' . $result . '</span></p>',
            'delay' => 500
        ];

        return $steps;
    }

    private function generateFenceVisualization(array $fence, int $rails, int $textLength, int $currentPos = -1, int $currentRail = -1, string $currentChar = '', bool $isComplete = false): string
    {
        $html = '<div style="background: #1e2937; padding: 15px; border-radius: 5px; margin: 10px 0;">';

        if ($currentPos >= 0) {
            $html .= '<p><strong>Position ' . ($currentPos + 1) . ':</strong> Character "' . $currentChar . '" placed on Rail ' . ($currentRail + 1) . '</p>';
        }

        $html .= '<pre style="font-family: monospace; font-size: 14px; line-height: 1.8; background: #1e2937; padding: 15px; border: 1px solid #dee2e6; border-radius: 4px;">';

        // Add position numbers header
        $html .= 'Pos: ';
        for ($i = 0; $i < $textLength; $i++) {
            $html .= sprintf('%2d ', $i + 1);
        }
        $html .= "\n";
        $html .= str_repeat('-', 4 + $textLength * 3) . "\n";

        // Generate each rail
        for ($r = 0; $r < $rails; $r++) {
            $html .= sprintf('R%d:  ', $r + 1);

            for ($i = 0; $i < $textLength; $i++) {
                $char = $fence[$r][$i];

                // Highlight current position
                if ($i === $currentPos && $r === $currentRail) {
                    $html .= '<span style="background-color: #007bff; color: white; padding: 2px 4px; border-radius: 3px;">' . $char . '</span> ';
                } elseif ($char !== '.') {
                    $html .= '<span style="color: #007bff; font-weight: bold;">' . $char . '</span>  ';
                } else {
                    $html .= '.  ';
                }
            }
            $html .= "\n";
        }

        $html .= '</pre>';

        if ($isComplete) {
            $html .= '<p><em>Complete Rail Fence pattern showing the decrypted message</em></p>';
        }

        $html .= '</div>';

        return $html;
    }
}

