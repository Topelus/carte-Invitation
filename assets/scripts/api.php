<?php
header("Content-Type: application/json; charset=UTF-8");

// Connexion à la BDD avec variables d'environnement
$servername = getenv("DB_HOST");
$username   = getenv("DB_USER");
$password   = getenv("DB_PASS");
$dbname     = getenv("DB_NAME");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connexion échouée : " . $conn->connect_error]);
    exit;
}

$result = $conn->query("SELECT * FROM Guest");
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>
