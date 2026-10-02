<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Plano Free',
            'slug' => 'free',
            'description' => 'Ideal para estudantes e testes individuais.',
            'price_monthly' => 0.00,
            'price_yearly' => 0.00,
            'limits' => json_encode(['projects' => 3, 'members' => 1]),
        ]);

        Plan::create([
            'name' => 'Escopo Pro',
            'slug' => 'pro',
            'description' => 'Focado em prioridades, prazos e gestão avançada.',
            'price_monthly' => 19.90,
            'price_yearly' => 199.00,
            'limits' => json_encode(['projects' => 15, 'members' => 5]),
        ]);

        Plan::create([
            'name' => 'Escopo Equipe',
            'slug' => 'equipe',
            'description' => 'Permissões avançadas, suporte e histórico completo para times.',
            'price_monthly' => 49.90,
            'price_yearly' => 499.00,
            'limits' => json_encode(['projects' => -1, 'members' => 25]),
        ]);
    }
}
