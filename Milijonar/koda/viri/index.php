<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Domača stran</title>
        <style>
            #ja{float:right;}
        </style>
    </head>
    <body>
        <div id="glava">

            <?php

            if(isset($_SESSION['idu']))
            {
                // Uporabnik je prijavljen
                echo $_SESSION['name'] . " " . $_SESSION['surname'];
                echo ' <a id="ja" href="odjava_uporabnikov.php">Odjava</a>';
            } else {
                // Uporabnik NI prijavljen
                echo '<a id="ja" href="prijava_uporabnikov.php">Prijava</a>';
            }
            ?>

        </div>
        <h1>Vodena vaja</h1>
        <ol>
            <!-- <li><a href="baza.php">Povezava na bazo</a></li> -->
            <li><a href="izpis_predmetov.php">Izpis predmetov</a></li>
            <li><a href="izpis_lastnikov.php">Izpis uporabnikov</a></li>
            <li><a href="vnos_podatkov.php">Vstavljanje predmetov</a></li>
            <li><a href="vnos_uporabnikov.php">Vstavljanje uporabnikov</a></li>
            <!-- <li><a href="prijava.php">Prijava uporabnika</a></li> -->            
        </ol>
        <p>
        <a href="../index.php">Nazaj</a> |
        </p>
    </body>
</html>