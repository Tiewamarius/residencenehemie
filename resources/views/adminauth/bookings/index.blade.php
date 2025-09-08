@extends('adminauth.AdminDashboard')
@section('content')

<div class="w-full px-6 py-6 mx-auto">

  <button id="mobile-filter-toggle" class="mobile-filter-btn hidden">
    Filtre
  </button>
  <div class="filter-box">
    <div class="filter-header">
      <h6>Réservations</h6>
      <div class="filter-actions">
        <!-- <a href="#"><i class="fas fa-download"></i> Télécharger</a> -->
        <a href="#"><i class="fas fa-print"></i> Imprimer </a>
      </div>
    </div>

    <form id="filter-form" action="{{ url('admin/bookings') }}" method="GET" class="filter-form">
      <div class="filter-group">
        <label>Nom du client ou N° Réser.</label>
        <input type="search" name="search" placeholder="Chercher...">
      </div>
      <div class="filter-group">
        <label>Statut</label>
        <select name="statut">
          <option value="all">Filtre</option>
          <option value="Confirmé">Confirmé</option>
          <option value="Encours">En cours</option>
          <option value="Annulé">Annulé</option>
          <option value="Terminé">Terminé</option>

        </select>
      </div>
      <div class="filter-group">
        <label>Du</label>
        <input type="date" name="date_arrivee">
      </div>
      <div class="filter-group">
        <label>Au</label>
        <input type="date" name="date_depart">
      </div>
    </form>
  </div>
  <div id="bookings-table-container">
    {{-- Le contenu de la table sera injecté ici --}}
    @include('adminauth.bookings.bookings_table', ['bookings' => $bookings])
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
  // Ferme toutes les modales ouvertes
  function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
  }

  // Gère l'affichage du menu de filtre sur mobile
  const mobileFilterBtn = document.getElementById('mobile-filter-toggle');
  const filterBox = document.querySelector('.boîte-filtre');

  mobileFilterBtn.addEventListener('click', function() {
    filterBox.classList.toggle('hidden');
  });

  // Gestion du formulaire de filtre avec AJAX
  const filterForm = document.getElementById('filter-form');
  const tableContainer = document.getElementById('bookings-table-container');
  let searchTimeout = null;

  function fetchBookings() {
    // Crée une chaîne de requête à partir des données du formulaire
    const formData = new FormData(filterForm);
    const queryString = new URLSearchParams(formData).toString();
    const url = `{{ url('admin/bookings') }}?${queryString}`;

    // Utilise l'API Fetch pour charger les données
    fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('La requête a échoué');
        }
        return response.text();
      })
      .then(html => {
        // Remplace le contenu de la table avec la nouvelle réponse
        tableContainer.innerHTML = html;
        // Met à jour l'URL sans recharger la page
        history.pushState(null, '', url);
      })
      .catch(error => console.error('Erreur de chargement des réservations:', error));
  }

  // Événements pour déclencher le filtre
  filterForm.addEventListener('change', (event) => {
    // Déclenche le filtre pour les champs qui changent (select, date)
    if (event.target.type !== 'search') {
      fetchBookings();
    }
  });

  filterForm.addEventListener('input', (event) => {
    // Utilise un "debounce" pour le champ de recherche
    if (event.target.type === 'search') {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        fetchBookings();
      }, 500); // Délai de 500ms
    }
  });

  // Gère la pagination avec AJAX
  document.addEventListener('click', (event) => {
    const paginationLink = event.target.closest('.pagination a');
    if (paginationLink) {
      event.preventDefault(); // Empêche le rechargement de la page
      const url = paginationLink.href;
      fetch(url, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(response => response.text())
        .then(html => {
          tableContainer.innerHTML = html;
          history.pushState(null, '', url);
        })
        .catch(error => console.error('Erreur de chargement de la pagination:', error));
    }
  });

  // Ouvre la modale de détails avec les données de la réservation
  function openDetail(id) {
    const modal = document.getElementById('detail-modal');
    const content = document.getElementById('detail-content');

    // Affiche un message de chargement
    content.innerHTML = `<p>Chargement des détails pour la réservation #${id}...</p>`;
    modal.classList.remove('hidden');

    // Récupère les détails depuis le serveur
    fetch(`/admin/bookings/${id}/details`)
      .then(response => {
        if (!response.ok) {
          throw new Error('La requête a échoué');
        }
        return response.text();
      })
      .then(html => {
        content.innerHTML = html;
      })
      .catch(error => {
        console.error('Erreur de chargement des détails:', error);
        content.innerHTML = `<p class="text-red-500">Erreur lors du chargement des détails.</p>`;
      });
  }

  // Ouvre la modale d'édition et injecte un formulaire
  function openEdit(id) {
    const modal = document.getElementById('edit-modal');
    const content = document.getElementById('edit-content');

    // Ajoute un message de chargement
    content.innerHTML = `<p>Chargement du formulaire d'édition pour la réservation #${id}...</p>`;

    // Récupère le formulaire d'édition via AJAX pour le rendre plus dynamique et réutilisable
    fetch(`/admin/bookings/${id}/edit`)
      .then(response => response.text())
      .then(html => {
        content.innerHTML = html;
        modal.classList.remove('hidden');
      })
      .catch(error => {
        console.error('Erreur de chargement du formulaire d\'édition:', error);
        content.innerHTML = `<p class="text-red-500">Erreur lors du chargement du formulaire.</p>`;
      });
  }
</script>


@endsection