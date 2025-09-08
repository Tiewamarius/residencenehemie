{{-- Tableau --}}
<div class="table-wrapper">
    <table id="reservations-table">
        <thead>
            <tr>
                <th>Statut</th>
                <th>Actions</th>
                <th>Numéro</th>
                <th>Résidence</th>
                <th>Date arrivée</th>
                <th>Date départ</th>
                <th>Prix total</th>
            </tr>
        </thead>
        <tbody>
            @if ($reservations->count() > 0)
            @foreach ($reservations as $reservation)
            <tr>
                <td>
                    <span class="status {{ $reservation->statut }}">
                        {{ $reservation->statut }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('bookings.details', $reservation->id) }}" class="link-btn">
                        détails
                    </a>
                </td>
                <td>{{ $reservation->numero_reservation }}</td>
                <td>{{ $reservation->residence->nom }}</td>
                <td>{{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}</td>
                <td>{{ number_format($reservation->total_price, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="7" class="empty-message">Aucune réservation trouvée pour le moment.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if ($reservations->lastPage() > 1)
<div class="pagination">
    {{ $reservations->withQueryString()->links() }}
</div>
@endif