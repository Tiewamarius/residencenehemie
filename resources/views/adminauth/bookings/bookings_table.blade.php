<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom" style="background-color: #d8cbd2ff;">
                            <tr>
                                <th></th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Dates</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">N° Reservation</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Clients</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Appartements</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nb. personnes</th>
                                <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                            <tr>
                                <td>
                                    <span class="bg-orange px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                        <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="text-xs font-semibold leading-tight text-slate-400">Details</a> </span>
                                </td>
                                <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    @php
                                    $statusClasses = '';
                                    $statutNom = '';
                                    switch ($booking->statut) {
                                    case 'Attente':
                                    $statusClasses = 'bg-gradient-to-tl from-blue-600 to-blue-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white';
                                    $statutNom = 'Attente';
                                    break;
                                    case 'Confirmé':
                                    $statusClasses = 'bg-gradient-to-tl from-green-600 to-lime-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white';
                                    $statutNom = 'Confirmé';
                                    break;
                                    case 'Encours':
                                    $statusClasses = 'bg-gradient-to-tl from-blue-600 to-blue-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white';
                                    $statutNom = 'En sejour';
                                    break;
                                    case 'checked_out':
                                    $statusClasses = 'bg-gradient-to-tl from-blue-600 to-blue-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white';
                                    $statutNom = 'Parti';
                                    break;
                                    case 'Terminé':
                                    $statusClasses = 'bg-gradient-to-tl from-blue-600 to-blue-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white';
                                    $statutNom = 'Terminé';
                                    break;

                                    case 'Annulé':
                                    $statusClasses = 'bg-gradient-to-tl from-slate-600 to-slate-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white';
                                    $statutNom = 'Annulé';
                                    break;
                                    default:
                                    $statusClasses = 'from-red-600 to-rose-400';
                                    $statutNom = 'Annulée';
                                    break;
                                    }
                                    @endphp
                                    <span class="{{ $statusClasses }}">
                                        {{ $statutNom }}
                                    </span>
                                </td>
                                <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <span class="text-xs font-semibold leading-tight text-slate-400">{{ $booking->date_arrivee->format('d/m/y') }} au {{ $booking->date_depart->format('d/m/y') }}
                                    </span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <span class="text-xs font-semibold leading-tight text-slate-400">{{ $booking->numero_reservation }}</span>
                                </td>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <span class="text-xs font-semibold leading-tight text-slate-400">{{ $booking->user->name ?? 'N/A' }}</span>
                                </td>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <span class="text-xs font-semibold leading-tight text-slate-400">{{ $booking->residence->nom ?? 'N/A' }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 text-xs font-semibold leading-tight">{{ $booking->nombre_adultes }} adultes <mark>&</mark>{{ $booking->nombre_enfants }} enfants</p>

                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">

                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold leading-tight text-red-500" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')"> Supprimer </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@if ($bookings->lastPage() > 1)
<div class="pagination">
    {{ $bookings->withQueryString()->links() }}
</div>
@endif
<style>
    .bg-orange {
        background-color: #eb0a337a;
        color: white;
    }
</style>