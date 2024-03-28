<?php

require(__DIR__ . "/lib/functions.php");

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
} else {
    header("Location: login.php");
    exit();
}

$db = getDB();
$stmt = $db->prepare("SELECT user_roles FROM Users WHERE id = :user_id");
$stmt->execute([":user_id" => $id]);

if ($stmt->errorCode() !== '00000') {
    die("Database error: " . implode(", ", $stmt->errorInfo()));
}

$user_roles = $stmt->fetch(PDO::FETCH_ASSOC);

echo "User Roles: ";
print_r($user_roles);

if (!empty($user_roles) && strpos($user_roles['user_roles'], "admin") !== false) {
    echo "Welcome to the Admin-only page!";
} else {
    echo '<script>';
    echo 'alert("You do not have permission for this page.");';
    echo 'window.location.href = "home.php";'; 
    echo '</script>';
    die(); 
}
?>
