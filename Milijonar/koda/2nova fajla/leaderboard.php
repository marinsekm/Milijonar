<?php
include_once 'seja.php';
include 'povezava.php';

$sql = "SELECT uporabniki.uporabnisko_ime, igre.zasluzen_denar, igre.konec 
FROM igre 
JOIN uporabniki ON igre.id_ig = uporabniki.id_ig
ORDER BY igre.zasluzen_denar DESC
LIMIT 10";


$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="sl">
<head>

  <meta charset="UTF-8">
  <title>Leaderboard</title>
  
<style>
body {
  font-family: Arial, sans-serif;
  background: #f2f2f2;
  text-align: center;
  padding: 40px;
    }
	
h1 {
  color: #333;
}

table {
  margin: auto;
  width: 80%;
  border-collapse: collapse;
  background: #fff;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
 }
 
th, td {
  padding: 12px;
  border: 1px solid #ddd;
}

th {
  background-color: #7B3FBF;
  color: white;
}


 a {
  margin-top: 30px;
  display: inline-block;
  text-decoration: none;
  background: #7B3FBF;;
  color: white;
  padding: 10px 20px;
  border-radius: 6px;
}


</style>
</head>
<body>

  <h1>Lestvica najboljših</h1>

<table>
<tr>
<th> Rank</th>
<th> Igralec</th>
<th> Denar (€)</th>
<th> Datum</th>
</tr>

<?php
if ($result && mysqli_num_rows($result) > 0) {
$rank = 1;
while ($row = mysqli_fetch_assoc($result)) {
echo "<tr>";
echo "<td>" . $rank++ . "</td>";
echo "<td>" . htmlspecialchars($row['uporabnisko_ime']) . "</td>";
echo "<td>" . number_format($row['zasluzen_denar'], 0, ',', '.') . "</td>";
echo "<td>" . date("d.m.Y H:i", strtotime($row['konec'])) . "</td>";
echo "</tr>";
 }
} else {
 echo "<tr><td colspan='4'>Ni podatkov za prikaz.</td></tr>";
}
?>

</table>

<a href="index.php"> Nazaj na domov</a>

</body>
</html>
