<?php

namespace App\Http\Controllers;
use App\Models\Purchase;

class ReceiptController extends Controller
{
    public function show(Purchase $purchase)
    {
        return view('receipt')->with('purchase', $purchase);
    }
}
