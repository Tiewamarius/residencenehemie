@extends('adminauth.AdminDashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h3 class="text-3xl font-bold mb-6">Liste des Résidences</h3>
    
    @if($residences->isEmpty())
        <p>Aucune résidence trouvée.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($residences as $residence)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($residence->image)
                        <img src="{{ asset('storage/' . $residence->image) }}" alt="{{ $residence->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                            Pas d'image
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <h2 class="text-xl font-semibold mb-2">{{ $residence->name }}</h2>
                        
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($residence->description, 50) }}</p>
                        
                        <a href="#" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            Voir les détails
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection