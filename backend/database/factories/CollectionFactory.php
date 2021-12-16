<?php

namespace Database\Factories;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Collection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(24),
            'description' => $this->faker->text(256),
            'inherit_information_to_resources' => $this->faker->boolean,
            'keywords_extracted_from_resources' => $this->faker->boolean,
            'publish_as_catalogue_of_resources' => $this->faker->boolean,
            'doi' => $this->faker->uuid,
            'publisher' => $this->faker->text(24),
            'embargo_date' => $this->faker->dateTime,
            'geospatial_coverage_calculated_from_resources' => $this->faker->boolean,
            'temporal_coverage_calculated_from_resources' => $this->faker->boolean,
            'findable_score' => $this->faker->randomFloat(2, 0, 10),
            'accessible_score' => $this->faker->randomFloat(2, 0, 10),
            'interoperable_score' => $this->faker->randomFloat(2, 0, 10),
            'reusable_score' => $this->faker->randomFloat(2, 0, 10),
            'fair_scoring' => $this->faker->randomFloat(2, 0, 10),
        ];
    }
}
