<?php
session_start();

if ($_POST['username'] == 'your_username' && $_POST['password'] == 'your_password') {
    $_SESSION['user_id'] = 1; 
    header("Location: game.php"); 
    exit();
} else {
    header("Location: login.php"); 
    exit();
}
?>
