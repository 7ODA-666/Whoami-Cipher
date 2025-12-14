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
            'html' => '<div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold">
                            <strong>Step 1:</strong> Starting ' . $mode . 'ion with shift value:
                            <span class="text-blue-600 dark:text-blue-400">' . $shift . '</span>
                        </p>
                      </div>',
            'delay' => 500
        ];

        $result = '';
        $chars = str_split($text);

        foreach ($chars as $index => $char) {
            if ($char === ' ') {
                $result .= ' ';
                $steps[] = [
                    'html' => '<div class="p-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg mb-3">
                                <p class="text-light-text-secondary dark:text-dark-text-secondary">
                                    <strong>Character ' . ($index + 1) . ':</strong>
                                    <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">"' . $char . '"</span>
                                    (space) →
                                    <span class="font-mono bg-green-100 dark:bg-green-800 px-2 py-1 rounded text-green-800 dark:text-green-200">"' . $char . '"</span>
                                </p>
                              </div>',
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
                    'html' => '<div class="p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg mb-3">
                                <p class="text-light-text-secondary dark:text-dark-text-secondary">
                                    <strong>Character ' . ($index + 1) . ':</strong>
                                    <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">"' . $char . '"</span>
                                    (position <span class="text-purple-600 dark:text-purple-400">' . $code . '</span>)
                                    ' . ($isEncrypt ? '+' : '-') . ' ' . abs($shift) . ' =
                                    <span class="text-purple-600 dark:text-purple-400">' . $shifted . '</span> (mod 26) →
                                    <span class="font-mono bg-green-100 dark:bg-green-800 px-2 py-1 rounded text-green-800 dark:text-green-200">"' . $newChar . '"</span>
                                </p>
                              </div>',
                    'delay' => 800
                ];
            }
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

