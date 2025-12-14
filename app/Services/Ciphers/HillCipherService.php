<?php

namespace App\Services\Ciphers;

class HillCipherService
{
    private int $modulus = 26;

    public function generateRandomKey(int $size): string
    {
        $maxAttempts = 100;
        $attempts = 0;

        while ($attempts < $maxAttempts) {
            $matrix = $this->generateRandomMatrix($size);

            // Check if determinant is coprime with 26
            $det = $this->determinant($matrix);
            $detMod = (($det % $this->modulus) + $this->modulus) % $this->modulus;

            if ($this->gcd($detMod, $this->modulus) === 1) {
                // Valid matrix found, convert to string
                return $this->matrixToString($matrix);
            }

            $attempts++;
        }

        // Fallback to a known valid matrix if random generation fails
        if ($size === 2) {
            return '6 24 1 13'; // Known valid 2x2 matrix
        } else {
            return '6 24 1 13 16 10 20 17 15'; // Known valid 3x3 matrix
        }
    }

    private function generateRandomMatrix(int $size): array
    {
        $matrix = [];
        for ($i = 0; $i < $size; $i++) {
            $matrix[$i] = [];
            for ($j = 0; $j < $size; $j++) {
                $matrix[$i][$j] = random_int(0, 25);
            }
        }
        return $matrix;
    }

    private function matrixToString(array $matrix): string
    {
        $values = [];
        foreach ($matrix as $row) {
            foreach ($row as $value) {
                $values[] = $value;
            }
        }
        return implode(' ', $values);
    }

    public function validateInput(string $text): bool
    {
        return preg_match('/^[A-Za-z\s]+$/', $text) === 1;
    }

    public function validateKey(string $key, int $size): bool
    {
        $matrix = $this->parseMatrix($key, $size);
        if (!$matrix) {
            return false;
        }

        $det = $this->determinant($matrix);
        $detMod = (($det % $this->modulus) + $this->modulus) % $this->modulus;
        return $this->gcd($detMod, $this->modulus) === 1;
    }

    public function parseMatrix(string $keyString, int $size): ?array
    {
        $values = array_map('intval', preg_split('/\s+/', trim($keyString)));
        if (count($values) !== $size * $size) {
            return null;
        }

        $matrix = [];
        for ($i = 0; $i < $size; $i++) {
            $matrix[] = array_slice($values, $i * $size, $size);
        }

        return $matrix;
    }

    private function determinant(array $matrix): int
    {
        $size = count($matrix);
        if ($size === 2) {
            return $matrix[0][0] * $matrix[1][1] - $matrix[0][1] * $matrix[1][0];
        } elseif ($size === 3) {
            return $matrix[0][0] * ($matrix[1][1] * $matrix[2][2] - $matrix[1][2] * $matrix[2][1]) -
                   $matrix[0][1] * ($matrix[1][0] * $matrix[2][2] - $matrix[1][2] * $matrix[2][0]) +
                   $matrix[0][2] * ($matrix[1][0] * $matrix[2][1] - $matrix[1][1] * $matrix[2][0]);
        }
        return 0;
    }

    private function gcd(int $a, int $b): int
    {
        while ($b !== 0) {
            $temp = $b;
            $b = $a % $b;
            $a = $temp;
        }
        return $a;
    }

    private function modInverse(int $a, int $m): ?int
    {
        $a = (($a % $m) + $m) % $m;
        for ($x = 1; $x < $m; $x++) {
            if (($a * $x) % $m === 1) {
                return $x;
            }
        }
        return null;
    }

    public function matrixInverse(array $matrix): ?array
    {
        $det = $this->determinant($matrix);
        $detMod = (($det % $this->modulus) + $this->modulus) % $this->modulus;
        $detInv = $this->modInverse($detMod, $this->modulus);

        if (!$detInv) {
            return null;
        }

        $size = count($matrix);
        if ($size === 2) {
            $adj = [
                [$matrix[1][1], -$matrix[0][1]],
                [-$matrix[1][0], $matrix[0][0]]
            ];
            return array_map(function ($row) use ($detInv) {
                return array_map(function ($val) use ($detInv) {
                    return ((($val * $detInv) % $this->modulus) + $this->modulus) % $this->modulus;
                }, $row);
            }, $adj);
        } elseif ($size === 3) {
            // Cofactor matrix
            $cofactor = [];
            for ($i = 0; $i < 3; $i++) {
                $cofactor[$i] = [];
                for ($j = 0; $j < 3; $j++) {
                    $minor = [];
                    for ($x = 0; $x < 3; $x++) {
                        if ($x !== $i) {
                            $row = [];
                            for ($y = 0; $y < 3; $y++) {
                                if ($y !== $j) {
                                    $row[] = $matrix[$x][$y];
                                }
                            }
                            $minor[] = $row;
                        }
                    }
                    $sign = (($i + $j) % 2 === 0) ? 1 : -1;
                    $cofactor[$i][$j] = $sign * $this->determinant($minor);
                }
            }

            // Transpose and multiply by inverse determinant
            $adj = [];
            for ($i = 0; $i < 3; $i++) {
                $adj[$i] = [];
                for ($j = 0; $j < 3; $j++) {
                    $adj[$i][$j] = $cofactor[$j][$i];
                }
            }

            return array_map(function ($row) use ($detInv) {
                return array_map(function ($val) use ($detInv) {
                    return ((($val * $detInv) % $this->modulus) + $this->modulus) % $this->modulus;
                }, $row);
            }, $adj);
        }

        return null;
    }

    private function textToNumbers(string $text): array
    {
        $text = strtoupper(preg_replace('/\s+/', '', $text));
        return array_map(function ($char) {
            return ord($char) - 65;
        }, str_split($text));
    }

    private function numbersToText(array $numbers): string
    {
        return implode('', array_map(function ($n) {
            $modVal = (($n % $this->modulus) + $this->modulus) % $this->modulus;
            return chr($modVal + 65);
        }, $numbers));
    }

    private function matrixMultiply(array $matrix, array $vector): array
    {
        $result = [];
        for ($i = 0; $i < count($matrix); $i++) {
            $sum = 0;
            for ($j = 0; $j < count($vector); $j++) {
                $sum += $matrix[$i][$j] * $vector[$j];
            }
            $result[] = (($sum % $this->modulus) + $this->modulus) % $this->modulus;
        }
        return $result;
    }

    public function encrypt(string $plaintext, string $key, int $size): string
    {
        if (empty($plaintext) || empty($key)) {
            return '';
        }

        if ($size !== 2 && $size !== 3) {
            throw new \InvalidArgumentException('Matrix size must be 2 or 3');
        }

        $matrix = $this->parseMatrix($key, $size);
        if (!$matrix) {
            throw new \InvalidArgumentException('Invalid key matrix format');
        }

        if (!$this->validateKey($key, $size)) {
            throw new \InvalidArgumentException('Matrix is not invertible (determinant must be coprime with 26)');
        }

        $numbers = $this->textToNumbers($plaintext);
        if (empty($numbers)) {
            return '';
        }

        // Pad if necessary
        while (count($numbers) % $size !== 0) {
            $numbers[] = 23; // X
        }

        $result = [];
        for ($i = 0; $i < count($numbers); $i += $size) {
            $block = array_slice($numbers, $i, $size);
            $encrypted = $this->matrixMultiply($matrix, $block);
            $result = array_merge($result, $encrypted);
        }

        return $this->numbersToText($result);
    }

    public function decrypt(string $ciphertext, string $key, int $size): string
    {
        if (empty($ciphertext) || empty($key)) {
            return '';
        }

        if ($size !== 2 && $size !== 3) {
            throw new \InvalidArgumentException('Matrix size must be 2 or 3');
        }

        $matrix = $this->parseMatrix($key, $size);
        if (!$matrix) {
            throw new \InvalidArgumentException('Invalid key matrix format');
        }

        $invMatrix = $this->matrixInverse($matrix);
        if (!$invMatrix) {
            throw new \InvalidArgumentException('Matrix is not invertible (determinant must be coprime with 26)');
        }

        $numbers = $this->textToNumbers($ciphertext);
        if (empty($numbers)) {
            return '';
        }

        if (count($numbers) % $size !== 0) {
            throw new \InvalidArgumentException('Ciphertext length must be a multiple of block size');
        }

        $result = [];
        for ($i = 0; $i < count($numbers); $i += $size) {
            $block = array_slice($numbers, $i, $size);
            $decrypted = $this->matrixMultiply($invMatrix, $block);
            $result = array_merge($result, $decrypted);
        }

        return $this->numbersToText($result);
    }

    public function getVisualizationSteps(string $text, string $key, string $mode = 'encrypt', int $size = 2): array
    {
        $steps = [];
        $matrix = $this->parseMatrix($key, $size);
        $isEncrypt = $mode === 'encrypt';

        $steps[] = [
            'html' => '<div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold">
                            <strong>Step 1:</strong> ' . $size . '×' . $size . ' Key Matrix
                        </p>
                      </div>',
            'delay' => 500
        ];

        // Display matrix with theme-aware styling
        $matrixHtml = '<div class="flex justify-center mb-4">
                        <table class="border-collapse bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg overflow-hidden">
                          <tbody>';
        for ($row = 0; $row < $size; $row++) {
            $matrixHtml .= '<tr>';
            for ($col = 0; $col < $size; $col++) {
                $matrixHtml .= '<td class="border border-light-border dark:border-dark-border bg-purple-50 dark:bg-purple-900/20 px-4 py-3 text-center text-light-text dark:text-dark-text font-mono font-semibold min-w-[60px]">' . $matrix[$row][$col] . '</td>';
            }
            $matrixHtml .= '</tr>';
        }
        $matrixHtml .= '</tbody></table></div>';

        $steps[] = [
            'html' => $matrixHtml,
            'delay' => 1000
        ];

        if (!$isEncrypt) {
            $invMatrix = $this->matrixInverse($matrix);
            $steps[] = [
                'html' => '<div class="p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg mb-4">
                            <p class="text-light-text dark:text-dark-text font-semibold">
                                <strong>Step 2:</strong> Inverse Matrix (for decryption)
                            </p>
                          </div>',
                'delay' => 500
            ];

            $invMatrixHtml = '<div class="flex justify-center mb-4">
                              <table class="border-collapse bg-light-card dark:bg-dark-card border border-light-border dark:border-dark-border rounded-lg overflow-hidden">
                                <tbody>';
            for ($row = 0; $row < $size; $row++) {
                $invMatrixHtml .= '<tr>';
                for ($col = 0; $col < $size; $col++) {
                    $invMatrixHtml .= '<td class="border border-light-border dark:border-dark-border bg-orange-50 dark:bg-orange-900/20 px-4 py-3 text-center text-light-text dark:text-dark-text font-mono font-semibold min-w-[60px]">' . $invMatrix[$row][$col] . '</td>';
                }
                $invMatrixHtml .= '</tr>';
            }
            $invMatrixHtml .= '</tbody></table></div>';

            $steps[] = [
                'html' => $invMatrixHtml,
                'delay' => 1000
            ];
        }

        $numbers = $this->textToNumbers($text);
        $paddedNumbers = $numbers;
        while (count($paddedNumbers) % $size !== 0) {
            $paddedNumbers[] = 23;
        }

        $steps[] = [
            'html' => '<div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700 rounded-lg mb-3">
                        <p class="text-light-text-secondary dark:text-dark-text-secondary">
                            <strong>Step ' . ($isEncrypt ? '2' : '3') . ':</strong> Convert text to numbers:
                            <span class="font-mono bg-indigo-100 dark:bg-indigo-800 px-2 py-1 rounded text-indigo-800 dark:text-indigo-200">' .
                            implode(', ', $numbers) . '</span>' .
                            (count($paddedNumbers) > count($numbers) ? ' <span class="text-amber-600 dark:text-amber-400">(padded with X)</span>' : '') . '
                        </p>
                      </div>',
            'delay' => 800
        ];

        $result = [];
        $workMatrix = $isEncrypt ? $matrix : $this->matrixInverse($matrix);

        for ($i = 0; $i < count($paddedNumbers); $i += $size) {
            $block = array_slice($paddedNumbers, $i, $size);
            $processed = $this->matrixMultiply($workMatrix, $block);
            $result = array_merge($result, $processed);

            $steps[] = [
                'html' => '<div class="p-3 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-700 rounded-lg mb-3">
                            <p class="text-light-text-secondary dark:text-dark-text-secondary">
                                <strong>Block ' . (($i / $size) + 1) . ':</strong>
                                <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">[' . implode(', ', $block) . ']</span>
                                × Matrix =
                                <span class="font-mono bg-teal-100 dark:bg-teal-800 px-2 py-1 rounded text-teal-800 dark:text-teal-200">[' . implode(', ', $processed) . ']</span>
                                <span class="text-teal-600 dark:text-teal-400">(mod 26)</span>
                            </p>
                          </div>',
                'delay' => 1200
            ];
        }

        $resultText = $this->numbersToText($result);
        $steps[] = [
            'html' => '<div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
                        <p class="text-light-text dark:text-dark-text font-bold text-lg">
                            <strong>Final Result:</strong>
                            <span class="font-mono bg-green-100 dark:bg-green-800 px-3 py-2 rounded text-green-800 dark:text-green-200">' . $resultText . '</span>
                        </p>
                      </div>',
            'delay' => 500
        ];

        return $steps;
    }
}

