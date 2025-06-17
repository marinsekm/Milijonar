<?php
session_start();

if (isset($_POST['submit'])) {
    $mail = $_POST['mail'];
    $geslo = $_POST['geslo'];
    $geslo2 = sha1 ($geslo);

    $query = "SELECT * FROM lastniki WHERE mail='$mail' AND geslo='$geslo2'";

    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0)
    {
        $row=mysqli_fetch_array($result);
        //echo $row ['ime'] ." in ". $row ['priimek'];

        $_SESSION['name']=$row['ime'];
        $_SESSION['surname']=$row['priimek'];
        $_SESSION['idu']=$row['id_l'];
        $_SESSION['log']=TRUE;

        echo $_SESSION['name']." ".$_SESSION['surname'];

        echo "Prijava uspešna.";
        header("Location: index.php");
    }

    else
    {
        echo "Napačen mail ali geslo.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Prijava rezultat</title>

</head>
<body>
<h1>Prijava</h1>
<form method="post" action="#">
    <input type="mail" name="mail" placeholder="E-Pošta" required><br><br>
    <input type="password" name="geslo" placeholder="Geslo"><br><br>
    <input type="submit" name="submit" value="Prijavi se">
</form>
<br>
<a href="../login.php">Nazaj</a>
</body>
</html>
