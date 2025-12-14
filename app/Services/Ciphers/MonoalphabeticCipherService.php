<?php

namespace App\Services\Ciphers;

class MonoalphabeticCipherService
{
    private string $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function generateRandomKey(): string
    {
        $alphabetArray = str_split($this->alphabet);
        shuffle($alphabetArray);
        return implode('', $alphabetArray);
    }

    public function validateInput(string $text): bool
    {
        return preg_match('/^[A-Za-z\s]+$/', $text) === 1;
    }

    public function validateKey(string $key): bool
    {
        if (strlen($key) !== 26) {
            return false;
        }

        $upperKey = strtoupper($key);
        $uniqueChars = count(array_unique(str_split($upperKey)));
        if ($uniqueChars !== 26) {
            return false;
        }

        return preg_match('/^[A-Z]+$/', $upperKey) === 1;
    }

    public function encrypt(string $plaintext, string $key): string
    {
        $upperKey = strtoupper($key);
        $upperText = strtoupper($plaintext);

        $result = '';
        for ($i = 0; $i < strlen($upperText); $i++) {
            $char = $upperText[$i];
            if ($char === ' ') {
                $result .= ' ';
            } else {
                $index = strpos($this->alphabet, $char);
                $result .= ($index !== false) ? $upperKey[$index] : $char;
            }
        }

        return $result;
    }

    public function decrypt(string $ciphertext, string $key): string
    {
        $upperKey = strtoupper($key);
        $upperText = strtoupper($ciphertext);

        $result = '';
        for ($i = 0; $i < strlen($upperText); $i++) {
            $char = $upperText[$i];
            if ($char === ' ') {
                $result .= ' ';
            } else {
                $index = strpos($upperKey, $char);
                $result .= ($index !== false) ? $this->alphabet[$index] : $char;
            }
        }

        return $result;
    }

    public function getVisualizationSteps(string $text, string $key, string $mode = 'encrypt'): array
    {
        $steps = [];
        $upperKey = strtoupper($key);
        $upperText = strtoupper($text);
        $isEncrypt = $mode === 'encrypt';

        $steps[] = [
            'html' => '<div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg mb-4">
                        <p class="text-light-text dark:text-dark-text font-semibold">
                            <strong>Step 1:</strong> ' . ($isEncrypt ? 'Encryption' : 'Decryption') . ' using substitution key
                        </p>
                      </div>',
            'delay' => 500
        ];

        $steps[] = [
            'html' => '<div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700 rounded-lg mb-4">
                        <p class="text-light-text-secondary dark:text-dark-text-secondary mb-2">
                            <strong>Standard Alphabet:</strong>
                            <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-sm">' . $this->alphabet . '</span>
                        </p>
                        <p class="text-light-text-secondary dark:text-dark-text-secondary">
                            <strong>Substitution Key:</strong>
                            <span class="font-mono bg-indigo-100 dark:bg-indigo-800 px-2 py-1 rounded text-indigo-800 dark:text-indigo-200 text-sm">' . $upperKey . '</span>
                        </p>
                      </div>',
            'delay' => 800
        ];

        $result = '';
        for ($index = 0; $index < strlen($upperText); $index++) {
            $char = $upperText[$index];

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
                    'delay' => 600
                ];
            } else {
                if ($isEncrypt) {
                    $pos = strpos($this->alphabet, $char);
                    $newChar = $upperKey[$pos];
                    $result .= $newChar;
                    $steps[] = [
                        'html' => '<div class="p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg mb-3">
                                    <p class="text-light-text-secondary dark:text-dark-text-secondary">
                                        <strong>Character ' . ($index + 1) . ':</strong>
                                        <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">"' . $char . '"</span> →
                                        Position <span class="text-purple-600 dark:text-purple-400 font-semibold">' . $pos . '</span> in alphabet →
                                        <span class="font-mono bg-green-100 dark:bg-green-800 px-2 py-1 rounded text-green-800 dark:text-green-200">"' . $newChar . '"</span> from key
                                    </p>
                                  </div>',
                        'delay' => 600
                    ];
                } else {
                    $pos = strpos($upperKey, $char);
                    $newChar = $this->alphabet[$pos];
                    $result .= $newChar;
                    $steps[] = [
                        'html' => '<div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg mb-3">
                                    <p class="text-light-text-secondary dark:text-dark-text-secondary">
                                        <strong>Character ' . ($index + 1) . ':</strong>
                                        <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">"' . $char . '"</span> →
                                        Position <span class="text-amber-600 dark:text-amber-400 font-semibold">' . $pos . '</span> in key →
                                        <span class="font-mono bg-green-100 dark:bg-green-800 px-2 py-1 rounded text-green-800 dark:text-green-200">"' . $newChar . '"</span> from alphabet
                                    </p>
                                  </div>',
                        'delay' => 600
                    ];
                }
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

