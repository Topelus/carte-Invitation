<?php
header('Content-Type: application/json');

// Connexion à la BDD distante
$servername = "hote-bdd";
$username = "utilisateur";
$password = "motdepasse";
$dbname = "nom_base";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Erreur connexion BDD : " . $conn->connect_error]));
}

// Récupération des paramètres GET
$guestId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$eventId = isset($_GET['eventId']) ? intval($_GET['eventId']) : 0;

if ($guestId === 0 || $eventId === 0) {
    echo json_encode(["error" => "Paramètres manquants"]);
    exit;
}

// 1. Récupérer les infos du guest
$sqlGuest = "SELECT fullName, groupSize FROM Guest WHERE id = $guestId AND eventId = $eventId";
$resultGuest = $conn->query($sqlGuest);

if ($resultGuest->num_rows === 0) {
    echo json_encode(["error" => "Guest introuvable"]);
    exit;
}

$guest = $resultGuest->fetch_assoc();

// 2. Récupérer la tableId depuis Assignement
$sqlAssign = "SELECT tableId FROM Assignement WHERE guestId = $guestId";
$resultAssign = $conn->query($sqlAssign);

if ($resultAssign->num_rows === 0) {
    echo json_encode(["error" => "Aucune table attribuée"]);
    exit;
}

$assign = $resultAssign->fetch_assoc();
$tableId = intval($assign['tableId']);

// 3. Récupérer codeName et number depuis Table
$sqlTable = "SELECT codeName, number FROM Table WHERE id = $tableId";
$resultTable = $conn->query($sqlTable);

if ($resultTable->num_rows === 0) {
    echo json_encode(["error" => "Table introuvable"]);
    exit;
}

$table = $resultTable->fetch_assoc();

// 4. Réponse finale
$response = [
    "fullName"   => $guest['fullName'],
    "groupSize"  => $guest['groupSize'],
    "tableName"  => $table['codeName'],
    "tableNumber"=> $table['number']
];

echo json_encode($response);

$conn->close();
?>
