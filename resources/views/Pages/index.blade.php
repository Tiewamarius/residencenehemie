<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Résidences - Résidence Nehemie</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Nos Résidences</h1>

        @if($residences->isEmpty())
            <p class="text-gray-600">Aucune résidence disponible pour le moment.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($residences as $residence)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        {{-- Logique pour trouver l'image à afficher --}}
                        @php
                            // Tente de trouver l'image marquée comme principale (est_principale = true)
                            $mainImage = $residence->images->where('est_principale', true)->first();

                            // Si aucune image principale n'est trouvée, prend la première image disponible
                            if (!$mainImage) {
                                $mainImage = $residence->images->sortBy('order')->first();
                            }

                            // Détermine la source (src) de l'image
                            if ($mainImage) {
                                // IMPORTANT : Utiliser asset() si le chemin en DB est 'img/residences/...'
                                // et si le fichier est dans 'public/img/residences/' (scénario du seeder)
                                $imageSource = asset($mainImage->chemin_image);
                            } else {
                                // Si aucune image n'est associée à la résidence, utilise l'image par défaut
                                $imageSource = asset('images/default.jpg');
                            }
                        @endphp

                        <img src="{{ $imageSource }}" alt="{{ $residence->nom }}" class="w-full h-48 object-cover">

                        <div class="p-4">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $residence->nom }}</h2>
                            <p class="text-gray-600 mb-2">{{ Str::limit($residence->adresse, 70) }}</p>
                            <p class="text-gray-700 text-sm mb-4">{{ Str::limit($residence->description, 120) }}</p>
                            <a href="{{ route('residences.show', $residence->id) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>