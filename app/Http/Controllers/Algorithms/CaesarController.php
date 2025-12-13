<?php

namespace App\Http\Controllers\Algorithms;

use App\Http\Controllers\Controller;
use App\Services\Ciphers\CaesarCipherService;
use Illuminate\Http\Request;

class CaesarController extends Controller
{
    protected $cipherService;

    public function __construct(CaesarCipherService $cipherService)
    {
        $this->cipherService = $cipherService;
    }

    public function encryption()
    {
        return view('algorithms.caesar.encryption');
    }

    public function decryption()
    {
        return view('algorithms.caesar.decryption');
    }

    public function about()
    {
        return view('algorithms.caesar.about');
    }

    public function processEncrypt(Request $request)
    {
        // Custom validation with specific error messages
        $validator = \Validator::make($request->all(), [
            'text' => 'required|string',
            'key' => 'required|numeric|integer|min:1|max:25'
        ], [
            'key.required' => 'Shift key is required.',
            'key.numeric' => 'Shift must be a valid integer.',
            'key.integer' => 'Shift must be a valid integer.',
            'key.min' => 'Shift must be between 1 and 25.',
            'key.max' => 'Shift must not exceed 25.',
            'text.required' => 'Text to encrypt is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        try {
            $key = (int) $request->key;

            // Additional validation for edge cases
            if ($key <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Shift must be a positive number.'
                ], 422);
            }

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
            'key' => 'required|numeric|integer|min:1|max:25'
        ], [
            'key.required' => 'Shift key is required.',
            'key.numeric' => 'Shift must be a valid integer.',
            'key.integer' => 'Shift must be a valid integer.',
            'key.min' => 'Shift must be between 1 and 25.',
            'key.max' => 'Shift must not exceed 25.',
            'text.required' => 'Text to decrypt is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        try {
            $key = (int) $request->key;

            // Additional validation for edge cases
            if ($key <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Shift must be a positive number.'
                ], 422);
            }

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

