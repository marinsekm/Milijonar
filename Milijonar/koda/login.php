<?php
include_once 'povezava.php';
include_once 'seja.php';

$napaka = "";

if (isset($_POST['sub'])) /*če je bila oddana forma*/ {
    $uporabnisko_ime = mysqli_real_escape_string($link, $_POST['u_ime']);
    $geslo = $_POST['pass'];
    $geslo_hash = sha1($geslo); // uporaba sha1 za varnost

$query = "SELECT * FROM uporabniki WHERE uporabnisko_ime='$uporabnisko_ime'";  
$result = mysqli_query($link, $query);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);

if ($geslo_hash === $row['geslo']) { /*preverjas ce se gesla ujemata*/
    $_SESSION['uporabnik'] = $uporabnisko_ime;
	$_SESSION['id_u'] = $row['id_u'];
    header("Location: index.php");
    exit();
    } else {
    $napaka = "Napačno geslo.";
    }
    } else {
    $napaka = "Uporabnik ne obstaja.";
    }
}
?>


<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="UTF-8">

<link rel="stylesheet" href="css/style.css">
<title>Prijava</title>
</head>

<body>
<div class="bg-blur"></div>
<header class="header"></header>

<div id="obrazec">
<h1>Prijava</h1>

<?php if ($napaka != "") echo "<p style='color:red;'>$napaka</p>"; ?> <!-- ce napaka ni prazna se izpise opozorilo-->

<form method="post" action="">
<input type="text" name="u_ime" placeholder="Uporabniško ime" class="uporab" required><br>
<input type="password" name="pass" placeholder="Geslo" class="geslo" required><br>
<input type="submit" name="sub" class="sub" value="Prijavi se"><br>
</form>

<p>Še nimaš računa? <a href="register.php">Registriraj se tukaj</a></p>
</div>
    <footer class="login">
      <a href="https://www.w3schools.com/howto/howto_css_login_form.asp?" class="viri">W3 Schools</a> |
	  <a href="css/slike/ozadje.jpg" class="viri">Ozadje</a> |
	  <a href="viri/prijava.php">Vodena_Vaja</a>
    </footer>
</body>
</html>
