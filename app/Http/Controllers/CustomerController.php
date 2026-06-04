<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private function guardAdmin()
    {
        if (!session('is_admin_logged_in')) {
            return redirect()->route('admin.login')
                ->with('error', 'Anda harus login sebagai admin terlebih dahulu.');
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $customers = User::whereIn('role', ['pelanggan', 'customer'])
            ->orderBy('full_name')
            ->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function history($id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $customer = User::find($id);

        if (! $customer || ! in_array($customer->role, ['pelanggan', 'customer'])) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Pelanggan tidak ditemukan.');
        }

        $orders = Order::where('user_id', $customer->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.customers.history', compact('customer', 'orders'));
    }

    public function resetPassword(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $customer = User::find($id);

        if (! $customer || ! in_array($customer->role, ['pelanggan', 'customer'])) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Pelanggan tidak ditemukan.');
        }

        $customer->password = 'password123';
        $customer->save();

        return redirect()->route('admin.customers.index')
            ->with('success', "Password pelanggan '{$customer->full_name}' berhasil di-reset ke default.");
    }
}
