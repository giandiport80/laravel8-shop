<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $name = $this->faker->words(2, true);

        return [
            'user_id' => $user->id,
            'sku' => $this->faker->isbn10,
            'type' => 'simple',
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $this->faker->randomFloat,
            'weight' => $this->faker->randomNumber,
            'status' => Product::ACTIVE,
        ];
    }
}
