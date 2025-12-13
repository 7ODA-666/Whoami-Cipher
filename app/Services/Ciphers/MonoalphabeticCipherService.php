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
            'html' => '<p><strong>Step 1:</strong> ' . ($isEncrypt ? 'Encryption' : 'Decryption') . ' using substitution key</p>',
            'delay' => 500
        ];

        $steps[] = [
            'html' => '<p><strong>Alphabet:</strong> ' . $this->alphabet . '</p><p><strong>Key:</strong> ' . $upperKey . '</p>',
            'delay' => 800
        ];

        $result = '';
        for ($index = 0; $index < strlen($upperText); $index++) {
            $char = $upperText[$index];

            if ($char === ' ') {
                $result .= ' ';
                $steps[] = [
                    'html' => '<p>Character ' . ($index + 1) . ': "' . $char . '" (space) → "' . $char . '"</p>',
                    'delay' => 600
                ];
            } else {
                if ($isEncrypt) {
                    $pos = strpos($this->alphabet, $char);
                    $newChar = $upperKey[$pos];
                    $result .= $newChar;
                    $steps[] = [
                        'html' => '<p>Character ' . ($index + 1) . ': "' . $char . '" → Position ' . $pos . ' in alphabet → "' . $newChar . '" from key</p>',
                        'delay' => 600
                    ];
                } else {
                    $pos = strpos($upperKey, $char);
                    $newChar = $this->alphabet[$pos];
                    $result .= $newChar;
                    $steps[] = [
                        'html' => '<p>Character ' . ($index + 1) . ': "' . $char . '" → Position ' . $pos . ' in key → "' . $newChar . '" from alphabet</p>',
                        'delay' => 600
                    ];
                }
            }
        }

        $steps[] = [
            'html' => '<p><strong>Result:</strong> ' . $result . '</p>',
            'delay' => 500
        ];

        return $steps;
    }
}

