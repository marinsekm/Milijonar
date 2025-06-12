<?php
include_once 'povezava.php';
include_once 'seja.php';

if (isset($_POST['sub'])) { // ce je obrazec oddan//
    $uporabnisko_ime = mysqli_real_escape_string($link, $_POST['uporabnik']); // za vasrnost//
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $geslo = $_POST['pass']; 

    
$geslo_hash = sha1($geslo); // SHA1 hashiranje gesla

 // Preveri ce uporabnik ali email ze obstaja//
$query_check = "SELECT * FROM uporabniki WHERE uporabnisko_ime='$uporabnisko_ime' OR e_posta='$email'";
$result_check = mysqli_query($link, $query_check);

if (mysqli_num_rows($result_check) > 0) {
    $sporocilo = "Uporabniško ime ali e-pošta že obstaja!";
    } else {
    // Vstavi novega uporabnika
    $query_insert = "INSERT INTO uporabniki (uporabnisko_ime, geslo, e_posta, datum_registracije)
VALUES ('$uporabnisko_ime', '$geslo_hash', '$email', NOW())";

if (mysqli_query($link, $query_insert)) {
    $sporocilo = "Registracija uspešna! Preusmerjam na prijavo...";
    header("Location: login.php");
    } else {
    $sporocilo = "Napaka pri registraciji.";
    }
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>

<meta charset="UTF-8">
<title>Registracija</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>
<div class="bg-blur"></div>
<header class="header"></header>

<div id="obrazec">
<h1>Registracija</h1>

<?php if (isset($sporocilo)) echo "<p style='color:red;'>$sporocilo</p>"; ?>

<form method="post" action="register.php">
<input type="text" name="uporabnik" placeholder="Uporabniško ime" required><br>
<input type="email" name="email" placeholder="E-pošta" required><br>
<input type="password" name="pass" placeholder="Geslo" required><br>
<input type="submit" name="sub" value="Registriraj se"><br>
</form>

<p>Že imaš račun? <a href="login.php">Prijavi se tukaj</a></p>
</div>
</body>
</html>
