<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile!</title>
    <style>
        body {
            background-color: burlywood;
            color: rgb(153, 0, 153);
        }
    </style>
</head>
<body>
<?php
require_once(__DIR__ . "/partials/nav.php");
require_once(__DIR__ . "/lib/db.php");

if (isset($_POST["save"])) {
    $email = se($_POST, "email", null, false);
    $username = se($_POST, "username", null, false);

    $params = [":email" => $email, ":username" => $username, ":id" => get_user_id()];
    $db = getDB();

    try {
        $stmt = $db->prepare("UPDATE Users SET email = :email, username = :username WHERE id = :id");
        $stmt->execute($params);
        flash("Profile saved", "success");
    } catch (PDOException $e) {
        handleDatabaseError($e);
    }

    try {
        $stmt = $db->prepare("SELECT scores FROM Users WHERE id = :id");
        $stmt->execute([":id" => get_user_id()]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $_SESSION["user"]["scores"] = $user["scores"];
        }
    } catch (PDOException $e) {
        flash("An unexpected error occurred while fetching the score, please try again", "danger");
    }

    // Check and update password
    $current_password = se($_POST, "currentPassword", null, false);
    $new_password = se($_POST, "newPassword", null, false);
    $confirm_password = se($_POST, "confirmPassword", null, false);

    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password === $confirm_password) {
            try {
                // Validate current password
                $stmt = $db->prepare("SELECT password FROM Users WHERE id = :id");
                $stmt->execute([":id" => get_user_id()]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if (isset($result["password"]) && password_verify($current_password, $result["password"])) {
                    // Update password
                    $query = "UPDATE Users SET password = :password WHERE id = :id";
                    $stmt = $db->prepare($query);
                    $stmt->execute([":id" => get_user_id(), ":password" => password_hash($new_password, PASSWORD_BCRYPT)]);
                    flash("Password reset", "success");
                } else {
                    flash("Current password is invalid", "warning");
                }
            } catch (PDOException $e) {
                handleDatabaseError($e);
            }
        } else {
            flash("New passwords don't match", "warning");
        }
    }
}

$email = get_user_email();
$username = get_username();
$scores = isset($_SESSION["user"]["scores"]) ? $_SESSION["user"]["scores"] : "N/A"; // Update to "scores"


?>

<form method="POST" onsubmit="return validate(this);">
    <div class="mb-3">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php se($email); ?>" />
    </div>
    <div class="mb-3">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php se($username); ?>" />
    </div>

    <div>Password Reset</div>
    <div class="mb-3">
        <label for="cp">Current Password</label>
        <input type="password" name="currentPassword" id="cp" />
    </div>
    <div class="mb-3">
        <label for="np">New Password</label>
        <input type="password" name="newPassword" id="np" />
    </div>
    <div class="mb-3">
        <label for="conp">Confirm Password</label>
        <input type="password" name="confirmPassword" id="conp" />
    </div>

    <!-- Score Information -->
    <div class="mb-3">
    <label for="scores">Scores</label>
    <input type="text" name="scores" id="scores" value="<?php echo $scores; ?>" />
</div>
    <input type="submit" value="Update Profile" name="save" />
</form>

<script>
    function validate(form) {
        let pw = form.newPassword.value;
        let con = form.confirmPassword.value;
        let isValid = true;
        if (pw !== con) {
            let flash = document.getElementById("flash");
            let outerDiv = document.createElement("div");
            outerDiv.className = "row justify-content-center";
            let innerDiv = document.createElement("div");
            innerDiv.className = "alert alert-warning";
            innerDiv.innerText = "Password and Confirm password must match";
            outerDiv.appendChild(innerDiv);
            flash.appendChild(outerDiv);
            isValid = false;
        }
        return isValid;
    }
</script>

<?php
require(__DIR__ . "/partials/flash.php");
?>
