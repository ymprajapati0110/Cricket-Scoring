<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];

$email = '';
$dob = '';
$role = '';

$servername = "localhost";
$dbusername = "root";
$dbpassword = "#Yash01.";
$dbname = "cricket";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, htmlspecialchars($_POST['email']));
    $dob = mysqli_real_escape_string($conn, htmlspecialchars($_POST['dob']));
    $role = mysqli_real_escape_string($conn, htmlspecialchars($_POST['role']));

    $sql = "INSERT INTO user_profiles (username, email, dob, role) VALUES ('$username', '$email', '$dob', '$role')
            ON DUPLICATE KEY UPDATE email = '$email', dob = '$dob', role = '$role'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Profile updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating profile: " . $conn->error . "');</script>";
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('3.jpg'); /* Ensure this path is correct */
            background-position: center; /* Center the background image */
            background-size: cover; /* Scale the background image to cover the entire viewport */
            background-repeat: no-repeat; /* Prevent the image from repeating */
            margin: 0;
            padding: 0;
            color: #fff;
        }
        .container {
            max-width: 500px; 
            margin: 100px auto; 
            padding: 30px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        label {
            margin-bottom: 5px;
            display: block;
            font-weight: bold;
        }
        input, select {
            margin-bottom: 15px;
            padding: 12px;
            font-size: 16px;
            width: 100%; 
            border: none;
            border-radius: 5px;
        }
        input[type="email"],
        input[type="date"],
        select {
            background: #fff; 
            color: #333; 
        }
        button {
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            width: 100%; 
            border: none;
            border-radius: 5px;
            background: #007bff;
            color: #fff;
            transition: background 0.3s;
        }
        button:hover {
            background: #0056b3; 
        }
    </style>
    <script>
        function validateForm() {
            const email = document.getElementById('userEmail').value;
            const dob = document.getElementById('dob').value;
            const role = document.getElementById('role').value;
            let isValid = true;
            let message = "";

            if (!email.includes('@')) {
                message += "Please enter a valid email address with an '@' symbol.\n";
                isValid = false;
            }

            if (!dob) {
                message += "Date of Birth is required.\n";
                isValid = false;
            }

            if (!role) {
                message += "Role selection is required.\n";
                isValid = false;
            }

            if (!isValid) {
                alert(message);
            }

            return isValid;
        }

        function logout() {
            window.location.href = 'index.html';
        }
    </script>
</head>
<body>

<div class="container">
    <h1>User Profile</h1>
    <form id="userProfileForm" method="POST" action="" onsubmit="return validateForm();">
        <label>Username:</label>
        <p><?php echo htmlspecialchars($username); ?></p>

        <label for="userEmail">Email:</label>
        <input type="email" id="userEmail" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" required>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="batsman" <?php echo ($role == 'batsman') ? 'selected' : ''; ?>>Batsman</option>
            <option value="bowler" <?php echo ($role == 'bowler') ? 'selected' : ''; ?>>Bowler</option>
            <option value="wicketkeeper" <?php echo ($role == 'wicketkeeper') ? 'selected' : ''; ?>>Wicket Keeper</option>
            <option value="allrounder" <?php echo ($role == 'allrounder') ? 'selected' : ''; ?>>All-rounder</option>
        </select>

        <button type="submit">Save Profile</button>
    </form>
    
    <button onclick="logout()">Logout</button>
</div>

</body>
</html>
