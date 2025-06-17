<?php
include_once 'seja.php';
include 'povezava.php';

$sql = "SELECT u.uporabnisko_ime, i.zasluzen_denar, i.konec 
        FROM igre i
        JOIN uporabniki u ON i.id_u = u.id_u
        WHERE i.zasluzen_denar > 0
        ORDER BY i.zasluzen_denar DESC
        LIMIT 10";

$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
  <meta charset="UTF-8">
  
<link rel="stylesheet" href="css/leaderboard.css">
  <title>Leaderboard</title>
</head>
<body>

  <h1>Lestvica najboljših</h1>

  <table>
    <tr>
      <th>Rank</th>
      <th>Igralec</th>
      <th>Denar (€)</th>
      <th>Datum</th>
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
  <a href="index.php" class="gumb">Nazaj na domov</a>

</body>
  <footer>
  <a href="https://www.w3schools.com/php/php_mysql_intro.asp">W3 Schools</a>
  </footer>
</html>
