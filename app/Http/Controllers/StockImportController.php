<?php

namespace App\Http\Controllers;

use App\Models\StockImport;
use Illuminate\Http\Request;

class StockImportController extends Controller
{
    public function history()
{
    return response()->json(
        StockImport::with('product', 'user')
        ->orderBy('import_date', 'desc')
        ->get()
    );
}

}
