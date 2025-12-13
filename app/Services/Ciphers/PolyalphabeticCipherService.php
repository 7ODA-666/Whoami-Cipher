<?php

namespace App\Services\Ciphers;

class PolyalphabeticCipherService
{
    private string $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function validateInput(string $text): bool
    {
        return preg_match('/^[A-Za-z\s]+$/', $text) === 1;
    }

    public function validateKey(string $key): bool
    {
        return preg_match('/^[A-Za-z]+$/', $key) === 1 && strlen($key) > 0;
    }

    private function prepareKey(string $key, int $length): string
    {
        $upperKey = strtoupper(preg_replace('/\s+/', '', $key));
        $keyStream = '';
        for ($i = 0; $i < $length; $i++) {
            $keyStream .= $upperKey[$i % strlen($upperKey)];
        }
        return $keyStream;
    }

    public function encrypt(string $plaintext, string $key): string
    {
        if (empty($plaintext) || empty($key)) {
            return '';
        }

        $upperText = strtoupper($plaintext);
        $textLength = strlen(preg_replace('/\s+/', '', $upperText));
        $keyStream = $this->prepareKey($key, $textLength);

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

            $keyChar = $keyStream[$keyIndex++];
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
        $textLength = strlen(preg_replace('/\s+/', '', $upperText));
        $keyStream = $this->prepareKey($key, $textLength);

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

            $keyChar = $keyStream[$keyIndex++];
            $keyPos = strpos($this->alphabet, $keyChar);
            if ($keyPos === false) {
                $result .= $char;
                continue;
            }

            $newPos = (($textPos - $keyPos) % 26 + 26) % 26;
            $result .= $this->alphabet[$newPos];
        }

        return $result;
    }

    public function getVisualizationSteps(string $text, string $key, string $mode = 'encrypt'): array
    {
        $steps = [];
        $upperText = strtoupper($text);
        $upperKey = strtoupper($key);
        $isEncrypt = $mode === 'encrypt';

        $steps[] = [
            'html' => '<p><strong>Step 1:</strong> ' . ($isEncrypt ? 'Encryption' : 'Decryption') . ' using Vigenère cipher</p>',
            'delay' => 500
        ];

        $steps[] = [
            'html' => '<p><strong>Keyword:</strong> ' . $upperKey . '</p><p><strong>Text:</strong> ' . $upperText . '</p>',
            'delay' => 800
        ];

        $keyStream = $this->prepareKey($key, strlen(preg_replace('/\s+/', '', $upperText)));
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
                $keyChar = $keyStream[$keyIndex];
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
                    $newPos = (($textPos - $keyPos) % 26 + 26) % 26;
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

