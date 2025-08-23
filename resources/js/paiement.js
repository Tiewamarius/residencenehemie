document.addEventListener("DOMContentLoaded", () => {
    const btn = document.querySelector(".submit-payment-btn");
    const radios = document.querySelectorAll("input[name='payment_method']");
    const allDetails = document.querySelectorAll(".payment-details");

    const detailsMap = {
        "payment-mm": "mobile-money-details",
        "payment-wave": "wave-details",
        "payment-espece": "espece-details",
    };

    radios.forEach(radio => {
        radio.addEventListener("change", () => {
            // Fermer tous les blocs
            allDetails.forEach(d => d.classList.remove("expanded"));

            // Ouvrir uniquement le bloc choisi
            const targetId = detailsMap[radio.id];
            if (targetId) {
                const target = document.getElementById(targetId);
                if (target) {
                    target.classList.add("expanded");
                }
            }

            // Activer bouton
            btn.disabled = false;
        });
    });

    // Gérer les options désactivées
    document.querySelectorAll(".disabled-option").forEach(opt => {
        opt.addEventListener("click", (e) => {
            e.preventDefault();
            const oldMsg = opt.querySelector(".unavailable-message");
            if (oldMsg) oldMsg.remove();

            const msg = document.createElement("div");
            msg.className = "unavailable-message";
            msg.innerText = opt.dataset.message;
            opt.appendChild(msg);

            setTimeout(() => {
                msg.style.opacity = "0";
                msg.style.transition = "opacity 0.5s ease";
                setTimeout(() => msg.remove(), 500);
            }, 2500);
        });
    });
});
