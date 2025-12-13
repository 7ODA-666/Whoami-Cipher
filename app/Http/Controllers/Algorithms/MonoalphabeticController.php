<?php

namespace App\Http\Controllers\Algorithms;

use App\Http\Controllers\Controller;
use App\Services\Ciphers\MonoalphabeticCipherService;
use Illuminate\Http\Request;

class MonoalphabeticController extends Controller
{
    protected $cipherService;

    public function __construct(MonoalphabeticCipherService $cipherService)
    {
        $this->cipherService = $cipherService;
    }

    public function encryption()
    {
        return view('algorithms.monoalphabetic.encryption');
    }

    public function decryption()
    {
        return view('algorithms.monoalphabetic.decryption');
    }

    public function about()
    {
        return view('algorithms.monoalphabetic.about');
    }

    public function generateKey()
    {
        try {
            $key = $this->cipherService->generateRandomKey();

            return response()->json([
                'success' => true,
                'key' => $key
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate random substitution key.'
            ], 400);
        }
    }

    public function processEncrypt(Request $request)
    {
        // Custom validation with specific error messages
        $validator = \Validator::make($request->all(), [
            'text' => 'required|string',
            'key' => 'required|string|size:26'
        ], [
            'text.required' => 'Text to encrypt is required.',
            'key.required' => 'Substitution key is required.',
            'key.string' => 'Key must be a valid string.',
            'key.size' => 'Key must be exactly 26 characters long.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        // Additional key validation
        $key = strtoupper($request->key);

        // Check if key contains only alphabetic characters
        if (!preg_match('/^[A-Z]+$/', $key)) {
            return response()->json([
                'success' => false,
                'error' => 'Key must contain only alphabetic characters.'
            ], 422);
        }

        // Check if key contains all 26 unique letters
        $keyArray = str_split($key);
        $uniqueLetters = array_unique($keyArray);

        if (count($uniqueLetters) !== 26) {
            return response()->json([
                'success' => false,
                'error' => 'Key must contain all 26 letters of the alphabet exactly once.'
            ], 422);
        }

        try {
            $result = $this->cipherService->encrypt($request->text, $key);
            $steps = $this->cipherService->getVisualizationSteps($request->text, $key, 'encrypt');

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
            'key' => 'required|string|size:26'
        ], [
            'text.required' => 'Text to decrypt is required.',
            'key.required' => 'Substitution key is required.',
            'key.string' => 'Key must be a valid string.',
            'key.size' => 'Key must be exactly 26 characters long.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        // Additional key validation
        $key = strtoupper($request->key);

        // Check if key contains only alphabetic characters
        if (!preg_match('/^[A-Z]+$/', $key)) {
            return response()->json([
                'success' => false,
                'error' => 'Key must contain only alphabetic characters.'
            ], 422);
        }

        // Check if key contains all 26 unique letters
        $keyArray = str_split($key);
        $uniqueLetters = array_unique($keyArray);

        if (count($uniqueLetters) !== 26) {
            return response()->json([
                'success' => false,
                'error' => 'Key must contain all 26 letters of the alphabet exactly once.'
            ], 422);
        }

        try {
            $result = $this->cipherService->decrypt($request->text, $key);
            $steps = $this->cipherService->getVisualizationSteps($request->text, $key, 'decrypt');

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

