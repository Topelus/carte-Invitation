<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$servername = getenv("DB_HOST");
$username   = getenv("DB_USER");
$password   = getenv("DB_PASS");
$dbname     = getenv("DB_NAME");

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Erreur connexion BDD : " . $conn->connect_error]));
}

$guestId = isset($_GET['id']) ? $conn->real_escape_string($_GET['id']) : '';
$eventId = isset($_GET['eventId']) ? $conn->real_escape_string($_GET['eventId']) : '';

if ($guestId === '' || $eventId === '') {
    echo json_encode(["error" => "Paramètres manquants"]);
    exit;
}

$sqlGuest = "SELECT fullName, groupSize FROM Guest WHERE id = '$guestId' AND eventId = '$eventId'";
$resultGuest = $conn->query($sqlGuest);
if ($resultGuest->num_rows === 0) {
    echo json_encode(["error" => "Guest introuvable"]);
    exit;
}
$guest = $resultGuest->fetch_assoc();

$sqlAssign = "SELECT tableId FROM Assignement WHERE guestId = '$guestId'";
$resultAssign = $conn->query($sqlAssign);
if ($resultAssign->num_rows === 0) {
    echo json_encode(["error" => "Aucune table attribuée"]);
    exit;
}
$assign = $resultAssign->fetch_assoc();
$tableId = $assign['tableId'];

$sqlTable = "SELECT codeName, number FROM Table WHERE id = '$tableId'";
$resultTable = $conn->query($sqlTable);
if ($resultTable->num_rows === 0) {
    echo json_encode(["error" => "Table introuvable"]);
    exit;
}
$table = $resultTable->fetch_assoc();

$response = [
    "fullName"   => $guest['fullName'],
    "groupSize"  => $guest['groupSize'],
    "tableName"  => $table['codeName'],
    "tableNumber"=> $table['number']
];

echo json_encode($response);
$conn->close();
?>
