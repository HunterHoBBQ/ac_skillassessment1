<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use Illuminate\Http\Request;
use App\Models\Notification;

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
    $userLastBidPrice = Bid::where('user_id', $request->user_id)->max('price');

// Check if there is a user_last_bid_price, if not, set it to 0
    $userLastBidPrice = $userLastBidPrice ?? 0.00;

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

    $saveNotification = Notification::create([
        'user_id' => $request->user_id,
        'latest_bid_price' => $latestBidPrice,
        'user_last_bid_price' => $userLastBidPrice,
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
