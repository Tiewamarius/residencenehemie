document.addEventListener("DOMContentLoaded", () => {
    const tabs = document.querySelectorAll(".profile-nav-item");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach(tab => {
        tab.addEventListener("click", e => {
            e.preventDefault();

            // Reset
            tabs.forEach(t => t.classList.remove("active"));
            contents.forEach(c => c.classList.remove("active"));

            // Activer l’onglet sélectionné
            tab.classList.add("active");
            const target = document.getElementById(tab.dataset.tab);
            if (target) target.classList.add("active");
        });
    });
});
