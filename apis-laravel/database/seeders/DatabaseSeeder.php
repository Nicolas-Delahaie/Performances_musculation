<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Performance;
use App\Models\Muscle;
use App\Models\MuscleTravaille;
use App\Models\Exercice;
use App\Models\Programme;
use App\Models\ExerciceProgramme;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ------ 0 DEPENDANCE ------ 
        // USERS
        $users = [
            ['name' => 'Root', 'email' => 'root@root.root', 'password' => bcrypt('root')],
            ['name' => 'Root2', 'email' => 'root2@root.root', 'password' => bcrypt('root')]
        ];
        foreach ($users as $user) {
            User::create($user);
        }

        // MUSCLES
        $muscles = [
            ['nom' => 'pectoraux'],
            ['nom' => 'triceps'],
            ['nom' => 'épaules'],
            ['nom' => 'grand_dorsal'],
            ['nom' => 'trapezes'],
            ['nom' => 'biceps'],
            ['nom' => 'brachial'],
            ['nom' => 'ischios-jambiers'],
            ['nom' => 'lombaires'],
        ];
        foreach ($muscles as $muscle) {
            Muscle::create($muscle);
        }

        // EXERCICES
        $exercices = [
            ['nom' => 'Soulevé militaire', 'poidsDeCorps' => false],
            ['nom' => 'Développé couché', 'poidsDeCorps' => false],
            ['nom' => 'Développé couché décliné', 'poidsDeCorps' => false],
            ['nom' => 'Pompes', 'poidsDeCorps' => true],
            ['nom' => 'Tractions supination', 'poidsDeCorps' => true],
            ['nom' => 'Tractions pronation', 'poidsDeCorps' => true],
            ['nom' => 'Soulevé de terre', 'poidsDeCorps' => false],
            ['nom' => 'Astication de poulie', 'poidsDeCorps' => false],
        ];
        foreach ($exercices as $exercice) {
            Exercice::create($exercice);
        }

        // PROGRAMMES
        $programmes = [
            ['nom' => 'push'],
            ['nom' => 'pull'],
            ['nom' => 'legs'],
            //Faire attention a positionner ca derriere la creation de user
            ['nom' => 'lundi', 'createur_id' => 1],
            ['nom' => 'jeudi', 'createur_id' => 1],
        ];
        foreach ($programmes as $programme) {
            Programme::create($programme);
        }



        // ------ 1 DEPENDANCE ------ 
        // PERFORMANCES
        //Chaque exercices a plusieurs performances de chaque utilisateur
        foreach (Exercice::all() as $exo) {
            foreach (User::all() as $user) {
                $nbPerformances = rand(0, 10);
                for ($i = 0; $i < $nbPerformances; $i++) {
                    $charge = $exo->poidsDeCorps ?
                        (rand(1, 4) === 1 ? rand(1, 100) : null)
                        :
                        rand(20, 180);

                    Performance::create([
                        'date_perf' => now()->subDays(rand(0, 365)),
                        'repetitions' => rand(1, 12),
                        'charge' => $charge,
                        'user_id' => $user->id,
                        'exercice_id' => $exo->id,
                    ]);
                }
            }
        }

        // MUSCLES_TRAVAILLES
        //Chaque exercice travaille entre 1 et 3 muscles
        foreach (Exercice::all() as $exo) {
            $nbMusclesTravailles = rand(1, 4);
            $musclesTravailles = Muscle::inRandomOrder()->take($nbMusclesTravailles)->get();

            foreach ($musclesTravailles as $muscleTravaille) {
                MuscleTravaille::create([
                    'solicitation' => 1 / $nbMusclesTravailles,
                    'exercice_id' => $exo->id,
                    'muscle_id' => $muscleTravaille->id,
                ]);
            }
        }

        // EXERCICES_PROGRAMMES
        $exercicesProgrammes = [
            ['exercice_id' => 1, 'programme_id' => 1],
            ['exercice_id' => 2, 'programme_id' => 1],
            ['exercice_id' => 3, 'programme_id' => 1],
            ['exercice_id' => 4, 'programme_id' => 1],
            ['exercice_id' => 5, 'programme_id' => 2],
            ['exercice_id' => 6, 'programme_id' => 2],
            ['exercice_id' => 7, 'programme_id' => 3],
            ['exercice_id' => 8, 'programme_id' => 4],
            ['exercice_id' => 8, 'programme_id' => 5],
        ];
        foreach ($exercicesProgrammes as $exerciceProgramme) {
            ExerciceProgramme::create($exerciceProgramme);
        }
    }
}