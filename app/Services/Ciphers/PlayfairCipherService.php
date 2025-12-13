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
            'html' => '<p><strong>Step 1:</strong> Building 5×5 Playfair matrix</p>',
            'delay' => 500
        ];

        // Display matrix
        $matrixHtml = '<table style="border-collapse: collapse; margin: 1rem 0;"><tbody>';
        for ($row = 0; $row < 5; $row++) {
            $matrixHtml .= '<tr>';
            for ($col = 0; $col < 5; $col++) {
                $matrixHtml .= '<td style="border: 1px solid var(--border-color); padding: 0.5rem; text-align: center; width: 40px;">' . $matrix[$row * 5 + $col] . '</td>';
            }
            $matrixHtml .= '</tr>';
        }
        $matrixHtml .= '</tbody></table>';

        $steps[] = [
            'html' => $matrixHtml,
            'delay' => 1000
        ];

        $prepared = $isEncrypt ? $this->prepareText($text) : strtoupper(preg_replace('/\s+/', '', $text));
        $pairs = str_split($prepared, 2);
        $steps[] = [
            'html' => '<p><strong>Step 2:</strong> Prepared text (in pairs): ' . implode(' ', $pairs) . '</p>',
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
                'html' => '<p><strong>Pair ' . (($i / 2) + 1) . ':</strong> "' . $char1 . '" (' . $pos1['row'] . ',' . $pos1['col'] . ') and "' . $char2 . '" (' . $pos2['row'] . ',' . $pos2['col'] . ') → ' . $rule . ' → "' . $pair['char1'] . $pair['char2'] . '"</p>',
                'delay' => 1000
            ];
        }

        $steps[] = [
            'html' => '<p><strong>Result:</strong> ' . $result . '</p>',
            'delay' => 500
        ];

        return $steps;
    }
}

