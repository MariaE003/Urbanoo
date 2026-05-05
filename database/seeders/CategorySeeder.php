<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Voirie',
                'description' => 'Nids-de-poule, chaussée abîmée, trottoirs cassés et problèmes de circulation.',
            ],
            [
                'name' => 'Éclairage public',
                'description' => 'Lampadaires éteints, câbles visibles et zones mal éclairées la nuit.',
            ],
            [
                'name' => 'Propreté',
                'description' => 'Déchets, dépôts sauvages, poubelles pleines et saleté dans les rues.',
            ],
            [
                'name' => 'Sécurité routière',
                'description' => 'Panneaux absents, marquage effacé, danger pour piétons et conducteurs.',
            ],
            [
                'name' => 'Espaces verts',
                'description' => 'Arbres non entretenus, pelouses abîmées et équipements de parc dégradés.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
