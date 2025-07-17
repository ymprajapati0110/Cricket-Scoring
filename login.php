<?php
session_start();

$servername = "localhost";
$dbusername = "root";
$dbpassword = "#Yash01.";
$dbname = "cricket";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $userType = $_POST['userType'];

    if ($userType === "existing") {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username; 
                header("Location: home.php"); 
                exit();
            } else {
                echo "<script>alert('Incorrect password.'); window.location.href='login.html';</script>";
            }
        } else {
            echo "<script>alert('Username does not exist.'); window.location.href='login.html';</script>";
        }
    } else if ($userType === "new") {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<script>alert('Username already exists. Please choose another.'); window.location.href='login.html';</script>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

            if ($conn->query($insert_sql) === TRUE) {
                $_SESSION['username'] = $username; 
                header("Location: home.php"); 
                exit();
            } else {
                echo "<script>alert('Error creating account.'); window.location.href='login.html';</script>";
            }
        }
    }
}

$conn->close();
?>
