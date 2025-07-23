// Il est recommandé de placer ce script dans resources/js/HomePage.js
// et de l'importer dans resources/js/app.js comme ceci :
// import './HomePage.js';

// --- Sélection des Éléments DOM ---
const assistantOpenHeaderBtn = document.querySelector("#form-open"); // Bouton "Assistant" dans le header principal
const formOpenSidebarBtn = document.querySelector("#form-open-sidebar"); // Nouveau: Bouton "Assistant" dans la sidebar
// const homeSection = document.querySelector(".home"); // N'est plus utilisé pour contrôler la visibilité du chat
const chatContainer = document.querySelector(".chat_container"); // La modale de chat
const chatCloseBtn = document.querySelector(".chat_close"); // Bouton de fermeture du chat
const chatMessagesArea = document.querySelector(".chat_messages"); // Zone d'affichage des messages
const chatInput = document.querySelector("#chat_input"); // Champ de saisie du message
const sendChatBtn = document.querySelector("#send_chat_btn"); // Bouton d'envoi du message

// Le lien "Contact" (3ème lien .nav_link) - Mettez à jour le sélecteur si nécessaire
const contactNavLink = document.querySelector('.nav_item li:nth-child(3) .nav_link');

// Éléments du diaporama d'images
const sliderImages = document.querySelectorAll(".slider-image");
let currentImageIndex = 0;

// Nouveaux éléments pour la sidebar
const menuToggleBtn = document.querySelector(".menu_toggle_btn"); // L'icône burger
const sidebar = document.querySelector(".sidebar"); // Le menu latéral
const sidebarCloseBtn = document.querySelector(".sidebar_close_btn"); // Bouton de fermeture de la sidebar
const overlay = document.createElement('div'); // Créer l'overlay
overlay.classList.add('overlay');
document.body.appendChild(overlay); // Ajouter l'overlay au body

// --- Variables Globales ---
let chatHistory = []; // Historique de la discussion pour l'API Gemini

// --- Fonctions Utilitaires ---

/**
 * Ajoute un message à la zone de discussion.
 * @param {string} message Le texte du message.
 * @param {'user'|'bot'} sender Le type d'expéditeur ('user' ou 'bot').
 */
function addMessageToChat(message, sender) {
    const messageDiv = document.createElement("div");
    messageDiv.classList.add("message", `${sender}-message`);
    messageDiv.textContent = message;
    chatMessagesArea.appendChild(messageDiv);
    // Faire défiler automatiquement vers le bas pour voir le dernier message
    chatMessagesArea.scrollTop = chatMessagesArea.scrollHeight;
}

/**
 * Appelle l'API Gemini avec le prompt de l'utilisateur et gère la réponse.
 * @param {string} prompt Le message de l'utilisateur à envoyer à l'API.
 */
async function callGeminiAPI(prompt) {
    addMessageToChat("...", "bot"); // Afficher un indicateur de chargement

    chatHistory.push({ role: "user", parts: [{ text: prompt }] });
    const payload = { contents: chatHistory };
    const apiKey = ""; // Laissez vide, Canvas fournira la clé en runtime si configuré.

    if (!apiKey) {
        if (chatMessagesArea.lastChild && chatMessagesArea.lastChild.textContent === "...") {
            chatMessagesArea.removeChild(chatMessagesArea.lastChild);
        }
        addMessageToChat("Erreur: La clé API Gemini n'est pas configurée. Veuillez contacter l'administrateur.", "bot");
        console.error("API Key for Gemini is missing.");
        return;
    }

    const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${apiKey}`;

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`Erreur API: ${response.status} - ${errorData.error.message || response.statusText}`);
        }

        const result = await response.json();

        if (chatMessagesArea.lastChild && chatMessagesArea.lastChild.textContent === "...") {
            chatMessagesArea.removeChild(chatMessagesArea.lastChild);
        }

        if (result.candidates && result.candidates.length > 0 &&
            result.candidates[0].content && result.candidates[0].content.parts &&
            result.candidates[0].content.parts.length > 0) {
            const text = result.candidates[0].content.parts[0].text;
            addMessageToChat(text, "bot");
            chatHistory.push({ role: "model", parts: [{ text: text }] }); // Ajouter la réponse du bot à l'historique
        } else {
            addMessageToChat("Désolé, je n'ai pas pu obtenir de réponse de l'assistant pour le moment. Veuillez réessayer plus tard ou noter que nos assistants sont disponibles de 09H à 22H.", "bot");
            console.error("Erreur: Structure de réponse inattendue ou contenu manquant de Gemini.", result);
        }
    } catch (error) {
        if (chatMessagesArea.lastChild && chatMessagesArea.lastChild.textContent === "...") {
            chatMessagesArea.removeChild(chatMessagesArea.lastChild);
        }
        addMessageToChat("Une erreur est survenue lors de la communication avec l'assistant. Veuillez vérifier votre connexion ou réessayer.", "bot");
        console.error("Erreur lors de l'appel API Gemini:", error);
    }
}

/**
 * Gère l'envoi d'un message par l'utilisateur.
 */
function sendMessage() {
    const userMessage = chatInput.value.trim();
    if (userMessage) {
        addMessageToChat(userMessage, "user");
        chatInput.value = "";
        callGeminiAPI(userMessage);
    }
}

/**
 * Gère le changement d'image pour le diaporama.
 */
function changeSlide() {
    // S'assurer qu'il y a des images à parcourir
    if (sliderImages.length === 0) return;

    // Supprimer la classe 'active' de l'image actuelle
    sliderImages[currentImageIndex].classList.remove("active");

    // Calculer l'index de la prochaine image
    currentImageIndex = (currentImageIndex + 1) % sliderImages.length;

    // Ajouter la classe 'active' à la nouvelle image
    sliderImages[currentImageIndex].classList.add("active");
}

// --- Fonctions de Gestion Sidebar ---
function openSidebar() {
    sidebar.classList.add("active");
    overlay.classList.add("active");
    document.body.style.overflow = 'hidden'; // Empêche le défilement du corps
}

function closeSidebar() {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
    document.body.style.overflow = ''; // Rétablit le défilement du corps
}

/**
 * Ouvre la modale de chat.
 */
function openChatModal() {
    chatContainer.classList.add("active");
}

/**
 * Ferme la modale de chat.
 */
function closeChatModal() {
    chatContainer.classList.remove("active");
}

// --- Initialisation et Écouteurs d'Événements ---

// Démarrer le diaporama d'images toutes les 5 secondes (5000 ms)
if (sliderImages.length > 0) {
    sliderImages[currentImageIndex].classList.add("active"); // S'assurer que la première image est active au démarrage
    setInterval(changeSlide, 5000);
}

// Écouteurs d'événements pour l'ouverture et la fermeture de la modale de chat
if (assistantOpenHeaderBtn) {
    assistantOpenHeaderBtn.addEventListener("click", openChatModal);
}

// Nouveau: Bouton assistant dans la sidebar
if (formOpenSidebarBtn) {
    formOpenSidebarBtn.addEventListener("click", () => {
        closeSidebar(); // Ferme la sidebar si elle est ouverte
        openChatModal(); // Ouvre la modale de chat
    });
}

if (chatCloseBtn) {
    chatCloseBtn.addEventListener("click", closeChatModal);
}

// Écouteurs d'événements pour l'envoi de messages de chat
if (sendChatBtn) {
    sendChatBtn.addEventListener("click", sendMessage);
}

if (chatInput) {
    chatInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            sendMessage();
        }
    });
}

// Écouteur d'événement pour le lien "Contact"
if (contactNavLink) {
    contactNavLink.addEventListener("click", (e) => {
        e.preventDefault(); // Empêche le comportement par défaut du lien
        openChatModal();
    });
}

// Nouveaux écouteurs d'événements pour la sidebar
if (menuToggleBtn) {
    menuToggleBtn.addEventListener("click", openSidebar);
}

if (sidebarCloseBtn) {
    sidebarCloseBtn.addEventListener("click", closeSidebar);
}

if (overlay) {
    overlay.addEventListener("click", closeSidebar); // Ferme la sidebar au clic sur l'overlay
}