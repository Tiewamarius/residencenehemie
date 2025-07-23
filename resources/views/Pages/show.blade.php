<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Résidence - {{ $residence->nom }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-4">
        <a href="{{ route('residences.index') }}" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Retour à la liste</a>

        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $residence->nom }}</h1>

        <div class="bg-white rounded-lg shadow-md p-6">
            @if($residence->images->isNotEmpty())
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Il est bon de trier les images pour un affichage cohérent,
                         par exemple par la colonne 'order' si elle existe. --}}
                    @foreach($residence->images->sortBy('order') as $image)
                        {{-- Correction ici : utilisation de 'chemin_image' et 'asset()' --}}
                        <img src="{{ asset($image->chemin_image) }}" alt="{{ $image->description ?? 'Image de ' . $residence->nom }}" class="w-full h-64 object-cover rounded-lg shadow-sm">
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 mb-4">Aucune image disponible pour cette résidence.</p>
            @endif

            <p class="text-gray-700 text-lg mb-4"><strong>Adresse :</strong> {{ $residence->adresse }}</p>
            <p class="text-gray-700 mb-4"><strong>Description :</strong> {{ $residence->description }}</p>
            <p class="text-gray-700 mb-4"><strong>Ville :</strong> {{ $residence->ville }}</p>
            <p class="text-gray-700 mb-4"><strong>Pays :</strong> {{ $residence->pays }}</p>
            <p class="text-gray-700 mb-4"><strong>Nombre de chambres :</strong> {{ $residence->nombre_chambres }}</p>

            <h3 class="text-2xl font-bold text-gray-800 mt-8 mb-4">Types de chambres disponibles</h3>
            @if($residence->types->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($residence->types as $type)
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <h4 class="text-xl font-semibold text-gray-700">{{ $type->nom_type }}</h4>
                            <p class="text-gray-600">Capacité : {{ $type->capacite_adultes }} adultes, {{ $type->capacite_enfants }} enfants</p>
                            <p class="text-gray-600">Prix de base par nuit : {{ number_format($type->prix_base, 0, ',', ' ') }} XOF</p>
                            <p class="text-gray-600 text-sm mt-2">{{ Str::limit($type->description_type, 100) }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">Aucun type de chambre disponible pour cette résidence.</p>
            @endif

            {{-- Vous pouvez ajouter ici la logique pour afficher les équipements, avis, etc. --}}
        </div>
    </div>
</body>
</html>