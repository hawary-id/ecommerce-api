<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VouchersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Voucher::query()->delete();

        Voucher::create([
            'code' => 'DISCOUNT50',
            'discount' => 50,
        ]);

        Voucher::create([
            'code' => 'SAVE20',
            'discount' => 20,
        ]);
    }
}
