<?php

namespace App\Http\Controllers\Algorithms;

use App\Http\Controllers\Controller;
use App\Services\Ciphers\PlayfairCipherService;
use Illuminate\Http\Request;

class PlayfairController extends Controller
{
    protected $cipherService;

    public function __construct(PlayfairCipherService $cipherService)
    {
        $this->cipherService = $cipherService;
    }

    public function encryption()
    {
        return view('algorithms.playfair.encryption');
    }

    public function decryption()
    {
        return view('algorithms.playfair.decryption');
    }

    public function about()
    {
        return view('algorithms.playfair.about');
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

        // Additional key validation
        $key = trim($request->key);

        // Check if key is empty after trimming
        if (empty($key)) {
            return response()->json([
                'success' => false,
                'error' => 'Key cannot be empty.'
            ], 422);
        }

        // Check if key contains only alphabetic characters
        if (!preg_match('/^[A-Za-z]+$/', $key)) {
            return response()->json([
                'success' => false,
                'error' => 'Key must contain only alphabetic characters.'
            ], 422);
        }

        // Check minimum key length
        if (strlen($key) < 2) {
            return response()->json([
                'success' => false,
                'error' => 'Key must be at least 2 characters long.'
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

        // Additional key validation
        $key = trim($request->key);

        // Check if key is empty after trimming
        if (empty($key)) {
            return response()->json([
                'success' => false,
                'error' => 'Key cannot be empty.'
            ], 422);
        }

        // Check if key contains only alphabetic characters
        if (!preg_match('/^[A-Za-z]+$/', $key)) {
            return response()->json([
                'success' => false,
                'error' => 'Key must contain only alphabetic characters.'
            ], 422);
        }

        // Check minimum key length
        if (strlen($key) < 2) {
            return response()->json([
                'success' => false,
                'error' => 'Key must be at least 2 characters long.'
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

