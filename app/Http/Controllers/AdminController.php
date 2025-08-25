<?php
namespace App\Http\Controllers;

use App\Models\Bestelling;
use App\Models\Gerecht;
use App\Models\News;

class AdminController extends Controller
{
    public function index()
    {
        $orderCount   = Bestelling::count();
        $openOrders   = Bestelling::where('status', 'open')->count();
        $totalRevenue = Bestelling::sum('totaal');
        $dishCount    = Gerecht::count();
        $newsCount    = News::count();

        return view('admin.index', compact('orderCount','openOrders','totalRevenue','dishCount','newsCount'));
    }
}
