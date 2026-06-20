<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Download dokumen yang diunggah pelanggan
     */
    public function downloadDokumen($orderId)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $order = Order::find($orderId);

        if (! $order || ! $order->file_dokumen) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
        }

        $path = storage_path('app/private/dokumen_cetak/' . $order->file_dokumen);

        if (!file_exists($path)) {
            $path = storage_path('app/dokumen_cetak/' . $order->file_dokumen);
        }

        if (! file_exists($path)) {
            return redirect()->back()->with('error', 'File dokumen tidak ditemukan di server.');
        }

        return response()->download($path, $order->file_dokumen);
    }
}
