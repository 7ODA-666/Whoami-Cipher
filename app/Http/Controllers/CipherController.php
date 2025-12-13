<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CipherController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Check if this is an AJAX request
        if (request()->ajax() || request()->wantsJson()) {
            return view('index')->render();
        }
        
        return view('index');
    }

}

