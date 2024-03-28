<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            background-color: burlywood;
            color: rgb(153, 0, 153);
        }
    </style>
</head>
<body>
<?php
require(__DIR__ . "/partials/nav.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Home Page</title>
    <link rel="stylesheet" href="styles.css">
    <script src="helpers.js"></script>
</head>
<body>

<h1>Home</h1>

<?php
if (is_logged_in()) {
    echo "Welcome, " . get_user_email();
} else {
    echo "You're not logged in, please press the login link above, or register a new account:)";
}

?>
