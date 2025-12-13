<?php

namespace App\Http\Controllers\Algorithms;

use App\Http\Controllers\Controller;
use App\Services\Ciphers\RailFenceCipherService;
use Illuminate\Http\Request;

class RailFenceController extends Controller
{
    protected $cipherService;

    public function __construct(RailFenceCipherService $cipherService)
    {
        $this->cipherService = $cipherService;
    }

    public function encryption()
    {
        return view('algorithms.rail-fence.encryption');
    }

    public function decryption()
    {
        return view('algorithms.rail-fence.decryption');
    }

    public function about()
    {
        return view('algorithms.rail-fence.about');
    }

    public function processEncrypt(Request $request)
    {
        // Custom validation with specific error messages
        $validator = \Validator::make($request->all(), [
            'text' => 'required|string',
            'key' => 'required|numeric|integer|min:2|max:10'
        ], [
            'text.required' => 'Text to encrypt is required.',
            'key.required' => 'Number of rails is required.',
            'key.numeric' => 'Number of rails must be a valid integer.',
            'key.integer' => 'Number of rails must be a valid integer.',
            'key.min' => 'Number of rails must be at least 2.',
            'key.max' => 'Number of rails must not exceed 10.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        // Additional validation
        $key = (int) $request->key;
        $text = trim($request->text);

        // Check if text is empty after trimming
        if (empty($text)) {
            return response()->json([
                'success' => false,
                'error' => 'Text cannot be empty.'
            ], 422);
        }

        // Check if number of rails is not greater than text length
        if ($key > strlen($text)) {
            return response()->json([
                'success' => false,
                'error' => 'Number of rails cannot exceed the length of the text.'
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
            'key' => 'required|numeric|integer|min:2|max:10'
        ], [
            'text.required' => 'Text to decrypt is required.',
            'key.required' => 'Number of rails is required.',
            'key.numeric' => 'Number of rails must be a valid integer.',
            'key.integer' => 'Number of rails must be a valid integer.',
            'key.min' => 'Number of rails must be at least 2.',
            'key.max' => 'Number of rails must not exceed 10.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        // Additional validation
        $key = (int) $request->key;
        $text = trim($request->text);

        // Check if text is empty after trimming
        if (empty($text)) {
            return response()->json([
                'success' => false,
                'error' => 'Text cannot be empty.'
            ], 422);
        }

        // Check if number of rails is not greater than text length
        if ($key > strlen($text)) {
            return response()->json([
                'success' => false,
                'error' => 'Number of rails cannot exceed the length of the text.'
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

