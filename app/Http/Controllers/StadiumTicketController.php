<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Stand;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StadiumTicketController extends Controller
{
    public function customerIndex()
    {
        $stands = Stand::with(['seats' => function ($query) {
            $query->orderBy('row')->orderBy('number');
        }])->get();

        return view('stadium.customer', compact('stands'));
    }

    public function adminIndex()
    {
        $stands = Stand::with(['seats' => function ($query) {
            $query->orderBy('row')->orderBy('number');
        }])->get();

        return view('stadium.admin', compact('stands'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'booking_type'   => 'required|in:online,on_the_spot',
            'seat_ids'       => 'required|array|min:1',
            'seat_ids.*'     => 'exists:seats,id',
        ]);

        return DB::transaction(function () use ($request) {
            // Lock baris kursi untuk menghindari race condition
            $seats = Seat::with('stand')
                ->whereIn('id', $request->seat_ids)
                ->lockForUpdate()
                ->get();

            // Cek apabila ada kursi yang sudah terisi
            $bookedSeats = $seats->where('status', 'booked');
            if ($bookedSeats->isNotEmpty()) {
                $conflictCodes = $bookedSeats->pluck('seat_code')->implode(', ');
                return back()->with('error', "Gagal! Kursi [{$conflictCodes}] sudah terisi atau baru saja dipesan orang lain.");
            }

            $totalAmount = $seats->sum(fn ($seat) => $seat->stand->price);

            $order = Order::create([
                'order_number'   => 'STD-' . strtoupper(Str::random(8)),
                'customer_name'  => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'booking_type'   => $request->booking_type,
                'total_amount'   => $totalAmount,
                'payment_status' => 'paid',
            ]);

            foreach ($seats as $seat) {
                $order->seats()->attach($seat->id, ['price' => $seat->stand->price]);
                $seat->update(['status' => 'booked']);
            }

            $redirectRoute = $request->booking_type === 'on_the_spot' ? 'admin.stadium' : 'customer.stadium';

            return redirect()->route($redirectRoute)->with(
                'success',
                "Pemesanan berhasil! No Tiket: {$order->order_number} | Atas Nama: {$order->customer_name} | Total: Rp " . number_format($totalAmount, 0, ',', '.')
            );
        });
    }
}