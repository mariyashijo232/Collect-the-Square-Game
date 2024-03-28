<?php

require(__DIR__ . "/lib/db.php");

$query = "SELECT 'test' from dual";
$db = getDB();
$stmt = $db->query($query);
$result = $stmt->fetch();
echo "<pre>" . var_export($result, true) . "</pre>";
?>