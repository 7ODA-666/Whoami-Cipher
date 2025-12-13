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
        $request->validate([
            'text' => 'required|string',
            'key' => 'required|integer|min:1|max:25'
        ]);

        try {
            $result = $this->cipherService->encrypt($request->text, (int) $request->key);
            $steps = $this->cipherService->getVisualizationSteps($request->text, (int) $request->key, 'encrypt');

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
            'key' => 'required|integer|min:1|max:25'
        ]);

        try {
            $result = $this->cipherService->decrypt($request->text, (int) $request->key);
            $steps = $this->cipherService->getVisualizationSteps($request->text, (int) $request->key, 'decrypt');

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

