<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(): View
    {
        $vendors = Vendor::with('user')->latest()->paginate(15);

        return view('admin.vendors.index', [
            'vendors' => $vendors,
        ]);
    }

    public function approve(Vendor $vendor): RedirectResponse
    {
        $vendor->update(['status' => 'approved']);

        return back()->with('success', 'Vendeur approuvé.');
    }

    public function reject(Vendor $vendor): RedirectResponse
    {
        $vendor->update(['status' => 'rejected']);

        return back()->with('success', 'Vendeur rejeté.');
    }
}