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
$teamsQuery = "SELECT DISTINCT team_name FROM $tableName";
$teamsResult = $conn->query($teamsQuery);

$teams = [];
if ($teamsResult->num_rows > 0) {
    while ($row = $teamsResult->fetch_assoc()) {
        $teams[] = $row['team_name'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Cricket Scoring</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('2.jpg'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            background-color: rgba(22, 33, 62, 0.9); 
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
            overflow: hidden;
        }
        header {
            background-color: #0f3460;
            padding: 20px;
            text-align: center;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px; /* Add padding to the sides */
        }
        header h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 1px;
        }
        .profile-button {
            background-color: #e94560;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .profile-button:hover {
            background-color: #0f3460;
        }
        .panel {
            padding: 20px;
            border-bottom: 1px solid #1b1b3a;
        }
        .panel:last-child {
            border-bottom: none;
        }
        h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #e94560;
        }
        .dropdown-container, .button-grid {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        select, button {
            padding: 12px 18px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            outline: none;
        }
        select {
            background-color: #1b1b3a;
            color: #ffffff;
        }
        button {
            background-color: #e94560;
            color: #ffffff;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0f3460;
        }
        .score-display {
            text-align: center;
            font-size: 24px;
            margin: 20px 0;
            font-weight: bold;
        }
        #commentaryBox {
            padding: 15px;
            background-color: #0f3460;
            border-radius: 5px;
            border: 1px solid #1b1b3a;
            text-align: center;
        }
        footer {
            background-color: #0f3460;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #b8b8b8;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <div class="header-content">
            <h1>Dynamic Cricket Scoring Interface</h1>
            <button class="profile-button" onclick="window.location.href='profile.php'">My Profile</button>
            </div>
    </header>

    <div class="panel">
        <h2>Team Setup</h2>
        <div class="dropdown-container">
            <div>
                <label for="battingTeam">Batting Team:</label>
                <select id="battingTeam" onchange="loadPlayers('batting')">
                    <option value="">Select Team</option>
                    <?php
                    foreach ($teams as $team) {
                        echo "<option value=\"$team\">$team</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="bowlingTeam">Bowling Team:</label>
                <select id="bowlingTeam" onchange="loadPlayers('bowling')">
                    <option value="">Select Team</option>
                    <?php
                    foreach ($teams as $team) {
                        echo "<option value=\"$team\">$team</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="panel">
        <h2>Choose Players</h2>
        <div class="dropdown-container">
            <div>
                <label for="batsman">Batsman:</label>
                <select id="batsman">
                    <option value="">Select Batsman</option>
                </select>
            </div>
            <div>
                <label for="bowler">Bowler:</label>
                <select id="bowler">
                    <option value="">Select Bowler</option>
                </select>
            </div>
        </div>
    </div>

    <div class="panel">
        <h2>Score Board</h2>
        <div class="score-display">
            <span id="totalScore">0/0</span> | Overs: <span id="overs">0.0</span>
        </div>
        <div class="button-grid">
            <button>Dot Ball</button>
            <button>1 Run</button>
            <button>2 Runs</button>
            <button>3 Runs</button>
            <button>Four</button>
            <button>Six</button>
            <button>Wide</button>
            <button>No Ball</button>
            <button>Bye</button>
            <button>Leg Bye</button>
            <button>Wicket</button>
        </div>
        <button id="endInningsBtn" style="margin-top: 20px;">End Innings</button>
    </div>

    <div class="panel">
        <h2>Live Commentary</h2>
        <div id="commentaryBox">Awaiting action...</div>
    </div>

    <footer>
        &copy; 2024 Cricket Scoring Interface
    </footer>
</div>

<script src="scoring.js"></script>

</body>
</html>
