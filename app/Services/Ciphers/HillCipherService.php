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
            'html' => '<p><strong>Step 1:</strong> ' . $size . '×' . $size . ' Key Matrix</p>',
            'delay' => 500
        ];

        // Display matrix
        $matrixHtml = '<table style="border-collapse: collapse; margin: 1rem 0;"><tbody>';
        for ($row = 0; $row < $size; $row++) {
            $matrixHtml .= '<tr>';
            for ($col = 0; $col < $size; $col++) {
                $matrixHtml .= '<td style="border: 1px solid var(--border-color); padding: 0.5rem; text-align: center; width: 50px;">' . $matrix[$row][$col] . '</td>';
            }
            $matrixHtml .= '</tr>';
        }
        $matrixHtml .= '</tbody></table>';

        $steps[] = [
            'html' => $matrixHtml,
            'delay' => 1000
        ];

        if (!$isEncrypt) {
            $invMatrix = $this->matrixInverse($matrix);
            $steps[] = [
                'html' => '<p><strong>Step 2:</strong> Inverse Matrix (for decryption)</p>',
                'delay' => 500
            ];

            $invMatrixHtml = '<table style="border-collapse: collapse; margin: 1rem 0;"><tbody>';
            for ($row = 0; $row < $size; $row++) {
                $invMatrixHtml .= '<tr>';
                for ($col = 0; $col < $size; $col++) {
                    $invMatrixHtml .= '<td style="border: 1px solid var(--border-color); padding: 0.5rem; text-align: center; width: 50px;">' . $invMatrix[$row][$col] . '</td>';
                }
                $invMatrixHtml .= '</tr>';
            }
            $invMatrixHtml .= '</tbody></table>';

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
            'html' => '<p><strong>Step ' . ($isEncrypt ? '2' : '3') . ':</strong> Convert text to numbers: ' . implode(', ', $numbers) .
                     (count($paddedNumbers) > count($numbers) ? ' (padded with X)' : '') . '</p>',
            'delay' => 800
        ];

        $result = [];
        $workMatrix = $isEncrypt ? $matrix : $this->matrixInverse($matrix);

        for ($i = 0; $i < count($paddedNumbers); $i += $size) {
            $block = array_slice($paddedNumbers, $i, $size);
            $processed = $this->matrixMultiply($workMatrix, $block);
            $result = array_merge($result, $processed);

            $steps[] = [
                'html' => '<p><strong>Block ' . (($i / $size) + 1) . ':</strong> [' . implode(', ', $block) . '] × Matrix = [' . implode(', ', $processed) . '] (mod 26)</p>',
                'delay' => 1200
            ];
        }

        $resultText = $this->numbersToText($result);
        $steps[] = [
            'html' => '<p><strong>Result:</strong> ' . $resultText . '</p>',
            'delay' => 500
        ];

        return $steps;
    }
}

