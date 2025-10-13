<?php
// Adatbázis kapcsolat beállításai
define('DB_SERVER', 'localhost'); // Általában 'localhost', ha nem változtattál
define('DB_USERNAME', 'root'); // Cseréld ki a saját MySQL felhasználónevedre!
define('DB_PASSWORD', '');     // Cseréld ki a saját MySQL jelszavadra!
define('DB_NAME', 'shadowstride_db');     // A megadott adatbázis neve

// Kapcsolat létrehozása
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Kapcsolat ellenőrzése
if($mysqli === false){
    die("HIBA: Nem sikerült csatlakozni. " . $mysqli->connect_error);
}
?>