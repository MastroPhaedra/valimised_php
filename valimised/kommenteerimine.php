<?php
require('conf.php');
global $yhendus;
// uue kommentaari lisamine
if(isset($_REQUEST['uus_kommentaar'])){
    $kask=$yhendus->prepare('UPDATE valimised SET kommentaarid=CONCAT(kommentaarid, ?) WHERE id=?');
    $kommentlisa=$_REQUEST['kommentaar']."\n"; // <input type='text' name='kommentaar'> │ \n работает в связке с nl2br в <input type='text' name='kommentaar'>
    $kask->bind_param('si',$kommentlisa, $_REQUEST['uus_kommentaar']); //$url_width=
    $kask->execute();
    //mb_strwidth($url_width)
    header:("Location: $_SERVER[PHP_SELF]"); //$_SERVER[PHP_SELF]
//$yhendus->close();
}

// Update käsk
if(isset($_REQUEST["haal"])){
    $kask=$yhendus->prepare('UPDATE valimised SET punktid=punktid + 1 WHERE id=?');
    $kask->bind_param('i', $_REQUEST["haal"]);
    $kask->execute();
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <title>Valimiste leht</title>
    <link rel="stylesheet" type="text/css" href="../../style/php_style.css">
</head>
<body>
<header>
    <h1>Valimiste leht + kommenteerimine</h1>
</header>
<?php
include('valimised_navigation.php');
?>
<main>

    <!--valimiste tabeli sisu vaatamine andmebaasist-->
    <?php
    global $yhendus;
    $kask=$yhendus->prepare('SELECT id, nimi, punktid, kommentaarid FROM valimised WHERE avalik=1 ORDER BY punktid DESC');
    $kask->bind_result($id, $nimi, $punktid, $kommentaarid);
    $kask->execute();

    // table
    echo "<table border='1'>";
    echo "<tr><th>Nimi</th><th>Punktid</th><th>Anna oma hääl</th><th>Kommentaarid</th></tr>";

    while($kask->fetch()){
        echo "<tr>";
        echo "<td>".htmlspecialchars($nimi)."</td>";
        echo "<td>".($punktid)."</td>";
        echo "<td><a href='?haal=$id'>Lisa +1 punkt</a></td>";
        echo "<td>".nl2br(htmlspecialchars($kommentaarid))."</td>"; //nl2br работает в связке с \n в $kommentlisa
        echo "<td>
                <form action='?'>
                    <input type='hidden' name='uus_kommentaar' value='$id'>
                    <input type='text' name='kommentaar'>
                    <input type='submit' value='Lisa kommentaar'>
                </form></td>";
        echo "</tr>";

    }
    echo "</table>";
    $yhendus->close();
    ?>
</main>
<?php
include('../../footer.php');
?>
</body>
</html>
<!--
CREATE TABLE valimised(
	id INT PRIMARY KEY AUTO_INCREMENT,
    nimi VARCHAR(100),
    lisamisaeg datetime,
    punktid INT DEFAULT 0,
    kommentaarid TEXT,
    avalik INT DEFAULT 1
);

INSERT INTO valimised (nimi, lisamisaeg, punktid, kommentaarid, avalik)
VALUES ('Tavisaar', '2021-09-09', 80, 'Väga hea raamat', 1);

SELECT * FROM valimised;
-->
