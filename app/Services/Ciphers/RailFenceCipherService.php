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
            $fence = array_fill(0, $rails, []);
            $direction = 1;
            $rail = 0;

            for ($i = 0; $i < strlen($cleanText); $i++) {
                $fence[$rail][] = $cleanText[$i];

                $railHtml = '<p><strong>Position ' . ($i + 1) . ':</strong> Character "' . $cleanText[$i] . '" → Rail ' . ($rail + 1) . '</p>';
                $railHtml .= '<pre style="font-family: monospace; line-height: 1.5;">';
                for ($r = 0; $r < $rails; $r++) {
                    $railHtml .= 'Rail ' . ($r + 1) . ': ' . implode(' ', $fence[$r]) . '\n';
                }
                $railHtml .= '</pre>';

                $steps[] = [
                    'html' => $railHtml,
                    'delay' => 1000
                ];

                $rail += $direction;
                if ($rail === 0 || $rail === $rails - 1) {
                    $direction *= -1;
                }
            }

            $result = implode('', array_map(function ($row) {
                return implode('', $row);
            }, $fence));

            $steps[] = [
                'html' => '<p><strong>Step 2:</strong> Reading rail by rail</p>',
                'delay' => 500
            ];

            $steps[] = [
                'html' => '<p><strong>Result:</strong> ' . $result . '</p>',
                'delay' => 500
            ];
        } else {
            // Decryption visualization
            $steps[] = [
                'html' => '<p><strong>Step 2:</strong> Distributing characters to rails</p>',
                'delay' => 500
            ];

            $fence = array_fill(0, $rails, []);
            $positions = [];
            $direction = 1;
            $rail = 0;

            for ($i = 0; $i < strlen($cleanText); $i++) {
                $positions[] = $rail;
                $rail += $direction;
                if ($rail === 0 || $rail === $rails - 1) {
                    $direction *= -1;
                }
            }

            $charIndex = 0;
            for ($r = 0; $r < $rails; $r++) {
                $count = count(array_filter($positions, function ($p) use ($r) {
                    return $p === $r;
                }));
                for ($i = 0; $i < $count; $i++) {
                    $fence[$r][] = $cleanText[$charIndex++];
                }
            }

            $fenceHtml = '<pre style="font-family: monospace; line-height: 1.5;">';
            for ($r = 0; $r < $rails; $r++) {
                $fenceHtml .= 'Rail ' . ($r + 1) . ': ' . implode(' ', $fence[$r]) . '\n';
            }
            $fenceHtml .= '</pre>';

            $steps[] = [
                'html' => $fenceHtml,
                'delay' => 1000
            ];

            $steps[] = [
                'html' => '<p><strong>Step 3:</strong> Reading in zigzag pattern</p>',
                'delay' => 500
            ];

            $result = '';
            $direction = 1;
            $rail = 0;
            $railIndices = array_fill(0, $rails, 0);

            for ($i = 0; $i < strlen($cleanText); $i++) {
                $result .= $fence[$rail][$railIndices[$rail]++];
                $rail += $direction;
                if ($rail === 0 || $rail === $rails - 1) {
                    $direction *= -1;
                }
            }

            $steps[] = [
                'html' => '<p><strong>Result:</strong> ' . $result . '</p>',
                'delay' => 500
            ];
        }

        return $steps;
    }
}

