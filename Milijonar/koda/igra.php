<?php
include_once 'seja.php';
include 'vprasanja.php';
include 'povezava.php'; 

$lestvica = [
  100, 200, 300, 500, 1000,
  2000, 4000, 8000, 16000, 32000,
  64000, 125000
];

if (!isset($_SESSION['trenutno'])) {  //ce se igra zacenja, nastavi na prvo vprasanje//
    $_SESSION['trenutno'] = 0;
}

function shraniRezultat($link, $idUporabnika, $dosezenoVp, $zasluzenDenar, $porabljenCas) {  
    // Varnostni filter za vhodne podatke
    $idUporabnika = (int)$idUporabnika;
    $dosezenoVp = (int)$dosezenoVp;
    $zasluzenDenar = (int)$zasluzenDenar;
    $porabljenCas = mysqli_real_escape_string($link, $porabljenCas);

    // INSERT v tabelo igre
    $sql = "INSERT INTO igre (id_u, dosezeno_vprasanje, zasluzen_denar, konec, porabljen_cas) 
            VALUES ($idUporabnika, $dosezenoVp, $zasluzenDenar, NOW(), '$porabljenCas')";

    if (!mysqli_query($link, $sql)) {
        die("Napaka pri INSERT: " . mysqli_error($link));
    }

    $idIgre = mysqli_insert_id($link);

}



if (isset($_POST['odneha'])) {
    $denar = $lestvica[$_SESSION['trenutno'] - 1] ?? 0;

    if (isset($_SESSION['id_u'])) {
    $idUporabnika = $_SESSION['id_u'];
    $dosezenoVp = $_SESSION['trenutno'] - 1;
    $zasluzenDenar = $denar;
    $porabljenCas = '00:10:15';
    shraniRezultat($link, $idUporabnika, $dosezenoVp, $zasluzenDenar, $porabljenCas);
    }

    echo "<div style='text-align:center; margin-top:50px;'>
    <h1>Odnehali ste!</h1>
    <p>Zaslužili ste: " . number_format($denar, 0, ',', '.') . " €.</p>
    <a href='igra.php'><button>Začni znova</button></a>
    <a href='index.php'><button>Nazaj na domov</button></a>
    <a href='leaderboard.php'><button>Leaderboard</button></a>
    </div>";
    session_destroy();
    exit;
}
//ce je oddana forma z odg, ko je pravilen, gre naprej, napacen prekine igro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['odgovor'])) {
    $izbran = $_POST['odgovor'];
    $pravilen = $vprasanja[$_SESSION['trenutno']]['pravilen'];

if ($izbran == $pravilen) {
    $_SESSION['trenutno']++;
	
    if ($_SESSION['trenutno'] >= count($vprasanja)) {
    $denar = end($lestvica);

    if (isset($_SESSION['id_u'])) {
    $idUporabnika = $_SESSION['id_u'];
    $dosezenoVp = count($vprasanja);
    $zasluzenDenar = $denar;
    $porabljenCas = '00:10:15';
    shraniRezultat($link, $idUporabnika, $dosezenoVp, $zasluzenDenar, $porabljenCas);
}

    echo "<div style='text-align:center; margin-top:50px;'>
    <h1>ČESTITKE! Zmagal/a si!</h1>
    <p>Zaslužil/a si: " . number_format($denar, 0, ',', '.') . " €.</p>
    <a href='igra.php'><button>Začni znova</button></a>
    <a href='index.php'><button>Nazaj na domov</button></a>
    <a href='leaderboard.php'><button>Leaderboard</button></a>
    </div>";
    session_destroy();
    exit;
    }
    } else {
    if (isset($_SESSION['id_u'])) {
    $idUporabnika = $_SESSION['id_u'];
    $dosezenoVp = $_SESSION['trenutno'];
    $zasluzenDenar = 0;
    $porabljenCas = '00:10:15';
    shraniRezultat($link, $idUporabnika, $dosezenoVp, $zasluzenDenar, $porabljenCas);
    }

    echo "<div style='text-align:center; margin-top:50px; color:red;'>
    <h1>Napačen odgovor! Igra je končana.</h1>
    <p>Nisi nič denarja zaslužil/a.</p>
    <a href='igra.php'><button>Poskusi znova</button></a>
    <a href='index.php'><button>Nazaj na domov</button></a>
    <a href='leaderboard.php'><button>Leaderboard</button></a>
    </div>";
    session_destroy();
    exit;
    }
}

$trenutno_vprasanje = $vprasanja[$_SESSION['trenutno']];
$st = $_SESSION['trenutno'] + 1;
?>

<!DOCTYPE html>
<html lang="sl">
<head>

<meta charset="UTF-8">
<link rel="stylesheet" href="css/style.css">
<title>Igra - Milijonar</title>

</head>
<body>

<div class="bg"></div>

<div class="igra-obrazec">
<div class="vprasanje-box">
<h2>Vprašanje <?php echo $st; ?></h2>
<p><?php echo $trenutno_vprasanje["vprasanje"]; ?></p>

<form method="post" class="odgovori">
<?php foreach ($trenutno_vprasanje["odgovori"] as $i => $odgovor): ?>
<button type="submit" name="odgovor" value="<?php echo $i; ?>">
<?php echo chr(65 + $i) . ") " . htmlspecialchars($odgovor); ?>
</button>

<?php endforeach; ?>

<br><br>
<button type="submit" name="odneha" style="background:#8a2be2; color:white; padding:10px 20px; border:none; cursor:pointer;">
Odnehaj </button>
</form>
</div>

<div class="lestvica">
<ul>
<?php for ($i = 0; $i < count($lestvica); $i++): ?>
<li class="<?php echo ($i == $_SESSION['trenutno']) ? 'aktivno' : ''; ?>">
<?php echo ($i+1) . ". " . number_format($lestvica[$i], 0, ',', '.') . " €"; ?>
 </li>
 
<?php endfor; ?>
</ul>
</div>
</div>

</body>
</html>
