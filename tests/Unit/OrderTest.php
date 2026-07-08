<?php

namespace Tests\Unit;

use App\Models\Order;
use Tests\TestCase;

class OrderTest extends TestCase
{
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
}
