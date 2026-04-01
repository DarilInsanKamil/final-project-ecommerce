<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::where('is_active', true);
        
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $services = $query->get();
        
        return view('services.index', compact('services'));
    }
}
