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
public function show($id)
{
    $item = StockImport::with('product', 'user')->find($id);

    if (!$item) {
        return response()->json([
            'message' => 'Không tìm thấy phiếu nhập'
        ], 404);
    }

    return response()->json($item);
}


}
