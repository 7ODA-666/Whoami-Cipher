<?php

namespace App\Http\Controllers\Algorithms;

use App\Http\Controllers\Controller;
use App\Services\Ciphers\HillCipherService;
use Illuminate\Http\Request;

class HillController extends Controller
{
    protected $cipherService;

    public function __construct(HillCipherService $cipherService)
    {
        $this->cipherService = $cipherService;
    }

    public function encryption()
    {
        return view('algorithms.hill.encryption');
    }

    public function decryption()
    {
        return view('algorithms.hill.decryption');
    }

    public function about()
    {
        return view('algorithms.hill.about');
    }

    public function generateKey(Request $request)
    {
        // Custom validation with specific error messages
        $validator = \Validator::make($request->all(), [
            'size' => 'required|integer|in:2,3'
        ], [
            'size.required' => 'Matrix size is required.',
            'size.integer' => 'Matrix size must be a valid integer.',
            'size.in' => 'Matrix size must be either 2 or 3.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        try {
            $key = $this->cipherService->generateRandomKey((int) $request->size);

            return response()->json([
                'success' => true,
                'key' => $key
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate valid key matrix.'
            ], 400);
        }
    }

    public function processEncrypt(Request $request)
    {
        // Custom validation with specific error messages
        $validator = \Validator::make($request->all(), [
            'text' => 'required|string',
            'key' => 'required|string',
            'size' => 'required|integer|in:2,3'
        ], [
            'text.required' => 'Text to encrypt is required.',
            'key.required' => 'Key matrix is required.',
            'key.string' => 'Key must be a valid string format.',
            'size.required' => 'Matrix size is required.',
            'size.integer' => 'Matrix size must be a valid integer.',
            'size.in' => 'Matrix size must be either 2 or 3.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        // Additional key format validation
        $key = trim($request->key);
        $size = (int) $request->size;
        $expectedValues = $size * $size;

        // Check if key contains only numbers and spaces
        if (!preg_match('/^[\d\s]+$/', $key)) {
            return response()->json([
                'success' => false,
                'error' => 'Key matrix must contain only numbers separated by spaces.'
            ], 422);
        }

        $values = array_filter(preg_split('/\s+/', $key));
        if (count($values) !== $expectedValues) {
            return response()->json([
                'success' => false,
                'error' => "Key matrix must contain exactly {$expectedValues} numbers for a {$size}x{$size} matrix."
            ], 422);
        }

        // Validate that all values are valid integers
        foreach ($values as $value) {
            if (!is_numeric($value) || (int)$value < 0 || (int)$value > 25) {
                return response()->json([
                    'success' => false,
                    'error' => 'All matrix values must be integers between 0 and 25.'
                ], 422);
            }
        }

        try {
            // Use service validation to check matrix invertibility
            if (!$this->cipherService->validateKey($key, $size)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Key matrix determinant is not invertible mod 26. Please use a different matrix.'
                ], 422);
            }

            $result = $this->cipherService->encrypt($request->text, $key, $size);
            $steps = $this->cipherService->getVisualizationSteps($request->text, $key, 'encrypt', $size);

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
            'key' => 'required|string',
            'size' => 'required|integer|in:2,3'
        ], [
            'text.required' => 'Text to decrypt is required.',
            'key.required' => 'Key matrix is required.',
            'key.string' => 'Key must be a valid string format.',
            'size.required' => 'Matrix size is required.',
            'size.integer' => 'Matrix size must be a valid integer.',
            'size.in' => 'Matrix size must be either 2 or 3.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        // Additional key format validation
        $key = trim($request->key);
        $size = (int) $request->size;
        $expectedValues = $size * $size;

        // Check if key contains only numbers and spaces
        if (!preg_match('/^[\d\s]+$/', $key)) {
            return response()->json([
                'success' => false,
                'error' => 'Key matrix must contain only numbers separated by spaces.'
            ], 422);
        }

        $values = array_filter(preg_split('/\s+/', $key));
        if (count($values) !== $expectedValues) {
            return response()->json([
                'success' => false,
                'error' => "Key matrix must contain exactly {$expectedValues} numbers for a {$size}x{$size} matrix."
            ], 422);
        }

        // Validate that all values are valid integers
        foreach ($values as $value) {
            if (!is_numeric($value) || (int)$value < 0 || (int)$value > 25) {
                return response()->json([
                    'success' => false,
                    'error' => 'All matrix values must be integers between 0 and 25.'
                ], 422);
            }
        }

        try {
            // Use service validation to check matrix invertibility
            if (!$this->cipherService->validateKey($key, $size)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Key matrix determinant is not invertible mod 26. Please use a different matrix.'
                ], 422);
            }

            $result = $this->cipherService->decrypt($request->text, $key, $size);
            $steps = $this->cipherService->getVisualizationSteps($request->text, $key, 'decrypt', $size);

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

