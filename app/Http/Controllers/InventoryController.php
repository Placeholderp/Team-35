<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    public function adjustInventory(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'type' => 'required|string',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);
        
        $product = Product::findOrFail($data['product_id']);
        $product->adjustInventory(
            $data['quantity'],
            $data['type'],
            $data['reference'] ?? null,
            $data['notes'] ?? null
        );
        
        return response()->json(['message' => 'Inventory adjusted successfully', 'product' => $product]);
    }
    
    public function getMovements(Request $request)
    {
        $query = InventoryMovement::with(['product', 'createdBy'])
            ->latest();
            
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        return response()->json($query->paginate(15));
    }
    
    public function getLowStockProducts()
    {
        $products = Product::where('track_inventory', true)
            ->whereRaw('quantity <= reorder_level')
            ->get();
            
        return response()->json($products);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
