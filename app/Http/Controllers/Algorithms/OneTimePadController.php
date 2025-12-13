<?php

namespace App\Http\Controllers\Algorithms;

use App\Http\Controllers\Controller;
use App\Services\Ciphers\OneTimePadCipherService;
use Illuminate\Http\Request;

class OneTimePadController extends Controller
{
    protected $cipherService;

    public function __construct(OneTimePadCipherService $cipherService)
    {
        $this->cipherService = $cipherService;
    }

    public function encryption()
    {
        return view('algorithms.one-time-pad.encryption');
    }

    public function decryption()
    {
        return view('algorithms.one-time-pad.decryption');
    }

    public function about()
    {
        return view('algorithms.one-time-pad.about');
    }

    public function generateKey(Request $request)
    {
        // Custom validation with specific error messages
        $validator = \Validator::make($request->all(), [
            'text' => 'required|string'
        ], [
            'text.required' => 'Text is required to generate a key of matching length.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        // Additional text validation
        $text = trim($request->text);
        if (empty($text)) {
            return response()->json([
                'success' => false,
                'error' => 'Text cannot be empty.'
            ], 422);
        }

        // Check if text contains only alphabetic characters and spaces
        if (!preg_match('/^[A-Za-z\s]+$/', $text)) {
            return response()->json([
                'success' => false,
                'error' => 'Text must contain only alphabetic characters and spaces.'
            ], 422);
        }

        try {
            $key = $this->cipherService->generateRandomKey($text);

            return response()->json([
                'success' => true,
                'key' => $key
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate random key.'
            ], 400);
        }
    }

    public function processEncrypt(Request $request)
    {
        // Custom validation with specific error messages
        $validator = \Validator::make($request->all(), [
            'text' => 'required|string',
            'key' => 'required|string'
        ], [
            'text.required' => 'Text to encrypt is required.',
            'key.required' => 'Key is required.',
            'key.string' => 'Key must be a valid string.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        // Additional validation
        $text = trim($request->text);
        $key = trim($request->key);

        // Check if text contains only alphabetic characters and spaces
        if (!preg_match('/^[A-Za-z\s]+$/', $text)) {
            return response()->json([
                'success' => false,
                'error' => 'Text must contain only alphabetic characters and spaces.'
            ], 422);
        }

        // Check if key contains only alphabetic characters
        if (!preg_match('/^[A-Za-z\s]*$/', $key)) {
            return response()->json([
                'success' => false,
                'error' => 'Key must contain only alphabetic characters.'
            ], 422);
        }

        // Validate key length matches text length (excluding spaces)
        $textLength = strlen(preg_replace('/\s+/', '', $text));
        $keyLength = strlen(preg_replace('/\s+/', '', $key));

        if ($keyLength !== $textLength) {
            return response()->json([
                'success' => false,
                'error' => "Key length ({$keyLength}) does not match text length ({$textLength}). Key must be exactly the same length as the text (excluding spaces)."
            ], 422);
        }

        try {
            $result = $this->cipherService->encrypt($text, $key);
            $steps = $this->cipherService->getVisualizationSteps($text, $key, 'encrypt');

            return response()->json([
                'success' => true,
                'result' => $result,
                'visualization' => $steps
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function processDecrypt(Request $request)
    {
        // Custom validation with specific error messages
        $validator = \Validator::make($request->all(), [
            'text' => 'required|string',
            'key' => 'required|string'
        ], [
            'text.required' => 'Text to decrypt is required.',
            'key.required' => 'Key is required.',
            'key.string' => 'Key must be a valid string.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        // Additional validation
        $text = trim($request->text);
        $key = trim($request->key);

        // Check if text contains only alphabetic characters and spaces
        if (!preg_match('/^[A-Za-z\s]+$/', $text)) {
            return response()->json([
                'success' => false,
                'error' => 'Text must contain only alphabetic characters and spaces.'
            ], 422);
        }

        // Check if key contains only alphabetic characters
        if (!preg_match('/^[A-Za-z\s]*$/', $key)) {
            return response()->json([
                'success' => false,
                'error' => 'Key must contain only alphabetic characters.'
            ], 422);
        }

        // Validate key length matches text length (excluding spaces)
        $textLength = strlen(preg_replace('/\s+/', '', $text));
        $keyLength = strlen(preg_replace('/\s+/', '', $key));

        if ($keyLength !== $textLength) {
            return response()->json([
                'success' => false,
                'error' => "Key length ({$keyLength}) does not match text length ({$textLength}). Key must be exactly the same length as the text (excluding spaces)."
            ], 422);
        }

        try {
            $result = $this->cipherService->decrypt($text, $key);
            $steps = $this->cipherService->getVisualizationSteps($text, $key, 'decrypt');

            return response()->json([
                'success' => true,
                'result' => $result,
                'visualization' => $steps
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}

