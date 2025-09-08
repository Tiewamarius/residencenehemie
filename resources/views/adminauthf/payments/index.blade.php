@extends('adminauth.AdminDashboard')
@section('content')

<div class="w-full px-6 py-6 mx-auto">

    <h2 class="text-xl font-bold mb-4">Paiements</h2>

    <div class="flex flex-col -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">ID</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Réservation</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Client</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Montant</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Statut</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td class="px-6 py-3 text-sm">{{ $payment->id }}</td>
                                <td class="px-6 py-3 text-sm">{{ $payment->booking->numero_reservation ?? 'N/A' }}</td>
                                <td class="px-6 py-3 text-sm">{{ $payment->booking->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-3 text-sm">{{ number_format($payment->montant, 2) }} {{ $payment->currency }}</td>
                                <td class="px-6 py-3 text-sm">
                                    <span class="px-2 py-1 rounded {{ $payment->statut == 'unpaid' ? 'bg-gradient-to-tl from-slate-600 to-slate-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white' : 'bg-red-500 text-white' }}">
                                        {{ ucfirst($payment->statut) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="text-blue-500 hover:underline">Voir</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

@endsection