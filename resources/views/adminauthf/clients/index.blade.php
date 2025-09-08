@extends('adminauth.AdminDashboard')
@section('content')

<div class="w-full px-6 py-6 mx-auto">
    <!-- Modal client réservation -->
    <div id="detailsModal"
        class="fixed inset-0 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-[0_0_25px_rgba(236,72,153,0.6)] w-11/12 md:w-2/3 p-8 relative animate-fadeIn">

            <!-- Bouton fermer -->
            <button id="closeModal"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-lg"></i>
            </button>

            <h2 class="text-2xl font-bold mb-4 text-gray-800 border-b pb-2">Séjours du client</h2>

            <!-- Contenu dynamique -->
            <div id="modalContent" class="space-y-3 max-h-[70vh] overflow-y-auto">
                <p class="text-gray-500">Chargement...</p>
            </div>

        </div>
    </div>


    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Clients</h2>
    </div>

    <div class="flex flex-col -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase"></th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Nom</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Email</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                            <tr>
                                <td class="px-6 py-3 text-sm">{{ $client->profile_picture }}</td>
                                <td class="px-6 py-3 text-sm">{{ $client->name }}</td>
                                <td class="px-6 py-3 text-sm">{{ $client->email }}</td>
                                <td class="px-6 py-3 text-sm flex gap-2">
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded details-btn"
                                        data-client-id="{{ $client->id }}">
                                        Détails
                                    </button>
                                    <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST"
                                        onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('detailsModal');
        const modalContent = document.getElementById('modalContent');
        const closeModal = document.getElementById('closeModal');

        document.querySelectorAll('.details-btn').forEach(button => {
            button.addEventListener('click', function() {
                const clientId = this.dataset.clientId;

                // Ouvrir la modal
                modal.classList.remove('hidden');
                modalContent.innerHTML = `<p class="text-gray-500">Chargement...</p>`;

                // Charger les séjours par AJAX
                fetch(`/admin/clients/${clientId}/bookings`)
                    .then(response => response.json())
                    .then(data => {
                        let html = `
                            <p class="text-gray-700"><strong>Nom:</strong> ${data.client.name}</p>
                            <p class="text-gray-700"><strong>Email:</strong> ${data.client.email}</p>
                            <h3 class="mt-4 font-semibold text-lg text-gray-800">Séjours :</h3>
                            <div class="overflow-x-auto mt-2">
                              <table class="w-full border-collapse border border-gray-200 text-sm">
                                <thead>
                                  <tr class="bg-gradient-to-r from-purple-600 to-pink-500 text-white">
                                    <th class="p-2 border">Résidence</th>
                                    <th class="p-2 border">Début</th>
                                    <th class="p-2 border">Fin</th>
                                    <th class="p-2 border">Statut</th>
                                  </tr>
                                </thead>
                                <tbody>`;

                        if (data.bookings.length > 0) {
                            data.bookings.forEach(b => {
                                html += `
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-2 border">${b.residence}</td>
                                        <td class="p-2 border">${b.date_debut}</td>
                                        <td class="p-2 border">${b.date_fin}</td>
                                        <td class="p-2 border">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                ${b.statut === 'approved' ? 'bg-green-100 text-green-700' :
                                                b.statut === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                                'bg-red-100 text-red-700'}">
                                                ${b.statut}
                                            </span>
                                        </td>
                                    </tr>`;
                            });
                        } else {
                            html += `<tr><td colspan="4" class="p-3 text-center text-gray-500">Aucun séjour trouvé</td></tr>`;
                        }

                        html += `</tbody></table></div>`;

                        // Injecter le contenu dans la modal
                        modalContent.innerHTML = html;
                    });
            });
        });

        // Fermer la modal avec bouton X
        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Fermer la modal en cliquant sur l’overlay
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>

@endsection