<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0.01',
        ]);

        // Get the latest bid price
        $latestBidPrice = Bid::max('price');

        // Validate if the bid price is higher than the latest bid price
        if ($request->price <= $latestBidPrice) {
            return response()->json([
                'message' => 'The bid price cannot be lower than ' . $latestBidPrice,
                'errors' => [
                    'price' => [
                        'The bid price cannot be lower than ' . $latestBidPrice
                    ]
                ]
            ], 422);
        }

        // Create the bid
        $bid = Bid::create([
            'user_id' => $request->user_id,
            'price' => $request->price,
        ]);

        // Retrieve the full name of the user (assuming a User model with first_name and last_name fields)
        $user = \App\Models\User::find($request->user_id);
        $fullName = $user->first_name . ' ' . $user->last_name;

        // Return the success response
        return response()->json([
            'message' => 'Success',
            'data' => [
                'full_name' => $fullName,
                'price' => number_format($request->price, 2),
            ]
        ], 201);
    }
}
