<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeCartBadgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_cart_badge_when_guest_cart_has_items(): void
    {
        $response = $this->withSession([
            'cart_guest' => [
                1 => 1,
                2 => 1,
            ],
        ])->get('/');

        $response->assertOk();
        $response->assertSee('translate-middle">2</span>', false);
    }
}
