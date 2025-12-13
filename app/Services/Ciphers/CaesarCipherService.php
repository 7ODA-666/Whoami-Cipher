<?php

namespace App\Services\Ciphers;

class CaesarCipherService
{
    public function validateInput(string $text): bool
    {
        return preg_match('/^[A-Za-z\s]+$/', $text) === 1;
    }

    public function validateKey($key): bool
    {
        $shift = (int) $key;
        return $shift >= 1 && $shift <= 25;
    }

    public function encrypt(string $plaintext, int $shift): string
    {
        if (empty($plaintext)) {
            return '';
        }

        if ($shift < 1) {
            throw new \InvalidArgumentException('Shift must be a positive number.');
        }

        if ($shift > 25) {
            throw new \InvalidArgumentException('Shift must not exceed 25.');
        }

        return $this->transform($plaintext, $shift);
    }

    public function decrypt(string $ciphertext, int $shift): string
    {
        if (empty($ciphertext)) {
            return '';
        }

        if ($shift < 1) {
            throw new \InvalidArgumentException('Shift must be a positive number.');
        }

        if ($shift > 25) {
            throw new \InvalidArgumentException('Shift must not exceed 25.');
        }

        return $this->transform($ciphertext, -$shift);
    }

    private function transform(string $text, int $shift): string
    {
        if (empty($text)) {
            return '';
        }

        $result = '';
        $length = strlen($text);

        for ($i = 0; $i < $length; $i++) {
            $char = $text[$i];

            if ($char === ' ') {
                $result .= ' ';
                continue;
            }

            $isUpper = ctype_upper($char);
            $base = $isUpper ? ord('A') : ord('a');
            $code = ord($char) - $base;
            $shifted = (($code + $shift) % 26 + 26) % 26;
            $result .= chr($shifted + $base);
        }

        return $result;
    }

    public function getVisualizationSteps(string $text, int $shift, string $mode = 'encrypt'): array
    {
        $steps = [];
        $isEncrypt = $mode === 'encrypt';
        $operation = $isEncrypt ? $shift : -$shift;

        $steps[] = [
            'html' => '<p><strong>Step 1:</strong> Starting ' . $mode . 'ion with shift value: ' . $shift . '</p>',
            'delay' => 500
        ];

        $result = '';
        $chars = str_split($text);

        foreach ($chars as $index => $char) {
            if ($char === ' ') {
                $result .= ' ';
                $steps[] = [
                    'html' => '<p>Character ' . ($index + 1) . ': "' . $char . '" (space) → "' . $char . '"</p>',
                    'delay' => 800
                ];
            } else {
                $isUpper = ctype_upper($char);
                $base = $isUpper ? ord('A') : ord('a');
                $code = ord($char) - $base;
                $shifted = (($code + $operation) % 26 + 26) % 26;
                $newChar = chr($shifted + $base);
                $result .= $newChar;

                $steps[] = [
                    'html' => '<p>Character ' . ($index + 1) . ': "' . $char . '" (position ' . $code . ') ' .
                             ($isEncrypt ? '+' : '-') . ' ' . abs($shift) . ' = ' . $shifted . ' (mod 26) → "' . $newChar . '"</p>',
                    'delay' => 800
                ];
            }
        }

        $steps[] = [
            'html' => '<p><strong>Result:</strong> ' . $result . '</p>',
            'delay' => 500
        ];

        return $steps;
    }
}

