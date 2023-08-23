<?php

namespace Database\Factories\Master;

use App\Http\Traits\GlobalTrait;
use App\Models\Master\RoleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleModelFactory extends Factory
{
    use GlobalTrait;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RoleModel::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'  => $this->faker->unique()->word,
            'akses' => json_encode($this->templateAkses()),
        ];
    }
}
