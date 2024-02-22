<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => function () {
                return Customer::all()->random();
            },
            'receiver_name' => $this->faker->name,
            'receiver_phone_number' => $this->faker->phoneNumber,
            'receiver_address' => $this->faker->address,
            'total_price' => $this->faker->numberBetween(100000, 10000000),
            'status' => OrderStatusEnum::getRandomValue(),
            'transaction_id' => $this->faker->uuid,
        ];
    }
}
