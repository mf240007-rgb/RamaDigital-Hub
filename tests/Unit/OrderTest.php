<?php

namespace Tests\Unit;

use App\Models\Order;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_it_calculates_the_initial_deposit_from_the_estimate(): void
    {
        $order = new Order([
            'estimasi_harga' => 100000,
            'total_harga' => 100000,
        ]);

        $this->assertSame(50000, $order->getDpAmount());
    }

    public function test_it_formats_uploaded_document_names_for_display(): void
    {
        $order = new Order([
            'file_dokumen_list' => [
                '1751974800_1_abc123_Contoh File.pdf',
                '1751974800_1_def456_Another File.docx',
            ],
        ]);

        $this->assertSame([
            'Contoh File.pdf',
            'Another File.docx',
        ], $order->getDokumenDisplayNames());
    }

    public function test_it_marks_confirmed_dp_as_deposit_confirmed(): void
    {
        $order = new Order(['payment_status' => 'dp_diterima']);

        $this->assertTrue($order->isDepositConfirmed());
    }

    public function test_it_does_not_mark_deposit_as_confirmed_for_pending_payment(): void
    {
        $order = new Order(['payment_status' => 'menunggu_konfirmasi']);

        $this->assertFalse($order->isDepositConfirmed());
    }

    public function test_it_calculates_remaining_balance_after_initial_deposit(): void
    {
        $order = new Order([
            'total_harga' => 150000,
            'dp_amount' => 50000,
        ]);

        $this->assertSame(100000, $order->getRemainingBalance());
    }

    public function test_it_returns_a_customer_display_state_for_rejected_payment(): void
    {
        $order = new Order([
            'status' => 'Menunggu Antrean',
            'payment_status' => 'ditolak',
        ]);

        $this->assertSame('ditolak', $order->getCustomerDisplayState());
    }

    public function test_it_prioritizes_cancelled_status_for_customer_display(): void
    {
        $order = new Order([
            'status' => 'dibatalkan',
            'payment_status' => 'menunggu_konfirmasi',
        ]);

        $this->assertSame('dibatalkan', $order->getCustomerDisplayState());
    }

    public function test_it_marks_orders_with_confirmed_dp_as_visible_to_admin(): void
    {
        $order = new Order(['payment_status' => 'dp_diterima']);

        $this->assertTrue($order->isVisibleInAdminQueue());
    }

    public function test_it_keeps_pending_dp_orders_out_of_admin_queue(): void
    {
        $order = new Order(['payment_status' => 'menunggu_konfirmasi']);

        $this->assertFalse($order->isVisibleInAdminQueue());
    }

    public function test_it_shows_orders_with_uploaded_dp_proof_in_admin_queue(): void
    {
        $order = new Order([
            'payment_status' => 'menunggu_konfirmasi',
            'bukti_bayar' => 'bukti.jpg',
        ]);

        $this->assertTrue($order->isVisibleInAdminQueue());
    }

    public function test_it_uses_the_latest_final_price_for_customer_display(): void
    {
        $order = new Order([
            'total_harga' => 120000,
            'harga_final' => 150000,
        ]);

        $this->assertSame(150000, $order->getDisplayTotalHarga());
    }

    public function test_it_marks_print_orders_without_dp_proof_as_needing_customer_payment_action(): void
    {
        $order = new Order([
            'item_type' => 'jasa',
            'status' => 'Menunggu Antrean',
            'payment_status' => 'menunggu_konfirmasi',
            'bukti_bayar' => null,
        ]);

        $this->assertTrue($order->needsCustomerPaymentAction());
    }

    public function test_it_marks_finished_print_orders_with_remaining_balance_as_needing_customer_payment_action(): void
    {
        $order = new Order([
            'item_type' => 'jasa',
            'status' => 'selesai',
            'payment_status' => 'dp_diterima',
            'harga_final' => 100000,
            'dp_amount' => 50000,
        ]);

        $this->assertTrue($order->needsCustomerPaymentAction());
    }

    public function test_it_shows_finished_print_orders_with_remaining_balance_as_waiting_for_remaining_payment(): void
    {
        $order = new Order([
            'item_type' => 'jasa',
            'status' => 'selesai',
            'payment_status' => 'dp_diterima',
            'harga_final' => 100000,
            'dp_amount' => 50000,
        ]);

        $this->assertSame('menunggu_pelunasan_sisa', $order->getCustomerDisplayState());
    }

    public function test_it_labels_finished_print_orders_waiting_for_remaining_payment_verification(): void
    {
        $order = new Order([
            'item_type' => 'jasa',
            'status' => 'selesai',
            'payment_status' => 'menunggu_konfirmasi',
            'harga_final' => 100000,
            'dp_amount' => 50000,
            'bukti_bayar' => 'bukti-sisa.jpg',
        ]);

        $this->assertSame('sisa_menunggu_konfirmasi', $order->getCustomerDisplayState());
    }

    public function test_it_does_not_mark_cancel_pending_orders_as_needing_customer_payment_action(): void
    {
        $order = new Order([
            'item_type' => 'jasa',
            'status' => 'Menunggu Antrean',
            'payment_status' => 'menunggu_persetujuan_batal',
            'bukti_bayar' => null,
        ]);

        $this->assertFalse($order->needsCustomerPaymentAction());
    }

    public function test_it_marks_atk_orders_without_payment_proof_as_needing_customer_payment_action(): void
    {
        $order = new Order([
            'item_type' => 'produk',
            'payment_status' => 'menunggu_konfirmasi',
            'bukti_bayar' => null,
        ]);

        $this->assertTrue($order->needsCustomerPaymentAction());
    }
}
