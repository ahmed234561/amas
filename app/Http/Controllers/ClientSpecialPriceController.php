<?php

namespace App\Http\Controllers;

use App\Models\ClientSpecialPrice;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Http\Request;
use Auth;

class ClientSpecialPriceController extends Controller
{
    public function __construct()
    {
        // التحقق من الصلاحيات
        $this->middleware(['permission:manage_special_prices|manage_own_special_prices']);
    }

    public function index(Request $request)
    {
        $query = ClientSpecialPrice::query()->with(['product', 'client']);

        // إذا لم يكن المستخدم أدمن، اعرض فقط الأسعار الخاصة به
        if (!auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        $specialPrices = $query->paginate(15);

        return view('backend.special_prices.index', compact('specialPrices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'product_id' => 'required|exists:products,id',
            'special_price' => 'required|numeric|min:0'
        ]);

        // التحقق من الصلاحيات
        if (!auth()->user()->hasRole('admin')) {
            // تحقق من أن المنتج يملكه البائع نفسه
            $product = Product::find($request->product_id);
            if ($product->user_id != auth()->id()) {
                flash(translate('You do not have permission to set special prices for this product.'))->error();
                return back();
            }
        }

        // تحقق من عدم وجود سعر خاص مسبق لنفس العميل والمنتج
        $existingPrice = ClientSpecialPrice::where('client_id', $request->client_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingPrice) {
            $existingPrice->update([
                'special_price' => $request->special_price,
                'user_id' => auth()->id(),
                'added_by' => auth()->user()->hasRole('admin') ? 'admin' : 'seller'
            ]);
            flash(translate('Special price has been updated successfully'))->success();
        } else {
            ClientSpecialPrice::create([
                'client_id' => $request->client_id,
                'product_id' => $request->product_id,
                'special_price' => $request->special_price,
                'user_id' => auth()->id(),
                'added_by' => auth()->user()->hasRole('admin') ? 'admin' : 'seller'
            ]);
            flash(translate('Special price has been added successfully'))->success();
        }

        return back();
    }

    public function destroy($id)
    {
        $specialPrice = ClientSpecialPrice::findOrFail($id);

        // التحقق من الصلاحيات
        if (!auth()->user()->hasRole('admin') && $specialPrice->user_id != auth()->id()) {
            flash(translate('You do not have permission to delete this special price.'))->error();
            return back();
        }

        $specialPrice->delete();
        flash(translate('Special price has been deleted successfully'))->success();
        return back();
    }

    public function getClientsForProduct($productId)
    {
        $product = Product::findOrFail($productId);

        // التحقق من الصلاحيات
        if (!auth()->user()->hasRole('admin') && $product->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $clients = Client::whereDoesntHave('specialPrices', function($query) use ($productId) {
            $query->where('product_id', $productId);
        })->get(['id', 'name']);

        return response()->json($clients);
    }
}
