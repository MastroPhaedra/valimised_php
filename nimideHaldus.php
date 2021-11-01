<?php
/*
// uue nimi lisamine
if(!empty($_REQUEST['uusnimi'])){
    $kask=$yhendus->prepare('INSERT INTO valimised (nimi, lisamisaeg) VALUES (?, Now())');
    $kask->bind_param('s', $_REQUEST['uusnimi']);
    $kask->execute();
    header:("Location: $_SERVER[PHP_SELF]");
//$yhendus->close();
}

// Update käsk
if(isset($_REQUEST["haal"])){
    $kask=$yhendus->prepare('UPDATE valimised SET punktid=punktid + 1 WHERE id=?');
    $kask->bind_param('i', $_REQUEST["haal"]);
    $kask->execute();
}
*/

require_once ('conf.php');
global $yhendus;
//peitmine (скрывать), avalik=0
if(isset($_REQUEST["peitmine"])){
    $kask=$yhendus->prepare('UPDATE valimised SET avalik=0 WHERE id=?');
    $kask->bind_param('i', $_REQUEST["peitmine"]);
    $kask->execute();
}

//avalikustutamine, avalik=1
if(isset($_REQUEST["avamine"])){
    $kask=$yhendus->prepare('UPDATE valimised SET avalik=1 WHERE id=?');
    $kask->bind_param('i', $_REQUEST["avamine"]);
    $kask->execute();
}

// andmete kustutamine
if(isset($_REQUEST["kustutasid"])){
    $kask=$yhendus->prepare("DELETE FROM valimised WHERE id=?");
    $kask->bind_param("i", $_REQUEST["kustutasid"]);
    $kask->execute();
}

// peidamine punktide nulliks
if(isset($_REQUEST["tühi"])){
    $kask=$yhendus->prepare("UPDATE valimised SET punktid=0 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["tühi"]);
    $kask->execute();
}

// kommentide tühistamine
if(isset($_REQUEST["tühi_komment"])){
    $kask=$yhendus->prepare("UPDATE valimised SET kommentaarid='' WHERE id=?");
    $kask->bind_param("i", $_REQUEST["tühi_komment"]);
    $kask->execute();
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <title>Valimiste nimide haldus</title>
    <link rel="stylesheet" type="text/css" href="../../style/php_style.css">
</head>
<body>
<header>
    <h1>Valimiste nimide haldus</h1>
</header>
<?php
include('../php_matkaLeht/matk_navigation.php');
?>
<main>
<h1>Uue nimi lisamine</h1>
<form action>
    <label for="uusnimi">Nimi</label>
    <input type="text" id="uusnimi" name="uusnimi" placeholder="Uusnimi">
    <input type="submit" value="OK">
</form>
<h1>Valimisnimede haldus</h1>

<!--valimiste tabeli sisu vaatamine andmebaasist-->
<?php
global $yhendus;
$kask=$yhendus->prepare('SELECT id, nimi, avalik, punktid, kommentaarid FROM valimised ORDER BY punktid DESC');
$kask->bind_result($id, $nimi, $avalik, $punktid, $kommentaarid);
$kask->execute();

// table
echo "<table>";
echo "<tr><th>Nimi</th><th>Seisund</th><th>Tegevus</th><th>Punktid</th><th>Peidamine nulliks</th><th>Kommentaar</th><th>Tühistamine komment</th><th>Kustutamine</th></tr>";

while($kask->fetch()){
    $avatekst="Ava";
    $param="avamine";
    $seisund="Peidetud";
    if($avalik==1){
        $avatekst="Peida";
        $param="peitmine";
        $seisund="Avatud";
    }
    echo "<tr>";
    echo "<td>".htmlspecialchars($nimi)."</td>";
    echo "<td>".($seisund)."</td>";
    echo "<td><a href='?$param=$id'>$avatekst</a></td>";
    echo "<td>".($punktid)."</td>";
    echo "<td><a href='?tühi=$id'>Peida</a></td>";
    echo "<td>".nl2br($kommentaarid)."</td>";
    echo "<td><a href='?tühi_komment=$id'>Tühista</a></td>";
    echo "<td><a href='".$_SERVER['REQUEST_URI']."?kustutasid=$id'>Kustuta</a></td>"; //$_SERVER[PHP_SELF]
    echo "</tr>";
}
echo "</table>";
$yhendus->close();
?>
<h3><a href="https://makarov20.thkit.ee/PHP/phpLehestik/content/valimised/kommenteerimine.php">Kasutaja page</a></h3>
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
