 @extends('layouts.myapp')

 @section('title', 'Détails de la réservation - Résidences Nehemie')
 <style>
     /* Layout base */
     .booking-show {
         max-width: 1100px;
         margin: 80px auto 40px;
         /* centré + espace sous le header */
         padding: 0 1rem;
         /* un peu de padding latéral */
     }

     .back-link {
         display: inline-flex;
         align-items: center;
         gap: .5rem;
         color: #374151;
         margin-bottom: 1rem;
         text-decoration: none;
     }

     .back-link:hover {
         color: #111827;
     }

     .card {
         background: #fff;
         border-radius: 16px;
         box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
         padding: 1.25rem;
         margin-bottom: 1.25rem;
     }

     .header-card {
         display: flex;
         justify-content: space-between;
         align-items: center;
     }

     .header-card h1 {
         font-size: 1.5rem;
         margin: 0 0 .25rem;
         color: #111827;
     }

     .muted {
         color: #6b7280;
         font-size: .95rem;
     }

     .copy-ref {
         border: none;
         background: transparent;
         color: #111827;
         cursor: pointer;
         padding: 0 .25rem;
         border-radius: 6px;
     }

     .copy-ref:hover {
         background: #f3f4f6;
     }

     .grid-2 {
         display: grid;
         grid-template-columns: 1.2fr .8fr;
         gap: 1.25rem;
     }

     @media (max-width: 1024px) {
         .grid-2 {
             grid-template-columns: 1fr;
         }
     }

     .residence-head {
         display: flex;
         gap: 1rem;
         align-items: center;
     }

     .residence-head img {
         width: 140px;
         height: 100px;
         object-fit: cover;
         border-radius: 12px;
         background: #f3f4f6;
     }

     .residence-head .info h2 {
         margin: 0;
         font-size: 1.25rem;
         color: #111827;
     }

     .residence-head .info p {
         margin: .25rem 0 0;
     }

     .details-grid {
         display: grid;
         grid-template-columns: repeat(2, minmax(0, 1fr));
         gap: 1rem;
         margin-top: 1rem;
     }

     .details-grid label {
         display: block;
         font-size: .8rem;
         color: #6b7280;
         margin-bottom: .25rem;
     }

     .details-grid p {
         margin: 0;
         color: #111827;
         font-weight: 500;
     }

     /* Price table with horizontal scroll space */
     .scroll-x {
         overflow-x: auto;
         padding-bottom: .5rem;
     }

     .block-gap {
         margin-top: .75rem;
     }

     .price-table {
         width: 640px;
         border-collapse: collapse;
     }

     .price-table th,
     .price-table td {
         border-bottom: 1px solid #e5e7eb;
         padding: .75rem 1rem;
         text-align: left;
     }

     .price-table thead {
         background: #f9fafb;
     }

     .price-table .text-right {
         text-align: right;
     }

     .price-table tr.total td {
         font-weight: 700;
         color: #111827;
         border-top: 2px solid #e5e7eb;
     }

     /* Buttons */
     .actions {
         display: flex;
         flex-wrap: wrap;
         gap: .5rem;
         margin-top: 1rem;
     }

     .btn {
         display: inline-flex;
         align-items: center;
         gap: .5rem;
         padding: .625rem 1rem;
         border-radius: 10px;
         font-weight: 600;
         text-decoration: none;
         border: 1px solid transparent;
         cursor: pointer;
     }

     .btn.primary {
         background: #4f46e5;
         color: #fff;
     }

     .btn.primary:hover {
         background: #4338ca;
     }

     .btn.outline {
         background: #fff;
         color: #1f2937;
         border-color: #e5e7eb;
     }

     .btn.outline:hover {
         background: #f9fafb;
     }

     .btn.danger {
         background: #ef4444;
         color: #fff;
     }

     .btn.danger:hover {
         background: #dc2626;
     }

     .inline-form {
         display: inline;
     }

     /* Badges statut */
     .badge {
         padding: .35rem .6rem;
         border-radius: 9999px;
         font-size: .8rem;
         font-weight: 700;
     }

     .status.pending {
         background: #fffbeb;
         color: #a16207;
     }

     .status.confirmed {
         background: #f0fdf4;
         color: #166534;
     }

     .status.cancelled {
         background: #fef2f2;
         color: #b91c1c;
     }

     .status.completed {
         background: #eff6ff;
         color: #1e40af;
     }

     /* Alerts */
     .alert {
         margin-top: .75rem;
         padding: .75rem 1rem;
         border-radius: 10px;
         font-size: .95rem;
     }

     .alert.success {
         background: #ecfdf5;
         color: #065f46;
         border: 1px solid #a7f3d0;
     }

     .alert.error {
         background: #fef2f2;
         color: #991b1b;
         border: 1px solid #fecaca;
     }

     /* Timeline */
     .timeline {
         display: flex;
         gap: 2rem;
         align-items: flex-start;
         margin-top: .25rem;
     }

     .step {
         position: relative;
         padding-left: 1.5rem;
         color: #6b7280;
     }

     .step .dot {
         position: absolute;
         left: 0;
         top: .2rem;
         width: .75rem;
         height: .75rem;
         border-radius: 50%;
         background: #d1d5db;
     }

     .step.done {
         color: #111827;
     }

     .step.done .dot {
         background: #10b981;
         box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
     }

     /* Link button style (si tu veux harmoniser le lien “Détails” ailleurs) */
     .link-btn {
         color: #2563eb;
         text-decoration: none;
         font-weight: 600;
         padding: .25rem .5rem;
         border-radius: 8px;
         border: 1px solid transparent;
     }

     .link-btn:hover {
         background: #eff6ff;
         border-color: #bfdbfe;
     }
 </style>
 @section('content')
 <main class="booking-show container mx-auto px-6 py-8">
     <a href="{{ url()->previous() }}" class="back-link">
         <i class="fas fa-arrow-left"></i> Retour
     </a>

     <header class="header-card card">
         <div class="header-left">
             <h1>Détails de la réservation</h1>
             <p class="muted">Référence
                 <button class="copy-ref" data-copy="{{ $reservation->numero_reservation }}" title="Copier">
                     #{{ $reservation->numero_reservation }}
                     <i class="far fa-copy"></i>
                 </button>
                 — créée le {{ \Carbon\Carbon::parse($reservation->created_at)->translatedFormat('d F Y à H:i') }}
             </p>
         </div>
         <div class="header-right">
             <span class="badge status
        @if($reservation->statut === 'pending') pending
        @elseif($reservation->statut === 'confirmed') confirmed
        @elseif($reservation->statut === 'canceled') cancelled
        @elseif($reservation->statut === 'completed') completed
        @endif">
                 {{ $reservation->statut }}
             </span>
             @if($reservation->statut!== 'confirmed')
             <a href="{{ route('paiements.show', ['booking' => $reservation->id]) }}" class="btnPaiement">
                 Procéder au paiement
             </a>
             @endif
         </div>
     </header>

     <section class="grid-2">
         {{-- Carte Résidence / Voyage --}}
         <div class="card">
             <div class="residence-head">
                 @php
                 $mainImage = $reservation->residence->images->where('est_principale', true)->first();
                 $imagePath = $mainImage ? asset($mainImage->chemin_image) : 'https://placehold.co/600x400?text=Residence';
                 @endphp
                 <img src="{{ $imagePath }}" alt="Image résidence">
                 <div class="info">
                     <h2>{{ $reservation->residence->nom }}</h2>
                     <p class="muted">
                         <i class="fas fa-map-marker-alt"></i>
                         {{ $reservation->residence->adresse ?? 'Adresse indisponible' }}
                     </p>
                 </div>
             </div>

             <div class="details-grid">
                 <div>
                     <label>Arrivée</label>
                     <p>{{ \Carbon\Carbon::parse($reservation->date_arrivee)->translatedFormat('d F Y') }}</p>
                 </div>
                 <div>
                     <label>Départ</label>
                     <p>{{ \Carbon\Carbon::parse($reservation->date_depart)->translatedFormat('d F Y') }}</p>
                 </div>
                 <div>
                     <label>Voyageurs</label>
                     <p>{{ $reservation->nombre_adultes ?? 1 }} adulte(s)<br> {{ isset($reservation->nombre_enfants) ? '' . $reservation->nombre_enfants . ' enfant(s)' : '' }}</p>
                 </div>
                 <div>
                     <label>Méthode de paiement</label>
                     <p>{{ ucfirst($reservation->payment_method ?? '—') }}</p>
                 </div>
             </div>
         </div>

         {{-- Carte Prix / Actions --}}
         <div class="card">
             <h3 class="card-title">Récapitulatif du prix</h3>

             <div class="scroll-x block-gap">
                 <table class="price-table">
                     <thead>
                         <tr>
                             <th>Libellé</th>
                             <th class="text-right">Montant</th>
                         </tr>
                     </thead>
                     <tbody>
                         <tr>
                             <td>
                                 {{ number_format($reservation->type->prix_base ?? 0, 0, ',', ' ') }} FCFA
                                 × {{ \Carbon\Carbon::parse($reservation->date_arrivee)->diffInDays($reservation->date_depart) }} nuit(s)
                             </td>
                             <td class="text-right">
                                 {{ number_format(($reservation->total_price ?? 0) - ($reservation->frais_service ?? 10000), 0, ',', ' ') }} FCFA
                             </td>
                         </tr>
                         <tr>
                             <td>Frais de service</td>
                             <td class="text-right">{{ number_format($reservation->frais_service ?? 10000, 0, ',', ' ') }} FCFA</td>
                         </tr>
                         @if(!empty($reservation->taxes))
                         <tr>
                             <td>Taxes</td>
                             <td class="text-right">{{ number_format($reservation->taxes, 0, ',', ' ') }} FCFA</td>
                         </tr>
                         @endif
                         <tr class="total">
                             <td>Total</td>
                             <td class="text-right">{{ number_format($reservation->total_price, 0, ',', ' ') }} FCFA</td>
                         </tr>
                     </tbody>
                 </table>
             </div>

             <div class="actions">
                 <a href="{{ route('bookings.invoice', $reservation->id) }}" class="btn outline" target="_blank">
                     <i class="fas fa-file-download"></i> Télécharger la facture
                 </a>

                 @if(in_array($reservation->statut, ['pending','confirmed']))
                 <form method="POST" action="{{ route('bookings.cancel', $reservation->id) }}" class="inline-form" id="cancel-form">
                     @csrf
                     @method('PATCH')
                     <button type="submit" class="btn danger" data-confirm="Annuler cette réservation ?">
                         <i class="fas fa-times-circle"></i> Annuler
                     </button>
                 </form>
                 @endif

                 @if($reservation->statut === 'confirmed')
                 <a href="{{ route('bookings.checkin', $reservation->id) }}" class="btn primary">
                     <i class="fas fa-door-open"></i> Check-in
                 </a>
                 @endif
             </div>

             @if(session('status'))
             <div class="alert success">{{ session('status') }}</div>
             @endif
             @if($errors->any())
             <div class="alert error">
                 <ul class="m-0 p-0">
                     @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                 </ul>
             </div>
             @endif
         </div>
     </section>

     {{-- Timeline (optionnel) --}}
     <section class="card mt-8">
         <h3 class="card-title">Suivi</h3>
         <div class="timeline">
             <div class="step {{ $reservation->created_at ? 'done' : '' }}">
                 <span class="dot"></span>
                 <p>Créée</p>
                 <small>{{ \Carbon\Carbon::parse($reservation->created_at)->translatedFormat('d/m/Y H:i') }}</small>
             </div>
             <div class="step {{ $reservation->statut === 'Confirmée' || $reservation->statut === 'Terminée' ? 'done' : '' }}">
                 <span class="dot"></span>
                 <p>Confirmée</p>
             </div>
             <div class="step {{ $reservation->statut === 'Terminée' ? 'done' : '' }}">
                 <span class="dot"></span>
                 <p>Terminée</p>
             </div>
             @if($reservation->statut === 'Annulée')
             <div class="step done">
                 <span class="dot"></span>
                 <p>Annulée</p>
             </div>
             @endif
         </div>
     </section>
     <script>
         document.addEventListener('DOMContentLoaded', () => {
             // Copier la référence
             document.querySelectorAll('.copy-ref').forEach(btn => {
                 btn.addEventListener('click', async () => {
                     const value = btn.getAttribute('data-copy');
                     try {
                         await navigator.clipboard.writeText(value);
                         flash(btn, 'Copié !');
                     } catch {
                         flash(btn, 'Impossible de copier');
                     }
                 });
             });

             // Confirmation d’annulation
             const cancelForm = document.getElementById('cancel-form');
             if (cancelForm) {
                 cancelForm.addEventListener('submit', (e) => {
                     const btn = cancelForm.querySelector('[data-confirm]');
                     const msg = btn?.getAttribute('data-confirm') || 'Confirmer ?';
                     if (!confirm(msg)) e.preventDefault();
                 });
             }
         });

         /** Petit feedback visuel à côté du bouton copié */
         function flash(btn, text) {
             const chip = document.createElement('span');
             chip.textContent = text;
             chip.style.marginLeft = '.5rem';
             chip.style.fontSize = '.85rem';
             chip.style.color = '#10b981';
             btn.insertAdjacentElement('afterend', chip);
             setTimeout(() => chip.remove(), 1600);
         }
     </script>
 </main>
 @endsection