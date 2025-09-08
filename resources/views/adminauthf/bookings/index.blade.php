@extends('adminauth.AdminDashboard')
@section('content')

<div class="w-full px-6 py-6 mx-auto">

  <!-- ====== Filtres ====== -->
  <!-- Bouton filtre mobile -->
  <button id="mobile-filter-toggle" class="mobile-filter-btn hidden">
    Filtre
  </button>
  <div class="filter-box">
    <div class="filter-header">
      <h6>Réservations</h6>
      <div class="filter-actions">
        <a href="#"><i class="fas fa-download"></i> Télécharger</a>
        <a href="#"><i class="fas fa-print"></i> Imprimer </a>
      </div>
    </div>

    <form action="{{ url('admin/bookings') }}" method="GET" class="filter-form">
      <!-- Date de -->
      <div class="filter-group">
        <label>Date de</label>
        <select name="date_type">
          <option value="arrivee">Arrivée</option>
          <option value="depart">Départ</option>
        </select>
      </div>

      <!-- Du -->
      <div class="filter-group">
        <label>Du</label>
        <input type="date" name="date_start">
      </div>

      <!-- Au -->
      <div class="filter-group">
        <label>Au</label>
        <input type="date" name="date_end">
      </div>

      <!-- Boutons -->
      <div class="filter-buttons">
        <button type="button" id="toggle-filters">Plus de filtres</button>
        <button type="submit" class="btn-primary " style="background-color:#ed5257;">Voir</button>
      </div>
    </form>

    <!-- Section filtres avancés -->
    <div id="advanced-filters" class="advanced-filters hidden">
      <!-- Statut réservation -->
      <div class="filter-block">
        <h6>Statut de la réservation</h6>
        <label><input type="checkbox" name="statut[]" value="ok"> Confirmé</label>
        <label><input type="checkbox" name="statut[]" value="annulee"> Annulée</label>
        <label><input type="checkbox" name="statut[]" value="no_show"> En attente</label>
        <label><input type="checkbox" name="statut[]" value="pro"> Carte professionnelle</label>
      </div>

      <!-- Communications clients -->
      <div class="filter-block">
        <h6>Communications clients</h6>
        <label><input type="checkbox" name="com[]" value="pending"> Demande client en attente</label>
        <label><input type="checkbox" name="com[]" value="invoice"> Facture demandée</label>
      </div>

      <!-- Carte de crédit -->
      <div class="filter-block">
        <h6>Carte de crédit invalide</h6>
        <label><input type="checkbox" name="cc[]" value="update"> Mise à jour</label>
        <label><input type="checkbox" name="cc[]" value="pending"> En attente</label>
      </div>

      <!-- Recherche -->
      <div class="filter-block">
        <h6>Nom du client ou numéro de réservation</h6>
        <input type="text" name="search" placeholder="Rechercher...">
      </div>
    </div>
  </div>
  <!-- ====== /Filtres ====== -->


  <!-- table 1 -->
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
                    <span class="bg-gradient-to-tl from-green-600 to-lime-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                      <button onclick="openDetail({{ $booking->id }})" class="text-xs font-semibold leading-tight text-slate-400"> Details </button>
                    </span>
                  </td>
                  <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                    @php
                    $statusClasses = '';
                    $statutNom = '';
                    switch ($booking->statut) {
                    case 'pending':
                    $statusClasses = 'bg-gradient-to-tl from-blue-600 to-blue-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white';
                    $statutNom = 'En attente';
                    break;
                    case 'confirmed':
                    $statusClasses = 'bg-gradient-to-tl from-green-600 to-lime-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white';
                    $statutNom = 'Confirmée';
                    break;
                    case 'checked_in':
                    $statusClasses = 'bg-gradient-to-tl from-blue-600 to-blue-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white';
                    $statutNom = 'Arrivé';
                    break;
                    case 'checked_out':
                    $statusClasses = 'bg-gradient-to-tl from-blue-600 to-blue-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white';
                    $statutNom = 'Parti';
                    break;
                    case 'completed':
                    $statusClasses = 'bg-gradient-to-tl from-blue-600 to-blue-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white';
                    $statutNom = 'Terminée';
                    break;

                    case 'canceled':
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
                    <p class="mb-0 text-xs font-semibold leading-tight">{{ $booking->nombre_adultes }} adultes</p>
                    <p class="mb-0 text-xs leading-tight text-slate-400">{{ $booking->nombre_enfants }} enfants</p>
                  </td>
                  <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                    <!-- Bouton Edit -->
                    <span class="bg-gradient-to-tl from-green-600 to-lime-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                      <button onclick="openEdit({{ $booking->id }})" class="text-xs font-semibold leading-tight text-slate-400"> Edit </button>
                    </span>
                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-xs font-semibold leading-tight text-red-500" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')"> Supprimer </button>
                    </form>
                    </span>
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
</div>
</div>

<!-- ===== Modale Détails ===== -->
<div id="detail-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-11/12 max-w-lg p-6 relative">
    <button class="absolute top-3 right-3 text-gray-500" onclick="closeModal('detail-modal')">&times;</button>
    <h3 class="text-lg font-bold mb-4">Détails de la réservation</h3>
    <div id="detail-content">
      <!-- Contenu chargé via JS -->
    </div>
  </div>
</div>

<!-- Modale Détails -->
<div id="detail-modal" class="modal">
  <div>
    <button class="close-btn" onclick="closeModal('detail-modal')">&times;</button>
    <h3 class="text-lg font-bold mb-4">Détails de la réservation</h3>
    <div id="detail-content"></div>
  </div>
</div>

<!-- Modale Édit -->
<div id="edit-modal" class="modal">
  <div>
    <button class="close-btn" onclick="closeModal('edit-modal')">&times;</button>
    <h3 class="text-lg font-bold mb-4">Modifier la réservation</h3>
    <div id="edit-content"></div>
  </div>
</div>


<!-- ====== CSS ====== -->
<style>
  /* Bouton mobile */
  .mobile-filter-btn {
    display: none;
    /* masqué par défaut */
    padding: 8px 14px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background: #007bff;
    color: #fff;
    cursor: pointer;
    margin-bottom: 10px;
  }

  /* Responsive pour petits écrans */
  @media (max-width: 768px) {
    .filter-box {
      display: none;
      /* masque la section filtre */
    }

    .mobile-filter-btn {
      display: inline-block;
      /* affiche le bouton filtre */
    }
  }

  .filter-box {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }

  .filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }

  .filter-header h6 {
    font-size: 18px;
    font-weight: bold;
    margin: 0;
  }

  .filter-actions a {
    margin-left: 15px;
    font-size: 14px;
    color: #555;
    text-decoration: none;
  }

  .filter-actions a:hover {
    color: #0066cc;
  }

  .filter-form {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
  }

  .filter-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #444;
  }

  .filter-group input,
  .filter-group select {
    width: 100%;
    padding: 6px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
  }

  .filter-buttons {
    display: flex;
    align-items: flex-end;
    gap: 10px;
  }

  .filter-buttons button {
    padding: 8px 14px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background: #f5f5f5;
    cursor: pointer;
  }

  .filter-buttons button:hover {
    background: #eaeaea;
  }

  .btn-primary {
    background: #007bff;
    color: white;
    border: none;
  }

  .btn-primary:hover {
    background: #0066cc;
  }

  .advanced-filters {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 15px;
  }

  .advanced-filters.hidden {
    display: none;
  }

  .filter-block h6 {
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 8px;
  }

  .filter-block label {
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    color: #555;
  }

  .filter-block input[type="text"] {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
  }

  /* Modales centrées */
  .modal {
    display: none;
    /* Masquées par défaut */
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 50;
    padding: 20px;
    overflow-y: auto;
  }

  /* Contenu de la modale */
  .modal>div {
    background: #fff;
    border-radius: 12px;
    width: 66%;
    /* 2/3 de l'écran */
    max-width: 800px;
    padding: 30px;
    position: relative;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    animation: modal-show 0.3s ease-out;
  }

  /* Bouton de fermeture */
  .modal button.close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 20px;
    color: #555;
    background: none;
    border: none;
    cursor: pointer;
  }

  /* Animation d'apparition */
  @keyframes modal-show {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Responsive */
  @media (max-width: 1024px) {
    .modal>div {
      width: 80%;
    }
  }

  @media (max-width: 640px) {
    .modal>div {
      width: 95%;
      padding: 20px;
    }
  }
</style>

<!-- ====== JS ====== -->
<script>
  document.getElementById('toggle-filters').addEventListener('click', function() {
    const adv = document.getElementById('advanced-filters');
    adv.classList.toggle('hidden');
    this.textContent = adv.classList.contains('hidden') ? 'Plus de filtres ⌄' : 'Moins de filtres ⌃';
  });

  const mobileFilterBtn = document.getElementById('mobile-filter-toggle');
  const filterBox = document.querySelector('.filter-box');

  mobileFilterBtn.addEventListener('click', function() {
    if (filterBox.style.display === 'block') {
      filterBox.style.display = 'none';
    } else {
      filterBox.style.display = 'block';
    }
  });


  function openDetail(id) {
    // Ici tu peux faire une requête AJAX pour récupérer les détails si besoin
    const modal = document.getElementById('detail-modal');
    const content = document.getElementById('detail-content');

    content.innerHTML = `
    <p>Chargement des détails pour la réservation #${id}...</p>
    <!-- Tu peux remplacer par un fetch() vers une route Laravel qui renvoie le HTML -->
  `;

    modal.classList.remove('hidden');
  }

  function openEdit(id) {
    const modal = document.getElementById('edit-modal');
    const content = document.getElementById('edit-content');

    content.innerHTML = `
    <form action="/admin/bookings/${id}" method="POST">
      @csrf
      @method('PUT')
      <label>Nombre d'adultes:</label>
      <input type="number" name="nombre_adultes" value="1" class="border p-2 rounded w-full mb-2">
      <label>Nombre d'enfants:</label>
      <input type="number" name="nombre_enfants" value="0" class="border p-2 rounded w-full mb-2">
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Enregistrer</button>
    </form>
  `;

    modal.classList.remove('hidden');
  }

  function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
  }
</script>


@endsection