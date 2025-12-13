<?php

namespace App\Http\Controllers\Algorithms;

use App\Http\Controllers\Controller;
use App\Services\Ciphers\PolyalphabeticCipherService;
use Illuminate\Http\Request;

class PolyalphabeticController extends Controller
{
    protected $cipherService;

    public function __construct(PolyalphabeticCipherService $cipherService)
    {
        $this->cipherService = $cipherService;
    }

    public function encryption()
    {
        return view('algorithms.polyalphabetic.encryption');
    }

    public function decryption()
    {
        return view('algorithms.polyalphabetic.decryption');
    }

    public function about()
    {
        return view('algorithms.polyalphabetic.about');
    }

    public function processEncrypt(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'key' => 'required|string|regex:/^[A-Za-z]+$/'
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
            'key' => 'required|string|regex:/^[A-Za-z]+$/'
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

