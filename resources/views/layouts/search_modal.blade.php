{{-- Modale de recherche pour petit écran (nouveau) --}}
<div class="search-modal-overlay" id="search-modal-overlay" style="display:none;">
    <div class="search-modal" id="search-modal">
        <div class="search-modal-header">
            <i class="fas fa-times search-modal-close-btn" id="search-modal-close-btn"></i>
            <h3 class="search-modal-title">Rechercher un logement</h3>
        </div>
        <div class="search-modal-content">
            <form action="#" method="GET" class="mobile-search-form-in-modal">
                <div class="form-group">
                    <label for="destination_modal">DESTINATION</label>
                    <input type="text" id="destination_modal" name="destination_modal" placeholder="N'importe où">
                </div>
                <div class="form-group-row">
                    <div class="form-group date-input">
                        <label for="arrivee_modal">ARRIVÉE</label>
                        <input type="date" id="arrivee_modal" name="arrivee_modal" placeholder="Ajouter une date">
                    </div>
                    <div class="form-group date-input">
                        <label for="depart_modal">DÉPART</label>
                        <input type="date" id="depart_modal" name="depart_modal" placeholder="Ajouter une date">
                    </div>
                </div>
                <div class="form-group-row">
                    <div class="form-group select-input">
                        <label for="adultes_modal">ADULTES</label>
                        <select id="adultes_modal" name="adultes_modal">
                            <option value="1">1</option>
                            <option value="2" selected>2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="form-group select-input">
                        <label for="enfants_modal">ENFANTS</label>
                        <select id="enfants_modal" name="enfants_modal">
                            <option value="0" selected>0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="button search-button-modal">Rechercher</button>
            </form>
        </div>
    </div>
</div>