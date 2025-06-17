<?php
include_once 'povezava.php';
include_once 'seja.php';

// Preveri ali je uporabnik prijavljen in admin
if (!isset($_SESSION['uporabnik'])) {
    header("Location: login.php");
    exit();
}

$uporabnisko_ime = $_SESSION['uporabnik'];
$query = "SELECT je_admin FROM uporabniki WHERE uporabnisko_ime='$uporabnisko_ime'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_assoc($result);

if (!$row || $row['je_admin'] != 1) {
    header("Location: index.php");
    exit();
}

// Obdelava dodajanja vprašanja
if (isset($_POST['dodaj_vprasanje'])) {
    $besedilo = mysqli_real_escape_string($link, $_POST['besedilo']);

    $query = "INSERT INTO vprasanja (besedilo_vprasanja) VALUES ('$besedilo')";
    mysqli_query($link, $query);
    $id_vprasanja = mysqli_insert_id($link);

    $odgovor_a = mysqli_real_escape_string($link, $_POST['odgovor_a']);
    $odgovor_b = mysqli_real_escape_string($link, $_POST['odgovor_b']);
    $odgovor_c = mysqli_real_escape_string($link, $_POST['odgovor_c']);
    $odgovor_d = mysqli_real_escape_string($link, $_POST['odgovor_d']);
    $pravilni = mysqli_real_escape_string($link, $_POST['pravilni']);

    $query = "INSERT INTO odgovori (odgovor_a, odgovor_b, odgovor_c, odgovor_d, pravilen_odgovor, id_vp)
              VALUES ('$odgovor_a', '$odgovor_b', '$odgovor_c', '$odgovor_d', '$pravilni', $id_vprasanja)";
    mysqli_query($link, $query);
    $sporocilo = "Vprašanje uspešno dodano!";
}

// Brisanje vprašanja
if (isset($_GET['izbrisi_vprasanje'])) {
    $id_vprasanja = intval($_GET['izbrisi_vprasanje']);
    mysqli_query($link, "DELETE FROM odgovori WHERE id_vp=$id_vprasanja");
    mysqli_query($link, "DELETE FROM vprasanja WHERE id_vp=$id_vprasanja");
    $sporocilo = "Vprašanje uspešno izbrisano!";
}

// Shrani spremembe urejenega vprašanja
if (isset($_POST['shrani_urejanje'])) {
    $id = intval($_POST['id_vprasanja']);
    $besedilo = mysqli_real_escape_string($link, $_POST['besedilo']);
    $query = "UPDATE vprasanja SET besedilo_vprasanja = '$besedilo' WHERE id_vp = $id";
    mysqli_query($link, $query);
    $sporocilo = "Vprašanje uspešno posodobljeno!";
}

// Pridobi vsa vprašanja
$vprasanja = mysqli_query($link, "SELECT * FROM vprasanja");
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Milijonar</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="content-wrapper">
    <div id="container">
        <h1>Admin Panel</h1>
        <?php if (isset($sporocilo)) echo "<p style='color:green;'>$sporocilo</p>"; ?>

        <h2>Dodaj novo vprašanje</h2>
        <form method="post">
            <textarea name="besedilo" class="vpr" placeholder="Besedilo vprašanja" required></textarea><br>
            <input type="text" name="odgovor_a" class="odg" placeholder="Odgovor A" required><br>
            <input type="text" name="odgovor_b" class="odg" placeholder="Odgovor B" required><br>
            <input type="text" name="odgovor_c" class="odg" placeholder="Odgovor C" required><br>
            <input type="text" name="odgovor_d" class="odg" placeholder="Odgovor D" required><br>
            <select name="pravilni" class="pravilen" required>
                <option value="">Pravilni odgovor</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select><br>
            <input type="submit" class="sub" name="dodaj_vprasanje" value="Dodaj vprašanje">
        </form>

        <?php
        // Če urejamo vprašanje, izpiši obrazec za urejanje
        if (isset($_GET['uredi'])):
            $id_uredi = intval($_GET['uredi']);
            $query = "SELECT * FROM vprasanja WHERE id_vp = $id_uredi";
            $result = mysqli_query($link, $query);
            $vprasanje_edit = mysqli_fetch_assoc($result);
        ?>
            <h2>Uredi vprašanje</h2>
            <form method="post">
                <input type="hidden" name="id_vprasanja" value="<?= $vprasanje_edit['id_vp'] ?>">
                <textarea name="besedilo" required><?= $vprasanje_edit['besedilo_vprasanja'] ?></textarea><br>
                <input type="submit" name="shrani_urejanje" value="Shrani spremembe">
            </form>
        <?php endif; ?>

        <h2>Vsa vprašanja</h2>
        <table border="1">
            <tr>
                <th>ID</th><th>Vprašanje</th><th>Akcije</th>
            </tr>
            <?php while ($vprasanje = mysqli_fetch_assoc($vprasanja)): ?>
                <tr>
                    <td><?= $vprasanje['id_vp'] ?></td>
                    <td><?= $vprasanje['besedilo_vprasanja'] ?></td>
                    <td>
                        <a href="admin.php?uredi=<?= $vprasanje['id_vp'] ?>">Uredi</a> |
                        <a href="admin.php?izbrisi_vprasanje=<?= $vprasanje['id_vp'] ?>" onclick="return confirm('Ste prepričani?')">Izbriši</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <a href="index.php"><button class="logout-btn">Domov</button></a>
    </div>
</div>
</body>
</html>
