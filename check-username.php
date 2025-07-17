<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "#Yash01.";
$dbname = "cricket";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['username'])) {
    $username = mysqli_real_escape_string($conn, $_GET['username']);
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    echo json_encode(['isUnique' => $result->num_rows === 0]);
}

$conn->close();
?>
