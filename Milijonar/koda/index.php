<?php
include_once 'seja.php';

if (!isset($_SESSION['uporabnik'])) /*preveri da je uporabnik prijavljen*/ {
    header("Location: login.php");
    exit();
}

$uporabnisko_ime = $_SESSION['uporabnik']; /*shrani se ime uporabnika*/
?>

<!DOCTYPE html> 
<html lang="sl">
<head>
<meta charset="UTF-8">

<link rel="stylesheet" href="css/style.css">
<title>Milijonar - Domov</title>
</head>
<body>

<div class="bg-blur"></div> <!-- -->

<header class="header-bar">
<a href="logout.php"><button class="logout-btn">Odjava</button></a>
</header>

<div id="obrazec">
<h1>Dobrodošli v Milijonar!</h1>
<p>Pozdravljeni, <?php echo htmlspecialchars($uporabnisko_ime); ?>!</p>
<a href="igra.php"><button>Začni igro</button></a><br><br>
<a href="leaderboard.php"><button>leaderboard</button></a><br><br>
</div>

</body>
</html>
