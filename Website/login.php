<?php
// Indítsuk el a munkamenetet (session)
session_start();

// Ellenőrizzük, hogy a felhasználó már be van-e jelentkezve
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: profile.php"); // Átirányítás a profil oldalra
    exit;
}

// Beállítjuk a kapcsolatot
require_once 'config.php';

// Ellenőrizzük, hogy az űrlap POST kéréssel érkezett-e
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ellenőrizzük, hogy az adatok megérkeztek-e
    if (empty(trim($_POST["username"])) || empty(trim($_POST["password"]))) {
        echo "Kérem, töltse ki mindkét mezőt.";
        exit;
    }
    
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // FIGYELEM: Biztosak vagyunk benne, hogy az oszlop neve 'password_hash' az adatbázisban?
    // Ha nem, akkor itt át kell írni a VALÓS oszlopnévre (pl. 'password').
    $sql = "SELECT id, username, password_hash FROM users WHERE username = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        
        // PARAMÉTER HOZZÁRENDELÉS (bind_param) - EZ HIÁNYZOTT!
        $param_username = $username;
        $stmt->bind_param("s", $param_username); 
        
        // Futtatjuk a lekérdezést
        if($stmt->execute()){
            // Eredmények lekérése a tárolt halmazba
            $stmt->store_result();
            
            // Ellenőrizzük, hogy a felhasználónév létezik-e (pontosan egy sor)
            if($stmt->num_rows == 1){                    
                
                // AZ EREDMÉNYEK KÖTÉSE (bind_result)
                $stmt->bind_result($id, $db_username, $hashed_password);
                
                if($stmt->fetch()){
                    // Jelszó ellenőrzése a hashelt jelszóval
                    if(password_verify($password, $hashed_password)){
                        
                        // Jelszó helyes, session indítása
                        $_SESSION["loggedin"] = true; // JAVÍTOTT: Itt "loggedin"-t használunk
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $db_username; // Az adatbázisból kinyert felhasználónév
                        
                        // Átirányítás a profil oldalra
                        header("location: profile.php");
                        exit;
                    } else{
                        // Jelszó hibás
                        echo "Hiba: A megadott jelszó érvénytelen.";
                    }
                }
            } else{
                // Felhasználónév nem található
                echo "Hiba: Nem található fiók ezzel a felhasználónévvel.";
            }
        } else{
            // Hiba a végrehajtás során
            echo "Hoppá! Valami hiba történt az adatbázisban. Próbálja meg később.";
        }
        $stmt->close();
    }
    
    // Kapcsolat bezárása
    $mysqli->close();
}
?>