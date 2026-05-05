<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@urbanoo.test'],
            [
                'name' => 'Admin Urbanoo',
                'role' => 'admin',
                'is_active' => true,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $citizens = User::factory(8)->create();
        $categories = Category::all()->keyBy('name');

        $reportsData = [
            ['title' => 'Nid-de-poule dangereux près du marché central', 'description' => 'Un grand nid-de-poule ralentit les voitures et met en danger les motos à proximité du marché central.', 'latitude' => 33.5731100, 'longitude' => -7.5898430, 'status' => 'pending', 'category' => 'Voirie'],
            ['title' => 'Lampadaire éteint dans une ruelle résidentielle', 'description' => 'La ruelle reste complètement sombre le soir et les habitants ont du mal à circuler en sécurité.', 'latitude' => 34.0208820, 'longitude' => -6.8416500, 'status' => 'in_progress', 'category' => 'Éclairage public'],
            ['title' => 'Déchets ménagers accumulés devant une école', 'description' => 'Des sacs de déchets s’accumulent depuis plusieurs jours devant l’entrée d’une école primaire.', 'latitude' => 31.6294720, 'longitude' => -7.9810840, 'status' => 'pending', 'category' => 'Propreté'],
            ['title' => 'Passage piéton presque effacé', 'description' => 'Le marquage au sol est devenu très difficile à voir, surtout aux heures de pointe.', 'latitude' => 35.7594650, 'longitude' => -5.8339540, 'status' => 'resolved', 'category' => 'Sécurité routière'],
            ['title' => 'Arbre cassé dans un jardin public', 'description' => 'Une grosse branche s’est cassée et bloque une partie de l’allée dans le parc.', 'latitude' => 30.4277550, 'longitude' => -9.5981070, 'status' => 'in_progress', 'category' => 'Espaces verts'],
            ['title' => 'Trottoir abîmé près de la gare', 'description' => 'Le trottoir présente plusieurs fissures et gêne le passage des poussettes et fauteuils roulants.', 'latitude' => 34.2610100, 'longitude' => -6.5802000, 'status' => 'pending', 'category' => 'Voirie'],
            ['title' => 'Feu public clignote toute la nuit', 'description' => 'Le lampadaire clignote sans arrêt, ce qui dérange fortement les riverains.', 'latitude' => 32.3372500, 'longitude' => -6.3498300, 'status' => 'resolved', 'category' => 'Éclairage public'],
            ['title' => 'Conteneur déborde dans le quartier', 'description' => 'Le conteneur principal est plein et les déchets commencent à se répandre autour.', 'latitude' => 33.2316320, 'longitude' => -8.5007120, 'status' => 'pending', 'category' => 'Propreté'],
            ['title' => 'Panneau stop manquant à une intersection', 'description' => 'Le panneau stop n’est plus visible et les véhicules passent sans ralentir.', 'latitude' => 34.0331300, 'longitude' => -5.0002800, 'status' => 'in_progress', 'category' => 'Sécurité routière'],
            ['title' => 'Banc cassé dans une aire verte', 'description' => 'Un banc en bois est cassé au milieu du parc et n’est plus utilisable.', 'latitude' => 34.6813900, 'longitude' => -1.9085800, 'status' => 'pending', 'category' => 'Espaces verts'],
            ['title' => 'Chaussée déformée après les pluies', 'description' => 'Après les dernières pluies, la chaussée s’est affaissée sur plusieurs mètres.', 'latitude' => 35.1681300, 'longitude' => -5.2696900, 'status' => 'in_progress', 'category' => 'Voirie'],
            ['title' => 'Zone sombre près d’un arrêt de bus', 'description' => 'L’arrêt de bus n’est plus éclairé, ce qui complique l’attente le soir.', 'latitude' => 32.2993900, 'longitude' => -9.2371800, 'status' => 'pending', 'category' => 'Éclairage public'],
            ['title' => 'Décharge sauvage sur un terrain vide', 'description' => 'Des déchets de chantier et ménagers ont été déposés illégalement sur un terrain vide.', 'latitude' => 31.9314000, 'longitude' => -4.4243000, 'status' => 'resolved', 'category' => 'Propreté'],
            ['title' => 'Virage dangereux sans marquage clair', 'description' => 'Le virage est difficile à négocier car les lignes au sol sont presque effacées.', 'latitude' => 35.5784500, 'longitude' => -5.3683700, 'status' => 'pending', 'category' => 'Sécurité routière'],
            ['title' => 'Pelouse dégradée dans un square', 'description' => 'Une partie importante de la pelouse est détruite et l’arrosage semble arrêté.', 'latitude' => 33.8954300, 'longitude' => -5.5472700, 'status' => 'in_progress', 'category' => 'Espaces verts'],
        ];

        $imagePaths = Storage::disk('public')->files('reports');

        $reports = collect();

        foreach ($reportsData as $index => $data) {
            $report = Report::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'user_id' => $citizens[$index % $citizens->count()]->id,
                'category_id' => $categories[$data['category']]->id,
                'status' => $data['status'],
            ]);

            if (!empty($imagePaths)) {
                $selectedImages = collect($imagePaths)->shuffle()->take(rand(1, 2));

                foreach ($selectedImages as $imagePath) {
                    ReportImage::create([
                        'report_id' => $report->id,
                        'image_path' => $imagePath,
                    ]);
                }
            }

            $reports->push($report);
        }

        $commentsData = [
            'Le problème est toujours visible ce matin.',
            'Je confirme, la circulation devient compliquée ici.',
            'Merci de traiter ce point rapidement.',
            'La situation s’aggrave surtout le soir.',
            'Le quartier attend une intervention depuis plusieurs jours.',
            'Le danger est réel pour les piétons.',
            'Une équipe est déjà passée voir la zone.',
            'Le signalement est utile, merci pour le partage.',
        ];

        foreach ($commentsData as $index => $content) {
            $user = $index < 2 ? $admin : $citizens->random();

            Comment::create([
                'user_id' => $user->id,
                'report_id' => $reports->random()->id,
                'content' => $content,
                'is_admin' => $user->role === 'admin',
            ]);
        }

        foreach ($reports as $report) {
            $voters = $citizens->shuffle()->take(rand(0, 3));

            foreach ($voters as $voter) {
                Vote::firstOrCreate([
                    'user_id' => $voter->id,
                    'report_id' => $report->id,
                ]);
            }
        }
    }
}
