<?php
$servername = "db.ethereallab.app";  
$username = "ms2969";  
$password = "0AGbYXNA7AqE";  
$dbname = "ms2969";  
$port = 3306; 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$score = $_POST['score'];
$username = $_POST['username'];

$sql = "UPDATE Users SET score = score + ? WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $score, $username);

if ($stmt->execute()) {
    echo "Score submitted successfully";
} else {
    echo "Error updating score: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
