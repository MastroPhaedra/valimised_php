<?php
// andmete salvestamine XML faili, kus igakord luuakse uus fail (1)
$uus_fail=(isset($_POST["uus_fail"])) && $_POST["uus_fail"];

// XML andmete salvestamine uusBaas.xml
if(isset($_POST['submit1']) && $uus_fail  && !empty($_POST['nimi1']) && !empty($_POST['hind1']) && !empty($_POST['varv1']) && !empty($_POST['lisade_nimi1']) && !empty($_POST['suurus1'])){
    $xmlDoc = new DOMDocument("1.0","UTF-8");
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->formatOutput = true;

    $xml_root = $xmlDoc->createElement("tooded");
    $xmlDoc->appendChild($xml_root);

    $xml_toode = $xmlDoc->createElement("toode");
    $xmlDoc->appendChild($xml_toode);

    $xml_root->appendChild($xml_toode);

    $xml_toode->appendChild($xmlDoc->createElement('nimi', $_POST['nimi1']));
    $xml_toode->appendChild($xmlDoc->createElement('hind', $_POST['hind1']));
    $xml_toode->appendChild($xmlDoc->createElement('varv', $_POST['varv1']));

    $lisad=$xml_toode->appendChild($xmlDoc->createElement('lisad'));

    $lisad->appendChild($xmlDoc->createElement('nimi', $_POST['lisade_nimi1'])); //первый параметр считывает переменную из XML, вторая из этого php файла
    $lisad->appendChild($xmlDoc->createElement('suurus', $_POST['suurus1'])); //первый параметр считывает переменную из XML, вторая из этого php файла

    $xmlDoc->save('uusBaas.xml');
    echo "<script type='text/javascript'>alert('Toode on salvestatud uuel andmebaasil (uusBaas.xml)!');</script>"; // уведомление

    //header("refresh: 0;");
}


// XML andmete täiendamine
if(isset($_POST['submit1']) && !$uus_fail  && !empty($_POST['nimi1']) && !empty($_POST['hind1']) && !empty($_POST['varv1']) && !empty($_POST['lisade_nimi1']) && !empty($_POST['suurus1'])){
    $xmlDoc = new DOMDocument("1.0","UTF-8");
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->load('andmeteBaas.xml');
    $xmlDoc->formatOutput = true;

    $xml_root = $xmlDoc->documentElement;
    $xmlDoc->appendChild($xml_root);

    $xml_toode = $xmlDoc->createElement("toode");
    $xmlDoc->appendChild($xml_toode);

    $xml_root->appendChild($xml_toode);

    $xml_toode->appendChild($xmlDoc->createElement('nimi', $_POST['nimi1']));
    $xml_toode->appendChild($xmlDoc->createElement('hind', $_POST['hind1']));
    $xml_toode->appendChild($xmlDoc->createElement('varv', $_POST['varv1']));

    $lisad=$xml_toode->appendChild($xmlDoc->createElement('lisad'));

    $lisad->appendChild($xmlDoc->createElement('nimi', $_POST['lisade_nimi1'])); //первый параметр считывает переменную из XML, вторая из этого php файла
    $lisad->appendChild($xmlDoc->createElement('suurus', $_POST['suurus1'])); //первый параметр считывает переменную из XML, вторая из этого php файла

    $xmlDoc->save('andmeteBaas.xml');
    echo "<script type='text/javascript'>alert('Toode on lisatud tavalisel andmebaasil!');</script>"; // уведомление

    //header("refresh: 0;"); //"Location: https://makarov20.thkit.ee/PHP/phpLehestik/content/xml_php/toode_salvestamine_valikuga.php",
}

// Otsing Toodenimi järgi

function searchByName($query){
    global $andmed;
    $result=array();
    foreach($andmed->toode as $toode){
        if(substr(strtolower($toode->nimi), 0, strlen($query)) == strtolower($query))
            array_push($result,$toode);
    }
    return $result;
}

$andmed=simplexml_load_file('andmeteBaas.xml');
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <title>XML andmete lugemine PHP abil</title>
    <link rel="stylesheet" type="text/css" href="../../style/php_style.css">
</head>
<body>
<header>
    <h1>XML andmete lugemine PHP abil</h1>
</header>
<?php
include('../php_matkaLeht/matk_navigation.php');
?>
<main>
    <!-- Otsing -->
    <h2>Otsing toodenimi järgi</h2>
    <form action="?" method="post">
        <label for="otsing">Otsing: </label>
        <input type="text" id="otsing" name="otsing" placeholder="Toode nimi">
        <input type="submit" value="Otsi">
    </form>
        <ul>
            <?php
                if (!empty($_POST["otsing"])){
                    $result=searchByName($_POST["otsing"]);
                    foreach ($result as $toode){
                        echo "<li><mark>";
                        echo $toode->nimi."</mark>, ".$toode->hind;
                        echo "</li>";
                    }
                }
            ?>
        </ul>
    <table border='1'>
        <tr>
            <th>Toodenimi</th>
            <th>Hind</th>
            <th>Värv</th>
            <th>Lisade nimi</th>
            <th>Lisade suurus</th>
        </tr>
        <?php
        foreach($andmed->toode as $toode){
            echo "<tr>";
            echo "<td>".$toode->nimi."</td>";
            echo "<td>".$toode->hind."</td>";
            echo "<td>".$toode->varv."</td>";
            echo "<td>".$toode->lisad->nimi."</td>";
            echo "<td>".$toode->lisad->suurus."</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <!-- lisamisvorm html failist -->
    <?php
        include ('lisamisvorm.html');
    ?>
    <h2>RSS uudised</h2>
    <?php
    $feed=simplexml_load_file('https://www.postimees.ee/rss');
    $linkide_arv=5;
    $loendur=1;
    foreach($feed->channel->item as $item){
        if($loendur<=$linkide_arv){
            echo "<li>";
            echo "<a href='$item->link' target='_blank'>$item->title</a>";
            echo "</li>";
            $loendur++;
        }
    }
    ?>
<h3><a href="https://github.com/MastroPhaedra/valimised_php-XML/tree/main/xml_php" target="_blank">GitHub</a></h3>
</main>
    <?php
    include('../../footer.php');
    ?>
</body>
</html>
