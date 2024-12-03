<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use database\factories\UserFactory;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_place_order()
    {
        $user = User::factory()->create();

        $orderData = [
            'userID' => $user->userID,
            'totalAmount' => 100.00,
            'orderDate' => now(),
        ];

        $response = $this->post('/place-order', $orderData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'userID' => $user->userID,
            'totalAmount' => 100.00,
        ]);
    }
}
