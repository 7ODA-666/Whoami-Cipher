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
            'html' => '<p><strong>Step 1:</strong> ' . ($isEncrypt ? 'Encryption' : 'Decryption') . ' using One-Time Pad</p>',
            'delay' => 500
        ];

        $steps[] = [
            'html' => '<p><strong>Key (same length as text):</strong> ' . $upperKey . '</p><p><strong>Text:</strong> ' . $upperText . '</p>',
            'delay' => 800
        ];

        $keyIndex = 0;
        $result = '';

        for ($index = 0; $index < strlen($upperText); $index++) {
            $char = $upperText[$index];

            if ($char === ' ') {
                $result .= ' ';
                $steps[] = [
                    'html' => '<p>Position ' . ($index + 1) . ': "' . $char . '" (space) → "' . $char . '"</p>',
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
                        'html' => '<p>Position ' . ($index + 1) . ': "' . $char . '" (' . $textPos . ') + "' . $keyChar . '" (' . $keyPos . ') = ' . $newPos . ' (mod 26) → "' . $newChar . '"</p>',
                        'delay' => 700
                    ];
                } else {
                    $newPos = ((($textPos - $keyPos) % 26) + 26) % 26;
                    $newChar = $this->alphabet[$newPos];
                    $result .= $newChar;
                    $steps[] = [
                        'html' => '<p>Position ' . ($index + 1) . ': "' . $char . '" (' . $textPos . ') - "' . $keyChar . '" (' . $keyPos . ') = ' . $newPos . ' (mod 26) → "' . $newChar . '"</p>',
                        'delay' => 700
                    ];
                }
                $keyIndex++;
            }
        }

        $steps[] = [
            'html' => '<p><strong>Result:</strong> ' . $result . '</p>',
            'delay' => 500
        ];

        return $steps;
    }
}

