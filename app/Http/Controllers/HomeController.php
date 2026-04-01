<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Staff;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredServices = Service::where('is_active', true)->take(4)->get();
        $staff = Staff::where('is_active', true)->take(4)->get();
        $reviews = Review::where('is_visible', true)->latest()->take(5)->get();

        return view('welcome', compact('featuredServices', 'staff', 'reviews'));
    }
}
