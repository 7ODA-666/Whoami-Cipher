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
        $request->validate([
            'size' => 'required|integer|in:2,3'
        ]);

        try {
            $key = $this->cipherService->generateRandomKey((int) $request->size);

            return response()->json([
                'success' => true,
                'key' => $key
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function processEncrypt(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'key' => 'required|string',
            'size' => 'required|integer|in:2,3'
        ]);

        try {
            $result = $this->cipherService->encrypt($request->text, $request->key, (int) $request->size);
            $steps = $this->cipherService->getVisualizationSteps($request->text, $request->key, 'encrypt', (int) $request->size);

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
        $request->validate([
            'text' => 'required|string',
            'key' => 'required|string',
            'size' => 'required|integer|in:2,3'
        ]);

        try {
            $result = $this->cipherService->decrypt($request->text, $request->key, (int) $request->size);
            $steps = $this->cipherService->getVisualizationSteps($request->text, $request->key, 'decrypt', (int) $request->size);

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

