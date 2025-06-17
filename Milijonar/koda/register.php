<?php
include_once 'povezava.php';
include_once 'seja.php';

if (isset($_POST['sub'])) {
    $uporabnisko_ime = mysqli_real_escape_string($link, $_POST['uporabnik']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $geslo = $_POST['pass'];
    $geslo_hash = sha1($geslo);

    $query_check = "SELECT * FROM uporabniki WHERE uporabnisko_ime='$uporabnisko_ime' OR e_posta='$email'";
    $result_check = mysqli_query($link, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        $sporocilo = "Uporabniško ime ali e-pošta že obstaja!";
    } else {
        $query_insert = "INSERT INTO uporabniki (uporabnisko_ime, geslo, e_posta, datum_registracije)
                         VALUES ('$uporabnisko_ime', '$geslo_hash', '$email', NOW())";

        if (mysqli_query($link, $query_insert)) {
            $sporocilo = "Registracija uspešna! Preusmerjam na prijavo...";
            header("Location: login.php");
            exit();
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

  <div class="content">
    <div id="obrazec">
      <h1>Registracija</h1>

      <form method="post" action="register.php">
        <input type="text" name="uporabnik" placeholder="Uporabniško ime" class="uporab" required><br>
        <input type="email" name="email" placeholder="E-pošta" class="mail" required><br>
        <input type="password" name="pass" placeholder="Geslo" class="geslo" required><br>
        <input type="submit" name="sub" class="sub" value="Registriraj se"><br>
      </form>

      <p>Že imaš račun? <a href="login.php">Prijavi se tukaj</a></p>
    </div>

    <footer class="footer_registracija">
      <a href="https://www.w3schools.com/howto/howto_css_register_form.asp?" class="viri">W3 Schools</a> |
	  <a href="css/slike/ozadje.jpg" class="viri">Ozadje</a>
    </footer>
  </div>
</body>
</html>
