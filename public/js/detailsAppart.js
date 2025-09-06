document.addEventListener('DOMContentLoaded', () => {
  // --- Sélecteurs essentiels ---
  const mainImage = document.querySelector('.apartment-gallery .main-image img');
  const thumbnails = document.querySelectorAll('.apartment-gallery .thumbnail-grid img');

  const checkInEl  = document.getElementById('check_in_date');
  const checkOutEl = document.getElementById('check_out_date');

  const nightsDisplay = document.getElementById('nights-display-breakdown');
  const priceSubtotalEl = document.getElementById('price-subtotal');
  const priceTotalEl = document.getElementById('price-total');
  const totalPriceInput = document.getElementById('total-price-input');
  const reserveBtn = document.querySelector('.check-availability-btn');
  const pricePerNightEl = document.querySelector('.booking-header .price');

  // --- Galerie ---
  if (mainImage && thumbnails.length) {
    thumbnails.forEach(t => t.addEventListener('click', () => {
      mainImage.src = t.src;
      mainImage.alt = t.alt || '';
    }));
  }

  // --- Prix ---
  const parsePrice = (txt) => (txt || '0').replace(/\s/g, '').replace(/[^\d]/g, '') * 1;
  const basePricePerNight = parsePrice(pricePerNightEl ? pricePerNightEl.textContent : '0');
  // const serviceFee = 10000;
  const serviceFee = 0;

  function recalcTotal() {
    let nights = 0, subtotal = 0, total = serviceFee;

    const dIn  = checkInEl?.value ? new Date(checkInEl.value + 'T00:00:00') : null;
    const dOut = checkOutEl?.value ? new Date(checkOutEl.value + 'T00:00:00') : null;

    if (dIn && dOut && dOut > dIn) {
      nights = Math.round((dOut - dIn) / (1000 * 60 * 60 * 24));
      subtotal = basePricePerNight * nights;
      total = subtotal + serviceFee;
    }

    if (nightsDisplay) nightsDisplay.textContent = nights;
    if (priceSubtotalEl) priceSubtotalEl.textContent = subtotal.toLocaleString('fr-FR');
    if (priceTotalEl) priceTotalEl.textContent = total.toLocaleString('fr-FR') + ' FCFA';
    if (totalPriceInput) totalPriceInput.value = total;
    if (reserveBtn) reserveBtn.disabled = !(dIn && dOut && nights > 0);
  }

  // --- Flatpickr: construction des périodes désactivées ---
  // window.bookedDateRanges = [{from: 'YYYY-MM-DD', to: 'YYYY-MM-DD'}, ...]
  const disabledRanges = Array.isArray(window.bookedDateRanges) ? window.bookedDateRanges : [];

  // Helpers dates
  const addDays = (iso, days) => {
    const d = new Date(iso + 'T00:00:00');
    d.setDate(d.getDate() + days);
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const dd = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${dd}`;
  };

  // IMPORTANT:
  // on désactive la plage [arrivee, depart - 1], car le jour de départ peut être réattribué.
  const flatpickrDisabled = disabledRanges.map(r => ({
    from: r.from,
    to: addDays(r.to, -1),
  }));

  let fpIn = null;
  let fpOut = null;

  if (checkInEl) {
    fpIn = flatpickr(checkInEl, {
      locale: 'fr',
      dateFormat: 'Y-m-d',
      minDate: 'today',
      disable: flatpickrDisabled,
      onChange: function(selectedDates, dateStr) {
        if (!fpOut) return;
        // minDate de sortie = lendemain du check-in
        const minOut = addDays(dateStr, 1);
        fpOut.set('minDate', minOut);

        // Empêche d’aller sur une plage bloquée
        fpOut.set('disable', flatpickrDisabled);

        // Si check-out sélectionné est invalide ou ≤ check-in, on reset
        const outVal = checkOutEl.value;
        if (!outVal || outVal <= dateStr) {
          checkOutEl.value = '';
        }

        recalcTotal();
      }
    });
  }

  if (checkOutEl) {
    fpOut = flatpickr(checkOutEl, {
      locale: 'fr',
      dateFormat: 'Y-m-d',
      minDate: checkInEl.value ? addDays(checkInEl.value, 1) : 'today',
      disable: flatpickrDisabled,
      onChange: recalcTotal
    });
  }

  // recalcul initial
  recalcTotal();
});
