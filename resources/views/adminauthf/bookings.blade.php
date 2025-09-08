@extends('adminauth.AdminDashboard')

@section('content')
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>Bookings</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">N°Réservation</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Client</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Résidence</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Dates</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Personnes</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Statut</th>
                                <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-slate-400 opacity-70">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                            <tr>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 text-sm leading-normal">{{ $booking->numero_reservation }}</p>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <div class="flex px-2 py-1">
                                        <div>
                                        </div>
                                        <div class="flex flex-col justify-center">
                                            <h6 class="mb-0 text-sm leading-normal">{{ $booking->user->name ?? 'N/A' }}</h6>
                                            <p class="mb-0 text-xs leading-tight text-slate-400">{{ $booking->user->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 text-xs font-semibold leading-tight">{{ $booking->residence->nom ?? 'N/A' }}</p>
                                </td>
                                <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 text-xs font-semibold leading-tight">{{ $booking->type->nom ?? 'N/A' }}</p>
                                </td>
                                <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 text-xs font-semibold leading-tight">{{ $booking->date_arrivee }}</p>
                                    <p class="mb-0 text-xs leading-tight text-slate-400">au</p>
                                    <p class="mb-0 text-xs font-semibold leading-tight">{{ $booking->date_depart }}</p>
                                </td>
                                <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 text-xs font-semibold leading-tight">{{ $booking->nombre_adultes }} adultes</p>
                                    <p class="mb-0 text-xs leading-tight text-slate-400">{{ $booking->nombre_enfants }} enfants</p>
                                </td>
                                <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    @php
                                    $statusClasses = '';
                                    $statutNom = '';
                                    switch ($booking->statut) {
                                    case 'pending':
                                    $statusClasses = 'from-orange-600 to-orange-400';
                                    $statutNom = 'En attente';
                                    break;
                                    case 'confirmed':
                                    $statusClasses = 'from-green-600 to-lime-400';
                                    $statutNom = 'Confirmée';
                                    break;
                                    case 'checked_in':
                                    $statusClasses = 'from-purple-600 to-fuchsia-400';
                                    $statutNom = 'Arrivé';
                                    break;
                                    case 'checked_out':
                                    $statusClasses = 'from-slate-600 to-slate-300';
                                    $statutNom = 'Parti';
                                    break;
                                    case 'completed':
                                    $statusClasses = 'from-blue-600 to-cyan-400';
                                    $statutNom = 'Terminée';
                                    break;

                                    case 'canceled':
                                    $statusClasses = 'from-blue-600 to-red-400';
                                    $statutNom = 'Annulé';
                                    break;
                                    default:
                                    $statusClasses = 'from-red-600 to-rose-400';
                                    $statutNom = 'Annulée';
                                    break;
                                    }
                                    @endphp
                                    <span class="bg-gradient-to-tl {{ $statusClasses }} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                        {{ $statutNom }}
                                    </span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="text-xs font-semibold leading-tight text-slate-400"> Voir </a>
                                    <a href="{{ route('bookings.edit', $booking->id) }}" class="text-xs font-semibold leading-tight text-slate-400"> Modifier </a>
                                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="inline">
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

@endsection