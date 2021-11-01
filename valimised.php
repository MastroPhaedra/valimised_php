<?php
require('conf.php');
global $yhendus;
// uue nimi lisamine
if(!empty($_REQUEST['uusnimi'])){
$kask=$yhendus->prepare('INSERT INTO valimised (nimi, lisamisaeg) VALUES (?, Now())');
$kask->bind_param('s', $_REQUEST['uusnimi']);
$kask->execute();
header:("Location: $_SERVER[PHP_SELF]");
echo "<script type='text/javascript'>alert('Nimi on lisatud!');</script>"; //'$message'
//$yhendus->close();
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
    <h1>Valimiste leht</h1>
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
<h3><a href="https://makarov20.thkit.ee/PHP/phpLehestik/content/valimised/kommenteerimine.php">Kasutaja page</a></h3>
<h3><a href="https://makarov20.thkit.ee/PHP/phpLehestik/content/valimised/nimideHaldus.php">Nimide haldus</a></h3>
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
