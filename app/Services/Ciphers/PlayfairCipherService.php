<?php

namespace App\Services\Ciphers;

class PlayfairCipherService
{
    private string $alphabet = 'ABCDEFGHIKLMNOPQRSTUVWXYZ'; // J is merged with I

    public function validateInput(string $text): bool
    {
        return preg_match('/^[A-Za-z\s]+$/', $text) === 1;
    }

    public function validateKey(string $key): bool
    {
        return preg_match('/^[A-Za-z]+$/', $key) === 1 && strlen($key) > 0;
    }

    private function prepareText(string $text): string
    {
        $prepared = strtoupper(preg_replace('/\s+/', '', $text));
        $prepared = str_replace('J', 'I', $prepared);

        $result = '';
        for ($i = 0; $i < strlen($prepared); $i++) {
            $result .= $prepared[$i];
            if ($i < strlen($prepared) - 1 && $prepared[$i] === $prepared[$i + 1]) {
                $result .= 'X';
            }
        }

        if (strlen($result) % 2 !== 0) {
            $result .= 'X';
        }

        return $result;
    }

    private function buildMatrix(string $key): array
    {
        $upperKey = strtoupper(str_replace('J', 'I', $key));
        $matrix = [];
        $used = [];

        // Add key characters
        for ($i = 0; $i < strlen($upperKey); $i++) {
            $char = $upperKey[$i];
            if (!in_array($char, $used) && strpos($this->alphabet, $char) !== false) {
                $matrix[] = $char;
                $used[] = $char;
            }
        }

        // Add remaining alphabet characters
        for ($i = 0; $i < strlen($this->alphabet); $i++) {
            $char = $this->alphabet[$i];
            if (!in_array($char, $used)) {
                $matrix[] = $char;
            }
        }

        return $matrix;
    }

    private function findPosition(array $matrix, string $char): array
    {
        $index = array_search($char, $matrix);
        return ['row' => intval($index / 5), 'col' => $index % 5];
    }

    private function getChar(array $matrix, int $row, int $col): string
    {
        return $matrix[$row * 5 + $col];
    }

    private function encryptPair(array $matrix, string $char1, string $char2): array
    {
        $pos1 = $this->findPosition($matrix, $char1);
        $pos2 = $this->findPosition($matrix, $char2);

        if ($pos1['row'] === $pos2['row']) {
            // Same row: shift right
            $newPos1 = ['row' => $pos1['row'], 'col' => ($pos1['col'] + 1) % 5];
            $newPos2 = ['row' => $pos2['row'], 'col' => ($pos2['col'] + 1) % 5];
        } elseif ($pos1['col'] === $pos2['col']) {
            // Same column: shift down
            $newPos1 = ['row' => ($pos1['row'] + 1) % 5, 'col' => $pos1['col']];
            $newPos2 = ['row' => ($pos2['row'] + 1) % 5, 'col' => $pos2['col']];
        } else {
            // Rectangle: swap columns
            $newPos1 = ['row' => $pos1['row'], 'col' => $pos2['col']];
            $newPos2 = ['row' => $pos2['row'], 'col' => $pos1['col']];
        }

        return [
            'char1' => $this->getChar($matrix, $newPos1['row'], $newPos1['col']),
            'char2' => $this->getChar($matrix, $newPos2['row'], $newPos2['col'])
        ];
    }

    private function decryptPair(array $matrix, string $char1, string $char2): array
    {
        $pos1 = $this->findPosition($matrix, $char1);
        $pos2 = $this->findPosition($matrix, $char2);

        if ($pos1['row'] === $pos2['row']) {
            // Same row: shift left
            $newPos1 = ['row' => $pos1['row'], 'col' => ($pos1['col'] - 1 + 5) % 5];
            $newPos2 = ['row' => $pos2['row'], 'col' => ($pos2['col'] - 1 + 5) % 5];
        } elseif ($pos1['col'] === $pos2['col']) {
            // Same column: shift up
            $newPos1 = ['row' => ($pos1['row'] - 1 + 5) % 5, 'col' => $pos1['col']];
            $newPos2 = ['row' => ($pos2['row'] - 1 + 5) % 5, 'col' => $pos2['col']];
        } else {
            // Rectangle: swap columns
            $newPos1 = ['row' => $pos1['row'], 'col' => $pos2['col']];
            $newPos2 = ['row' => $pos2['row'], 'col' => $pos1['col']];
        }

        return [
            'char1' => $this->getChar($matrix, $newPos1['row'], $newPos1['col']),
            'char2' => $this->getChar($matrix, $newPos2['row'], $newPos2['col'])
        ];
    }

    public function encrypt(string $plaintext, string $key): string
    {
        $matrix = $this->buildMatrix($key);
        $prepared = $this->prepareText($plaintext);
        $result = '';

        for ($i = 0; $i < strlen($prepared); $i += 2) {
            $pair = $this->encryptPair($matrix, $prepared[$i], $prepared[$i + 1]);
            $result .= $pair['char1'] . $pair['char2'];
        }

        return $result;
    }

    public function decrypt(string $ciphertext, string $key): string
    {
        $matrix = $this->buildMatrix($key);
        $upperText = strtoupper(preg_replace('/\s+/', '', $ciphertext));
        $result = '';

        for ($i = 0; $i < strlen($upperText); $i += 2) {
            $pair = $this->decryptPair($matrix, $upperText[$i], $upperText[$i + 1]);
            $result .= $pair['char1'] . $pair['char2'];
        }

        return $result;
    }

    public function getVisualizationSteps(string $text, string $key, string $mode = 'encrypt'): array
    {
        $steps = [];
        $matrix = $this->buildMatrix($key);
        $isEncrypt = $mode === 'encrypt';

        $steps[] = [
            'html' => '<div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold">
                            <strong>Step 1:</strong> Building 5×5 Playfair matrix
                        </p>
                      </div>',
            'delay' => 500
        ];

        // Display matrix with theme-aware styling
        $matrixHtml = '<div class="flex justify-center mb-4">
                        <table class="border-collapse bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg overflow-hidden">
                          <tbody>';
        for ($row = 0; $row < 5; $row++) {
            $matrixHtml .= '<tr>';
            for ($col = 0; $col < 5; $col++) {
                $matrixHtml .= '<td class="border border-light-border dark:border-dark-border bg-indigo-50 dark:bg-indigo-900/20 px-3 py-2 text-center text-light-text dark:text-dark-text font-mono font-semibold min-w-[40px]">' . $matrix[$row * 5 + $col] . '</td>';
            }
            $matrixHtml .= '</tr>';
        }
        $matrixHtml .= '</tbody></table></div>';

        $steps[] = [
            'html' => $matrixHtml,
            'delay' => 1000
        ];

        $prepared = $isEncrypt ? $this->prepareText($text) : strtoupper(preg_replace('/\s+/', '', $text));
        $pairs = str_split($prepared, 2);
        $steps[] = [
            'html' => '<div class="p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg mb-3">
                        <p class="text-light-text-secondary dark:text-dark-text-secondary">
                            <strong>Step 2:</strong> Prepared text (in pairs):
                            <span class="font-mono bg-purple-100 dark:bg-purple-800 px-2 py-1 rounded text-purple-800 dark:text-purple-200">' . implode(' ', $pairs) . '</span>
                        </p>
                      </div>',
            'delay' => 800
        ];

        $result = '';
        for ($i = 0; $i < strlen($prepared); $i += 2) {
            $char1 = $prepared[$i];
            $char2 = $prepared[$i + 1];
            $pos1 = $this->findPosition($matrix, $char1);
            $pos2 = $this->findPosition($matrix, $char2);

            if ($isEncrypt) {
                $pair = $this->encryptPair($matrix, $char1, $char2);
            } else {
                $pair = $this->decryptPair($matrix, $char1, $char2);
            }

            $result .= $pair['char1'] . $pair['char2'];

            $rule = '';
            if ($pos1['row'] === $pos2['row']) {
                $rule = 'Same row: shift ' . ($isEncrypt ? 'right' : 'left');
            } elseif ($pos1['col'] === $pos2['col']) {
                $rule = 'Same column: shift ' . ($isEncrypt ? 'down' : 'up');
            } else {
                $rule = 'Rectangle: swap columns';
            }

            $steps[] = [
                'html' => '<div class="p-3 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-700 rounded-lg mb-3">
                            <p class="text-light-text-secondary dark:text-dark-text-secondary">
                                <strong>Pair ' . (($i / 2) + 1) . ':</strong>
                                <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">"' . $char1 . '"</span>
                                (<span class="text-teal-600 dark:text-teal-400">' . $pos1['row'] . ',' . $pos1['col'] . '</span>) and
                                <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">"' . $char2 . '"</span>
                                (<span class="text-teal-600 dark:text-teal-400">' . $pos2['row'] . ',' . $pos2['col'] . '</span>) →
                                <span class="text-teal-700 dark:text-teal-300 font-medium">' . $rule . '</span> →
                                <span class="font-mono bg-green-100 dark:bg-green-800 px-2 py-1 rounded text-green-800 dark:text-green-200">"' . $pair['char1'] . $pair['char2'] . '"</span>
                            </p>
                          </div>',
                'delay' => 1000
            ];
        }

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
}

