// Fonction pour fermer le message
function closeWarning() {
    document.getElementById("warning").style.display = "none";
}

// Affiche le message après chargement de la page
window.onload = () => {
    document.getElementById("warning").style.display = "flex";
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, {
    threshold: 0.2
});

const items = document.querySelectorAll('section');
items.forEach(item => observer.observe(item));

function getQueryParam(param) {
    const params = new URLSearchParams(window.location.search);
    return params.get(param);
}

const guestId = getQueryParam('id');
const eventId = getQueryParam('eventId');

if (guestId && eventId) {
    fetch(`https://carte-invitation.onrender.com/assets/scripts/api.php?id=${encodeURIComponent(guestId)}&eventId=${encodeURIComponent(eventId)}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.querySelector('.guest-name').textContent = data.error;
                return;
            }
            // Affiche les infos dans la page
            document.querySelector('.guest-name').textContent = data.fullName;
            document.querySelector('.group-size').textContent = data.groupSize > 1 ? `Nombre de personnes : ${data.groupSize}` : "Seul(e)";
            document.querySelector('.table-name').textContent = data.tableName;
            document.getElementById('table-number').textContent = data.tableNumber;

            // Générer le QR code (avec la librairie QRCode.js par exemple)
            new QRCode(document.querySelector('.qrcode'), {
                text: guestId,
                width: 128,
                height: 128,
            });
        })
        .catch(err => {
            document.querySelector('.guest-name').textContent = "Erreur lors du chargement.";
            console.error(err);
        });
} else {
    document.querySelector('.guest-name').textContent = "Paramètres manquants dans l’URL.";
}


document.addEventListener("DOMContentLoaded", () => {
    const numero = document.getElementById("table-number").textContent.trim();
    console.log("Numéro de la table:", numero);

    // 1. Sélectionner toutes les tables (avec la classe cls-1)  
    const allTables = document.querySelectorAll(".cls-1");

    // 3. Sélectionner la bonne table et la colorer
    const elementsToColor = document.querySelectorAll(".cls-1.T" + numero);
    elementsToColor.forEach(el => {
        el.style.fill = "#ffbb00"; // couleur surlignée
    });
});
