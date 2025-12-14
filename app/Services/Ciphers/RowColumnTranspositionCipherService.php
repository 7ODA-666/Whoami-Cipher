<?php

namespace App\Services\Ciphers;

class RowColumnTranspositionCipherService
{
    public function validateInput(string $text): bool
    {
        return preg_match('/^[A-Za-z\s]+$/', $text) === 1;
    }

    public function validateKey(string $key): bool
    {
        return preg_match('/^[A-Za-z]+$/', $key) === 1 && strlen($key) > 0;
    }

    private function getColumnOrder(string $key): array
    {
        $upperKey = strtoupper($key);
        $keyChars = [];
        for ($i = 0; $i < strlen($upperKey); $i++) {
            $keyChars[] = ['char' => $upperKey[$i], 'originalIndex' => $i];
        }

        usort($keyChars, function ($a, $b) {
            if ($a['char'] !== $b['char']) {
                return strcmp($a['char'], $b['char']);
            }
            return $a['originalIndex'] - $b['originalIndex'];
        });

        return array_map(function ($item) {
            return $item['originalIndex'];
        }, $keyChars);
    }

    public function encrypt(string $plaintext, string $key): string
    {
        $text = strtoupper(preg_replace('/\s+/', '', $plaintext));
        $keyLength = strlen($key);
        $numRows = (int) ceil(strlen($text) / $keyLength);

        // Create grid
        $grid = [];
        $textIndex = 0;
        for ($row = 0; $row < $numRows; $row++) {
            $grid[$row] = [];
            for ($col = 0; $col < $keyLength; $col++) {
                if ($textIndex < strlen($text)) {
                    $grid[$row][$col] = $text[$textIndex++];
                } else {
                    $grid[$row][$col] = 'X'; // Padding
                }
            }
        }

        // Get column order
        $columnOrder = $this->getColumnOrder($key);

        // Read column by column
        $result = '';
        foreach ($columnOrder as $colIndex) {
            for ($row = 0; $row < $numRows; $row++) {
                $result .= $grid[$row][$colIndex];
            }
        }

        return $result;
    }

    public function decrypt(string $ciphertext, string $key): string
    {
        $text = strtoupper(preg_replace('/\s+/', '', $ciphertext));
        $keyLength = strlen($key);
        $numRows = (int) ceil(strlen($text) / $keyLength);

        // Get column order
        $columnOrder = $this->getColumnOrder($key);

        // Fill grid column by column
        $grid = array_fill(0, $numRows, array_fill(0, $keyLength, null));
        $textIndex = 0;

        foreach ($columnOrder as $colIndex) {
            for ($row = 0; $row < $numRows; $row++) {
                if ($textIndex < strlen($text)) {
                    $grid[$row][$colIndex] = $text[$textIndex++];
                }
            }
        }

        // Read row by row
        $result = '';
        for ($row = 0; $row < $numRows; $row++) {
            for ($col = 0; $col < $keyLength; $col++) {
                if ($grid[$row][$col]) {
                    $result .= $grid[$row][$col];
                }
            }
        }

        // Remove padding X's at the end
        return rtrim($result, 'X');
    }

    public function getVisualizationSteps(string $text, string $key, string $mode = 'encrypt'): array
    {
        $steps = [];
        $cleanText = strtoupper(preg_replace('/\s+/', '', $text));
        $upperKey = strtoupper($key);
        $isEncrypt = $mode === 'encrypt';

        $steps[] = [
            'html' => '<div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold">
                            <strong>Step 1:</strong> ' . ($isEncrypt ? 'Encryption' : 'Decryption') . ' using Row-Column Transposition
                        </p>
                      </div>',
            'delay' => 500
        ];

        $steps[] = [
            'html' => '<div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700 rounded-lg mb-4">
                        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-2">
                            <strong>Keyword:</strong>
                            <span class="font-mono bg-indigo-100 dark:bg-indigo-800 px-2 py-1 rounded text-indigo-800 dark:text-indigo-200">' . $upperKey . '</span>
                        </p>
                        <p class="text-light-text-secondary dark:text-dark-text-secondary">
                            <strong>Text:</strong>
                            <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">' . $cleanText . '</span>
                        </p>
                      </div>',
            'delay' => 500
        ];

        if ($isEncrypt) {
            $keyLength = strlen($key);
            $numRows = (int) ceil(strlen($cleanText) / $keyLength);

            $steps[] = [
                'html' => '<div class="p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg mb-4">
                            <p class="text-light-text dark:text-dark-text font-semibold">
                                <strong>Step 2:</strong> Writing text row by row into
                                <span class="text-purple-600 dark:text-purple-400">' . $numRows . '×' . $keyLength . '</span> grid
                            </p>
                          </div>',
                'delay' => 500
            ];

            // Create grid
            $grid = [];
            $textIndex = 0;
            for ($row = 0; $row < $numRows; $row++) {
                $grid[$row] = [];
                for ($col = 0; $col < $keyLength; $col++) {
                    if ($textIndex < strlen($cleanText)) {
                        $grid[$row][$col] = $cleanText[$textIndex++];
                    } else {
                        $grid[$row][$col] = 'X';
                    }
                }
            }

            // Display grid
            $gridHtml = '<table style="border-collapse: collapse; margin: 1rem 0;"><thead><tr><th></th>';
            for ($i = 0; $i < $keyLength; $i++) {
                $gridHtml .= '<th style="border: 1px solid var(--border-color); padding: 0.5rem; background: var(--bg-secondary);">' . $upperKey[$i] . '</th>';
            }
            $gridHtml .= '</tr></thead><tbody>';
            for ($row = 0; $row < $numRows; $row++) {
                $gridHtml .= '<tr><td style="border: 1px solid var(--border-color); padding: 0.5rem; background: var(--bg-secondary); font-weight: bold;">Row ' . ($row + 1) . '</td>';
                for ($col = 0; $col < $keyLength; $col++) {
                    $gridHtml .= '<td style="border: 1px solid var(--border-color); padding: 0.5rem; text-align: center;">' . $grid[$row][$col] . '</td>';
                }
                $gridHtml .= '</tr>';
            }
            $gridHtml .= '</tbody></table>';

            $steps[] = [
                'html' => $gridHtml,
                'delay' => 1000
            ];

            $columnOrder = $this->getColumnOrder($key);
            $orderText = implode(' → ', array_map(function ($i) use ($upperKey) {
                return $upperKey[$i];
            }, $columnOrder));

            $steps[] = [
                'html' => '<p><strong>Step 3:</strong> Column order based on keyword alphabetical order: ' . $orderText . '</p>',
                'delay' => 800
            ];

            $steps[] = [
                'html' => '<p><strong>Step 4:</strong> Reading column by column in order</p>',
                'delay' => 500
            ];

            $result = '';
            foreach ($columnOrder as $colIndex) {
                for ($row = 0; $row < $numRows; $row++) {
                    $result .= $grid[$row][$colIndex];
                }
            }

            $steps[] = [
                'html' => '<p><strong>Result:</strong> ' . $result . '</p>',
                'delay' => 500
            ];
        } else {
            // Decryption
            $keyLength = strlen($key);
            $numRows = (int) ceil(strlen($cleanText) / $keyLength);

            $columnOrder = $this->getColumnOrder($key);
            $orderText = implode(' → ', array_map(function ($i) use ($upperKey) {
                return $upperKey[$i];
            }, $columnOrder));

            $steps[] = [
                'html' => '<p><strong>Step 2:</strong> Column order: ' . $orderText . '</p>',
                'delay' => 500
            ];

            $steps[] = [
                'html' => '<p><strong>Step 3:</strong> Filling grid column by column (inverse order)</p>',
                'delay' => 500
            ];

            $grid = array_fill(0, $numRows, array_fill(0, $keyLength, null));
            $textIndex = 0;

            foreach ($columnOrder as $colIndex) {
                for ($row = 0; $row < $numRows; $row++) {
                    if ($textIndex < strlen($cleanText)) {
                        $grid[$row][$colIndex] = $cleanText[$textIndex++];
                    }
                }
            }

            $gridHtml = '<table style="border-collapse: collapse; margin: 1rem 0;"><thead><tr><th></th>';
            for ($i = 0; $i < $keyLength; $i++) {
                $gridHtml .= '<th style="border: 1px solid var(--border-color); padding: 0.5rem; background: var(--bg-secondary);">' . $upperKey[$i] . '</th>';
            }
            $gridHtml .= '</tr></thead><tbody>';
            for ($row = 0; $row < $numRows; $row++) {
                $gridHtml .= '<tr><td style="border: 1px solid var(--border-color); padding: 0.5rem; background: var(--bg-secondary); font-weight: bold;">Row ' . ($row + 1) . '</td>';
                for ($col = 0; $col < $keyLength; $col++) {
                    $gridHtml .= '<td style="border: 1px solid var(--border-color); padding: 0.5rem; text-align: center;">' . ($grid[$row][$col] ?? '') . '</td>';
                }
                $gridHtml .= '</tr>';
            }
            $gridHtml .= '</tbody></table>';

            $steps[] = [
                'html' => $gridHtml,
                'delay' => 1000
            ];

            $steps[] = [
                'html' => '<p><strong>Step 4:</strong> Reading row by row</p>',
                'delay' => 500
            ];

            $result = '';
            for ($row = 0; $row < $numRows; $row++) {
                for ($col = 0; $col < $keyLength; $col++) {
                    if ($grid[$row][$col]) {
                        $result .= $grid[$row][$col];
                    }
                }
            }

            $result = rtrim($result, 'X');
            $steps[] = [
                'html' => '<p><strong>Result:</strong> ' . $result . '</p>',
                'delay' => 500
            ];
        }

        return $steps;
    }
}

