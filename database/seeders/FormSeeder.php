<?php

namespace Database\Seeders;

use App\Models\Form;
use Faker\Factory;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    function run(): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 500; ++$i) {
            $form = new Form();
            $form->nome = $faker->name;
            $form->cognome = $faker->lastName;
            $form->data_ricezione = $faker->dateTimeBetween('-2 year');
            $form->email = $faker->email;
            $form->messaggio = $faker->sentence(5);
            $form->save();
        }
    }
}
