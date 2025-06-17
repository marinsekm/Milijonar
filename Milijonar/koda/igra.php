<?php
include_once 'seja.php';
include_once 'povezava.php';

// Preveri, ali je uporabnik prijavljen
if (!isset($_SESSION['id_u'])) {
    die("uporabnik ni prijavljen");
}


// Denarne nagrade
$lestvica = [
  100, 200, 300, 500, 1000,
  2000, 4000, 8000, 16000, 32000,
  64000, 125000
];

// Začni igro
if (!isset($_SESSION['trenutno'])) {
    $_SESSION['trenutno'] = 0;
}

// Naloži vprašanja iz baze
if (!isset($_SESSION['vprasanja'])) {
    $vprasanja = [];
    $sql = "SELECT v.id_vp, v.besedilo_vprasanja, o.odgovor_a, o.odgovor_b, o.odgovor_c, o.odgovor_d, o.pravilen_odgovor
            FROM vprasanja v
            JOIN odgovori o ON v.id_vp = o.id_vp
            ORDER BY RAND()
            LIMIT 12";
    $result = mysqli_query($link, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $vprasanja[] = [
            'vprasanje' => $row['besedilo_vprasanja'],
            'odgovori' => [
                $row['odgovor_a'],
                $row['odgovor_b'],
                $row['odgovor_c'],
                $row['odgovor_d']
            ],
            'pravilen' => $row['pravilen_odgovor']
        ];
    }
    $_SESSION['vprasanja'] = $vprasanja;
} else {
    $vprasanja = $_SESSION['vprasanja'];
}

// Uporabnik odneha
if (isset($_POST['odneha'])) {
    $denar = $lestvica[$_SESSION['trenutno'] - 1] ?? 0;

    $id = (int)$_SESSION['id_u'];
    $dosezeno = (int)($_SESSION['trenutno'] - 1);
    $cas = '00:10:15';

    $sql = "INSERT INTO igre (id_u, dosezeno_vprasanje, zasluzen_denar, konec, porabljen_cas)
            VALUES ($id, $dosezeno, $denar, NOW(), '$cas')";
    if (!mysqli_query($link, $sql)) {
        die("Napaka pri shranjevanju (odneha): " . mysqli_error($link));
    }

    echo "<div style='text-align:center; margin-top:50px;'>
        <h1>Odnehali ste!</h1>
        <p>Zaslužili ste: " . number_format($denar, 0, ',', '.') . " €</p>
        <a href='igra.php'><button>Začni znova</button></a>
        <a href='index.php'><button>Domov</button></a>
        <a href='leaderboard.php'><button>Leaderboard</button></a>
        </div>";
    session_destroy();
    exit;
}

// Obdelava odgovora
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['odgovor'])) {
    $izbran = $_POST['odgovor'];
    $trenutni = $_SESSION['trenutno'];
    $pravilen = $vprasanja[$trenutni]['pravilen'];

    if ($izbran == $pravilen) {
        $_SESSION['trenutno']++;

        // Zmaga
        if ($_SESSION['trenutno'] >= count($vprasanja)) {
            $denar = end($lestvica);
            $id = (int)$_SESSION['id_u'];
            $dosezeno = count($vprasanja);
            $cas = '00:10:15';

            $sql = "INSERT INTO igre (id_u, dosezeno_vprasanje, zasluzen_denar, konec, porabljen_cas)
                    VALUES ($id, $dosezeno, $denar, NOW(), '$cas')";
            if (!mysqli_query($link, $sql)) {
                die("Napaka pri shranjevanju (zmaga): " . mysqli_error($link));
            }

            echo "<div>
                <h1>ČESTITKE! Zmagal/a si!</h1>
                <p>Zaslužil/a si: " . number_format($denar, 0, ',', '.') . " €</p>
                <a href='igra.php'><button>Začni znova</button></a>
                <a href='index.php'><button>Domov</button></a>
                <a href='leaderboard.php'><button>Leaderboard</button></a>
                </div>";
            session_destroy();
            exit;
        }

    } else {
        // Napačen odgovor
        $id = (int)$_SESSION['id_u'];
        $dosezeno = $_SESSION['trenutno'];
        $cas = '00:10:15';

        $sql = "INSERT INTO igre (id_u, dosezeno_vprasanje, zasluzen_denar, konec, porabljen_cas)
                VALUES ($id, $dosezeno, 0, NOW(), '$cas')";
        if (!mysqli_query($link, $sql)) {
            die("Napaka pri shranjevanju (napačen odgovor): " . mysqli_error($link));
        }

        echo "<div>
            <h1>Napačen odgovor!</h1>
            <p>Nisi nič zaslužil/a.</p>
            <a href='igra.php'><button>Poskusi znova</button></a>
            <a href='index.php'><button>Domov</button></a>
            <a href='leaderboard.php'><button>Leaderboard</button></a>
            </div>";
        session_destroy();
        exit;
    }
}

// Prikaži trenutno vprašanje
$trenutno_vprasanje = $vprasanja[$_SESSION['trenutno']];
$st = $_SESSION['trenutno'] + 1;
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Igra - Milijonar</title>
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>

<div class="bg"></div>

<div class="igra-obrazec">
<div class="vprasanje-box">
<h2>Vprašanje <?php echo $st; ?></h2>
<p><?php echo $trenutno_vprasanje["vprasanje"]; ?></p>

<form method="post" class="odgovori">
<?php foreach ($trenutno_vprasanje["odgovori"] as $i => $odgovor): 
    $crka = chr(65 + $i);
?>
<button type="submit" name="odgovor" value="<?php echo $crka; ?>">
    <?php echo "$crka) " . htmlspecialchars($odgovor); ?>
</button>
<?php endforeach; ?>
<br><br>
<button type="submit" name="odneha" style="background:#8a2be2; color:white; padding:10px 20px; border:none; cursor:pointer;">
    Odnehaj
</button>
</form>
</div>

<div class="lestvica">
<ul>
<?php for ($i = 0; $i < count($lestvica); $i++): ?>
    <li class="<?php echo ($i == $_SESSION['trenutno']) ? 'aktivno' : ''; ?>">
        <?php echo ($i + 1) . ". " . number_format($lestvica[$i], 0, ',', '.') . " €"; ?>
    </li>
<?php endfor; ?>
</ul>
</div>
</div>

</body>
</html>
