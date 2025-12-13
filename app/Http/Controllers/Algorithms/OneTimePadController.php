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
        $request->validate([
            'text' => 'required|string'
        ]);

        try {
            $key = $this->cipherService->generateRandomKey($request->text);

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
            'key' => 'required|string'
        ]);

        try {
            $result = $this->cipherService->encrypt($request->text, $request->key);
            $steps = $this->cipherService->getVisualizationSteps($request->text, $request->key, 'encrypt');

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
            'key' => 'required|string'
        ]);

        try {
            $result = $this->cipherService->decrypt($request->text, $request->key);
            $steps = $this->cipherService->getVisualizationSteps($request->text, $request->key, 'decrypt');

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

