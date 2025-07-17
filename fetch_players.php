<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Content-Type: application/json");
    echo json_encode([]);
    exit();
}

$username = $_SESSION['username'];

$servername = "localhost";
$dbusername = "root";
$dbpassword = "#Yash01.";
$dbname = "cricket";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$teamName = mysqli_real_escape_string($conn, $_GET['team_name']);
$tableName = $username . "_teams";
$playersQuery = "SELECT player_name FROM $tableName WHERE team_name = '$teamName'";
$playersResult = $conn->query($playersQuery);

$players = [];
if ($playersResult->num_rows > 0) {
    while ($row = $playersResult->fetch_assoc()) {
        $players[] = $row['player_name'];
    }
}

$conn->close();
header("Content-Type: application/json");
echo json_encode($players);
?>
