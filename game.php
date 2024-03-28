<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Game Page</title>
    <style>
        body {
            background-color: burlywood;
            color: rgb(153, 0, 153);
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <button type="submit" name="start_game">Start Game</button>
    </form>

    <?php
    
    if (isset($_POST['start_game'])) {
    
        header("Location: /IT202101/M1/gamestart.php");
        
    }
    ?>
</body>
</html>
