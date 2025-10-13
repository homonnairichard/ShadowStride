<?php
// Indítsuk el a munkamenetet (session)
session_start();

// Beállítjuk a kapcsolatot
require_once 'config.php';

// Ellenőrizzük, hogy az űrlap POST kéréssel érkezett-e
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // 1. Felhasználónév/Email ellenőrzés (egyediség)
    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("ss", $param_username, $param_email);
        $param_username = $username;
        $param_email = $email;
        
        if($stmt->execute()){
            $stmt->store_result();
            if($stmt->num_rows > 0){
                echo "Hiba: A felhasználónév vagy az email már foglalt.";
                // Vissza irányítás a regisztrációs oldalra
                exit();
            }
        } else{
            echo "Hoppá! Valami hiba történt. Próbálja meg később.";
            exit();
        }
        $stmt->close();
    }

    // 2. Felhasználó beillesztése az adatbázisba
    // register.php 37. sor környékén - HASZNÁLD EZT
    $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";    
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("sss", $param_username, $param_email, $param_password_hash);
        
        $param_username = $username;
        $param_email = $email;
        // Jelszó hashelése a biztonság érdekében! SOHA NE TÁROLD TISZTA SZÖVEGBEN!
        $param_password_hash = password_hash($password, PASSWORD_DEFAULT); 
        
        if($stmt->execute()){
            // Sikeres regisztráció, átirányítás a bejelentkezési oldalra
            header("location: ucp.html?registered=success");
        } else{
            echo "Hiba: A regisztráció sikertelen volt. Próbálja újra.";
        }
        $stmt->close();
    }
    
    // Kapcsolat bezárása
    $mysqli->close();
}
?>