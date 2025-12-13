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
        $request->validate([
            'text' => 'required|string',
            'key' => 'required|integer|min:2|max:10'
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
            'key' => 'required|integer|min:2|max:10'
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

