<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\UserSpecialPrice;
use Illuminate\Http\Request;

class UserSpecialPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage_special_prices|manage_own_special_prices']);
    }


    public function index()
    {
        $specialPrices = UserSpecialPrice::with(['user', 'product'])->latest()->paginate(15);
        $products = Product::where('published', 1)
                          ->where('approved', 1)
                          ->get();
        $users = User::where('user_type', 'customer')->get();

        return view('backend.special_prices.index', compact('specialPrices', 'products', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'special_price' => 'required|numeric|min:0.01',
        ]);

        UserSpecialPrice::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
            ],
            [
                'special_price' => $request->special_price,
                'added_by' => auth()->id(),
            ]
        );

        flash(translate('Special price has been saved successfully'))->success();
        return redirect()->route('special-prices.index');
    }

    public function getUsers($productId)
    {
        $product = Product::findOrFail($productId);
        $users = User::where('user_type', 'customer')->get();
        return response()->json($users);
    }

    public function destroy($id)
    {
        UserSpecialPrice::destroy($id);
        flash(translate('Special price has been deleted successfully'))->success();
        return redirect()->route('special-prices.index');
    }
}
