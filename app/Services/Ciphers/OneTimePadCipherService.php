<?php

namespace App\Services\Ciphers;

class OneTimePadCipherService
{
    private string $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function generateRandomKey(string $plaintext): string
    {
        $textWithoutSpaces = preg_replace('/\s+/', '', $plaintext);
        $length = strlen($textWithoutSpaces);

        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $this->alphabet[random_int(0, 25)];
        }

        return $key;
    }

    public function validateInput(string $text): bool
    {
        return preg_match('/^[A-Za-z\s]+$/', $text) === 1;
    }

    public function validateKey(string $key, string $text): bool
    {
        $textLength = strlen(preg_replace('/\s+/', '', $text));
        $keyLength = strlen(preg_replace('/\s+/', '', $key));
        return $keyLength === $textLength && preg_match('/^[A-Za-z\s]+$/', $key) === 1;
    }

    public function encrypt(string $plaintext, string $key): string
    {
        if (empty($plaintext) || empty($key)) {
            return '';
        }

        $upperText = strtoupper($plaintext);
        $upperKey = strtoupper(preg_replace('/\s+/', '', $key));

        $textLength = strlen(preg_replace('/\s+/', '', $upperText));
        if (strlen($upperKey) !== $textLength) {
            throw new \InvalidArgumentException("Key length must match text length (excluding spaces)");
        }

        $result = '';
        $keyIndex = 0;

        for ($i = 0; $i < strlen($upperText); $i++) {
            $char = $upperText[$i];

            if ($char === ' ') {
                $result .= ' ';
                continue;
            }

            $textPos = strpos($this->alphabet, $char);
            if ($textPos === false) {
                $result .= $char;
                continue;
            }

            $keyChar = $upperKey[$keyIndex++];
            $keyPos = strpos($this->alphabet, $keyChar);
            if ($keyPos === false) {
                $result .= $char;
                continue;
            }

            $newPos = ($textPos + $keyPos) % 26;
            $result .= $this->alphabet[$newPos];
        }

        return $result;
    }

    public function decrypt(string $ciphertext, string $key): string
    {
        if (empty($ciphertext) || empty($key)) {
            return '';
        }

        $upperText = strtoupper($ciphertext);
        $upperKey = strtoupper(preg_replace('/\s+/', '', $key));

        $textLength = strlen(preg_replace('/\s+/', '', $upperText));
        if (strlen($upperKey) !== $textLength) {
            throw new \InvalidArgumentException("Key length must match text length (excluding spaces)");
        }

        $result = '';
        $keyIndex = 0;

        for ($i = 0; $i < strlen($upperText); $i++) {
            $char = $upperText[$i];

            if ($char === ' ') {
                $result .= ' ';
                continue;
            }

            $textPos = strpos($this->alphabet, $char);
            if ($textPos === false) {
                $result .= $char;
                continue;
            }

            $keyChar = $upperKey[$keyIndex++];
            $keyPos = strpos($this->alphabet, $keyChar);
            if ($keyPos === false) {
                $result .= $char;
                continue;
            }

            $newPos = ((($textPos - $keyPos) % 26) + 26) % 26;
            $result .= $this->alphabet[$newPos];
        }

        return $result;
    }

    public function getVisualizationSteps(string $text, string $key, string $mode = 'encrypt'): array
    {
        $steps = [];
        $upperText = strtoupper($text);
        $upperKey = strtoupper(preg_replace('/\s+/', '', $key));
        $isEncrypt = $mode === 'encrypt';

        $steps[] = [
            'html' => '<div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold">
                            <strong>Step 1:</strong> ' . ($isEncrypt ? 'Encryption' : 'Decryption') . ' using One-Time Pad
                        </p>
                      </div>',
            'delay' => 500
        ];

        $steps[] = [
            'html' => '<div class="p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg mb-4">
                        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-2">
                            <strong>Key (same length as text):</strong>
                            <span class="font-mono bg-purple-100 dark:bg-purple-800 px-2 py-1 rounded text-purple-800 dark:text-purple-200 text-sm">' . $upperKey . '</span>
                        </p>
                        <p class="text-light-text-secondary dark:text-dark-text-secondary">
                            <strong>Text:</strong>
                            <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-sm">' . $upperText . '</span>
                        </p>
                      </div>',
            'delay' => 800
        ];

        $keyIndex = 0;
        $result = '';

        for ($index = 0; $index < strlen($upperText); $index++) {
            $char = $upperText[$index];

            if ($char === ' ') {
                $result .= ' ';
                $steps[] = [
                    'html' => '<div class="p-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg mb-3">
                                <p class="text-light-text-secondary dark:text-dark-text-secondary">
                                    <strong>Position ' . ($index + 1) . ':</strong>
                                    <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">"' . $char . '"</span>
                                    (space) →
                                    <span class="font-mono bg-green-100 dark:bg-green-800 px-2 py-1 rounded text-green-800 dark:text-green-200">"' . $char . '"</span>
                                </p>
                              </div>',
                    'delay' => 600
                ];
            } else {
                $textPos = strpos($this->alphabet, $char);
                $keyChar = $upperKey[$keyIndex];
                $keyPos = strpos($this->alphabet, $keyChar);

                if ($isEncrypt) {
                    $newPos = ($textPos + $keyPos) % 26;
                    $newChar = $this->alphabet[$newPos];
                    $result .= $newChar;
                    $steps[] = [
                        'html' => '<div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-lg mb-3">
                                    <p class="text-light-text-secondary dark:text-dark-text-secondary">
                                        <strong>Position ' . ($index + 1) . ':</strong>
                                        <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">"' . $char . '"</span>
                                        (<span class="text-emerald-600 dark:text-emerald-400">' . $textPos . '</span>) +
                                        <span class="font-mono bg-purple-100 dark:bg-purple-800 px-2 py-1 rounded text-purple-800 dark:text-purple-200">"' . $keyChar . '"</span>
                                        (<span class="text-emerald-600 dark:text-emerald-400">' . $keyPos . '</span>) =
                                        <span class="text-emerald-600 dark:text-emerald-400">' . $newPos . '</span> (mod 26) →
                                        <span class="font-mono bg-green-100 dark:bg-green-800 px-2 py-1 rounded text-green-800 dark:text-green-200">"' . $newChar . '"</span>
                                    </p>
                                  </div>',
                        'delay' => 700
                    ];
                } else {
                    $newPos = ((($textPos - $keyPos) % 26) + 26) % 26;
                    $newChar = $this->alphabet[$newPos];
                    $result .= $newChar;
                    $steps[] = [
                        'html' => '<div class="p-3 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg mb-3">
                                    <p class="text-light-text-secondary dark:text-dark-text-secondary">
                                        <strong>Position ' . ($index + 1) . ':</strong>
                                        <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">"' . $char . '"</span>
                                        (<span class="text-orange-600 dark:text-orange-400">' . $textPos . '</span>) -
                                        <span class="font-mono bg-purple-100 dark:bg-purple-800 px-2 py-1 rounded text-purple-800 dark:text-purple-200">"' . $keyChar . '"</span>
                                        (<span class="text-orange-600 dark:text-orange-400">' . $keyPos . '</span>) =
                                        <span class="text-orange-600 dark:text-orange-400">' . $newPos . '</span> (mod 26) →
                                        <span class="font-mono bg-green-100 dark:bg-green-800 px-2 py-1 rounded text-green-800 dark:text-green-200">"' . $newChar . '"</span>
                                    </p>
                                  </div>',
                        'delay' => 700
                    ];
                }
                $keyIndex++;
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

