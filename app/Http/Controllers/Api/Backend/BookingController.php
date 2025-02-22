<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;
        $start_date = $request->start_date ?? null;
        $end_date = $request->end_date ?? null;

        $product_booking = Booking::where('is_delete', 0);

        if (!empty($search)) {
            $product_booking->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        if (!empty($start_date) && !empty($end_date)) {
            $product_booking->whereBetween('start_date', [$start_date, $end_date])
                ->orWhereBetween('end_date', [$start_date, $end_date]);
        } elseif (!empty($start_date)) {
            $product_booking->whereDate('start_date', '>=', $start_date)
                ->orWhereDate('end_date', '>=', $start_date);
        } elseif (!empty($end_date)) {
            $product_booking->whereDate('start_date', '<=', $end_date)
                ->orWhereDate('end_date', '<=', $end_date);
        }

        $product_booking = $product_booking->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $product_booking
        ], 200);
    }

    public function store(Request $request)
    {
        $booking = new Booking();
        $booking->rent_id = $request->rent_id;
        $booking->user_id = $request->user_id;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->name = $request->name;
        $booking->phone = $request->phone;
        $booking->adult = $request->adult;
        $booking->child = $request->child;
        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully'
        ]);
    }

    public function show($id)
    {
        $booking = Booking::find($id);
        return response()->json([
            'success' => true,
            'data' => $booking
        ], 200);
    }

    public function update(Request $request)
    {
        $booking = Booking::find($request->id);
        $booking->rent_id = $request->rent_id;
        $booking->user_id = $request->user_id;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->name = $request->name;
        $booking->phone = $request->phone;
        $booking->adult = $request->adult;
        $booking->child = $request->child;
        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully'
        ], 200);
    }

    public function destroy(string $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $booking->is_delete = 1;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully'
        ], 200);
    }
}
