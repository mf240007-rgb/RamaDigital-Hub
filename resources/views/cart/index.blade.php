@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Keranjang Belanja</h2>

    @if(empty($products) || count($products) === 0)
        <div class="alert alert-info">Keranjang kosong.</div>
    @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                        <tr>
                            <td>{{ $p['product']->name_produk }}</td>
                            <td>Rp {{ number_format($p['product']->harga, 0, ',', '.') }}</td>
                            <td>
                                <form method="POST" action="{{ route('cart.update') }}" class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $p['product']->id }}">
                                    <input type="number" name="quantity" value="{{ $p['quantity'] }}" min="1" class="form-control me-2" style="width:100px;">
                                    <button class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td>Rp {{ number_format($p['subtotal'], 0, ',', '.') }}</td>
                            <td>
                                <form method="POST" action="{{ route('cart.remove') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $p['product']->id }}">
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
            <h4>Total: Rp {{ number_format($total, 0, ',', '.') }}</h4>
        </div>
    @endif
</div>
@endsection
