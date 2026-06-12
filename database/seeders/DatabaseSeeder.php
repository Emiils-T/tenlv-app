<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\Tournament;
use App\Models\TournamentRegistration;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(15)->create();

        /*
        Court::create([
            'name'=>"Tuvāk pie stadiona",
            'address'=>'Sporta iela 3, Limbaži, LV-4001',
            'surface_type' => 'Makslīgā zāle'
        ]);
        Court::create([
            'name'=>"Tuvāk pie bērnu laukuma",
            'address'=>'Sporta iela 3, Limbaži, LV-4001',
            'surface_type' => 'Makslīgā zāle'
        ]);
        Court::create([
            'name'=>"1",
            'address'=>'Skolas iela 11A, Ragana',
            'surface_type' => 'Sintētisks'
        ]);
        */
        Tournament::factory(4)->create();

        TournamentRegistration::create([

        ]);

    }
}
