<?php

namespace Database\Factories;

use App\Models\ResidenceImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File; // Pour les opérations de fichier
use Illuminate\Support\Str; // Pour générer des noms de fichiers uniques

class ResidenceImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResidenceImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Définissez un ensemble d'images de base à utiliser pour le seeding.
        // Assurez-vous que ces images existent dans 'database/seed_assets/residences'
        $availableImages = [
            'RN2_Appart-4.jpeg',
            'RN2_APPART.jpg',
            'RN8_Salon.jpg',
            // Ajoutez d'autres noms de fichiers d'images par défaut si vous en avez
        ];

        // Choisissez une image aléatoire parmi celles disponibles
        $imageName = $this->faker->randomElement($availableImages);

        // Définir les chemins source et destination
        $sourcePath = database_path('seed_assets/residences') . '/' . $imageName;
        $destinationDir = public_path('img/residences'); // Chemin où les images seront copiées
        $destinationPath = $destinationDir . '/' . $imageName; // Chemin de destination

        // Vérifiez si le répertoire de destination existe, sinon créez-le
        if (!File::isDirectory($destinationDir)) {
            File::makeDirectory($destinationDir, 0777, true, true);
        }

        // Copiez le fichier seulement si le fichier source existe
        if (File::exists($sourcePath)) {
            // Pour éviter les doublons de noms si les factories sont appelées plusieurs fois
            // Vous pouvez renommer l'image avec un préfixe unique ou un hash
            $uniqueImageName = Str::random(10) . '_' . $imageName;
            $finalDestinationPath = $destinationDir . '/' . $uniqueImageName;
            File::copy($sourcePath, $finalDestinationPath);
            $relativePath = 'img/residences/' . $uniqueImageName; // Le chemin relatif pour la DB
        } else {
            // Gérer le cas où l'image par défaut n'existe pas
            // Par exemple, utiliser une image placeholder ou journaliser une erreur
            $relativePath = 'img/placeholders/default_residence.jpg'; // Chemin d'une image placeholder par défaut
            // Ou loggez un avertissement: error_log("Seed asset not found: " . $sourcePath);
            // Créez une image par défaut si elle n'existe pas pour éviter les erreurs 404
            if (!File::exists(public_path($relativePath))) {
                // Vous pouvez même créer une image de placeholder si elle n'existe pas
                // require_once 'path/to/your/image_generator.php'; // Si vous avez un script pour générer des images
                // Ou simplement un log pour demander à l'utilisateur de la créer
                error_log("Placeholder image not found: " . public_path($relativePath));
            }
        }

        return [
            'chemin_image' => $relativePath,
            'est_principale' => $this->faker->boolean(20), // 20% de chance d'être l'image principale
            'order' => $this->faker->numberBetween(1, 10),
            // 'residence_id' sera défini par l'appel de la factory dans DatabaseSeeder
        ];
    }
}