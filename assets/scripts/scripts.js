
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

const guestId = getQueryParam("id");
const eventId = getQueryParam("eventId");

fetch(`api.php?id=${guestId}&eventId=${eventId}`)
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            document.querySelector(".guest-name").textContent = data.error;
            return;
        }

        document.querySelector(".guest-name").textContent = data.fullName;
        if (data.groupSize > 1) {
            document.querySelector(".guest-name").textContent += ` (+${data.groupSize - 1})`;
        }
        document.querySelector(".table-name").textContent = data.tableName;
        document.getElementById("table-number").textContent = data.tableNumber;

        // Génération du QR code avec l'id du guest
        new QRCode(document.querySelector(".qrcode"), {
            text: guestId,
            width: 128,
            height: 128
        });
    })
    .catch(err => {
        console.error(err);
        // document.querySelector(".guest-name").textContent = "Erreur de chargement.";
    });
