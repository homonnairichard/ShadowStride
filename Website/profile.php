<?php
session_start();
// Ellenőrzés, hogy be van-e jelentkezve
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ucp.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title>Profil</title>
</head>
<body>
    <h1>Üdvözöljük, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
    <p>Ez a profiloldal. Sikeresen bejelentkezett.</p>
    <p><a href="logout.php">Kijelentkezés</a></p>
</body>
</html>