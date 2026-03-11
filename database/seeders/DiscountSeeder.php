<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount::create([
            'code' => 'DISCOUNT10',
            'type' => 'percentage',
            'amount' => 10,
            'expires_at' => now()->addMonth(1),
            'usage_limit' => 5,
            'status' => 'active',
        ]);
        Discount::create([
            'code' => 'DISCOUNT20',
            'type' => 'percentage',
            'amount' => 20,
            'expires_at' => now()->addMonth(1),
            'usage_limit' => 5,
            'status' => 'active',
        ]);
        Discount::create([
            'code' => '5OFF',
            'type' => 'fixed',
            'amount' => 5,
            'expires_at' => now()->addMonth(1),
            'usage_limit' => 5,
            'status' => 'active',
        ]);
        Discount::create([
            'code' => 'EXPIRED26',
            'type' => 'fixed',
            'amount' => 5,
            'expires_at' => now()->subMonth(1),
            'usage_limit' => 5,
            'status' => 'active',
        ]);
        Discount::create([
            'code' => 'INVALID26',
            'type' => 'percentage',
            'amount' => 5,
            'expires_at' => now()->addMonth(1),
            'usage_limit' => 5,
            'status' => 'inactive',
        ]);
    }
}
