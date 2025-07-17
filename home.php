<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
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

$tableName = $username . "_teams";
$createTableSql = "CREATE TABLE IF NOT EXISTS $tableName (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    match_id INT NOT NULL,
    team_name VARCHAR(50) NOT NULL,
    player_name VARCHAR(50) NOT NULL
)";

if ($conn->query($createTableSql) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $team1 = mysqli_real_escape_string($conn, $_POST['team1']);
    $players1 = array_map('trim', explode(",", mysqli_real_escape_string($conn, $_POST['players1'])));
    $team2 = mysqli_real_escape_string($conn, $_POST['team2']);
    $players2 = array_map('trim', explode(",", mysqli_real_escape_string($conn, $_POST['players2'])));
    $overs = mysqli_real_escape_string($conn, $_POST['overs']);
    $ballType = mysqli_real_escape_string($conn, $_POST['ballType']);

    if ($team1 === $team2) {
        die("Team names must be unique.");
    }

    if (count(array_unique($players1)) !== 11 || count(array_unique($players2)) !== 11) {
        die("Each player in a team must have a unique name.");
    }

    $allPlayers = array_merge($players1, $players2);
    if (count(array_unique($allPlayers)) !== 22) {
        die("Player names must be unique across both teams.");
    }

    $checkTeamSql = "SELECT * FROM $tableName WHERE team_name = ? LIMIT 1";
    
    $stmt = $conn->prepare($checkTeamSql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $team1);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        die("The team name '$team1' already exists in the database. Please choose a different name.");
    }

    $stmt->bind_param("s", $team2);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        die("The team name '$team2' already exists in the database. Please choose a different name.");
    }

    $stmt->close();

    $matchId = time(); 
    foreach ($players1 as $player) {
        $insertPlayerSql = "INSERT INTO $tableName (match_id, team_name, player_name)
                            VALUES ('$matchId', '$team1', '$player')";
        if ($conn->query($insertPlayerSql) !== TRUE) {
            die("Error inserting player: " . $conn->error);
        }
    }

    foreach ($players2 as $player) {
        $insertPlayerSql = "INSERT INTO $tableName (match_id, team_name, player_name)
                            VALUES ('$matchId', '$team2', '$player')";
        if ($conn->query($insertPlayerSql) !== TRUE) {
            die("Error inserting player: " . $conn->error);
        }
    }

    header("Location: scoring.php?match_id=$matchId&overs=$overs&ballType=$ballType");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cricket Scoring App</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div class="welcome-container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

        <div class="match-setup">
            <h3>Setup a Match</h3>

            <form method="POST" action="home.php">
                <label for="team1">Enter Team 1 Name:</label>
                <input type="text" name="team1" id="team1" placeholder="Team 1 Name" required>

                <label for="players1">Enter Players for Team 1 (comma-separated):</label>
                <input type="text" name="players1" id="players1" placeholder="Player1, Player2, ..., Player11" required>

                <label for="team2">Enter Team 2 Name:</label>
                <input type="text" name="team2" id="team2" placeholder="Team 2 Name" required>

                <label for="players2">Enter Players for Team 2 (comma-separated):</label>
                <input type="text" name="players2" id="players2" placeholder="Player1, Player2, ..., Player11" required>

                <label for="ballType">Ball Type:</label>
                <select name="ballType" id="ballType" required>
                    <option value="tennis">Tennis Ball</option>
                    <option value="leather">Leather Ball</option>
                </select>

                <button type="submit">Begin Scoring</button>
            </form>
        </div>
    </div>
</body>
</html>
