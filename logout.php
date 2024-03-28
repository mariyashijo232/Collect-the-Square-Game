<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        body {
            background-color: burlywood;
            color: rgb(153, 0, 153);
        }
    </style>
</head>
<body>
<?php
session_start();
require(__DIR__ . "/lib/functions.php");
reset_session();

flash("Successfully logged out", "success");
header("Location: login.php");
?>