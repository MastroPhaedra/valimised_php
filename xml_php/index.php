<?php
// https://www.metshein.com/unit/xml-xml-andmete-salvestamine-php-abil/

// andmete salvestamine XML faili, kus igakord luuakse uus fail (1)
$andmed=simplexml_load_file('andmeteBaas.xml');

//andmete salvestamine XML faili, kus andmed lisatakse juurde
if(isset($_POST['submit1']) && !empty($_POST['nimi1']) && !empty($_POST['hind1']) && !empty($_POST['varv1']) && !empty($_POST['lisade_nimi1']) && !empty($_POST['suurus1'])){ // && !empty($_POST['nimi'])

    $toodenimi=$_POST['nimi1'];
    $toodehind=$_POST['hind1'];
    $toodevarv=$_POST['varv1'];
    $toodelisade_nimi=$_POST['lisade_nimi1'];
    $toodesuurus=$_POST['suurus1'];

    $xml_tooded=$andmed->addChild('toode');
    $xml_tooded->addChild('nimi', $toodenimi); // $andmed->addChild('toode')->addChild('nimi', $toodenimi) -- ВСЁ ДОБАВОЯЕТСЯ К $andmed --
    $xml_tooded->addChild('hind', $toodehind); // $andmed->addChild('toode')->addChild('hind', $toodehind) -- ВСЁ ДОБАВОЯЕТСЯ К $andmed --
    $xml_tooded->addChild('varv', $toodevarv); // $andmed->addChild('toode')->addChild('varv', $toodevarv) -- ВСЁ ДОБАВОЯЕТСЯ К $andmed --

    $lisad=$xml_tooded->addChild('lisad');
    $lisad->addChild('nimi', $toodelisade_nimi); //первый параметр считывает переменную из XML, вторая из этого php файла
    $lisad->addChild('suurus', $toodesuurus); //первый параметр считывает переменную из XML, вторая из этого php файла

    $xmlDoc=new DOMDocument("1.0","UTF-8");
    $xmlDoc->loadXML($andmed->asXML(), LIBXML_NOBLANKS);
    $xmlDoc->formatOutput=true;
    $xmlDoc->preserveWhiteSpace = false;

    $xmlDoc->save("andmeteBaas.xml");
    header("refresh: 0;");
}

// XML faili täiendamine uute ridadega (2)
if(isset($_POST['submit2']) && !empty($_POST['nimi2']) && !empty($_POST['hind2']) && !empty($_POST['varv2']) && !empty($_POST['lisade_nimi2']) && !empty($_POST['suurus2'])){ // && !empty($_POST['nimi'])

    $xmlDoc2=new DOMDocument("1.0","UTF-8");
    $xmlDoc2->preserveWhiteSpace = false;
    $xmlDoc2->load("andmeteBaas.xml");
    $xmlDoc2->formatOutput=true;

    $toodenimi2=$_POST['nimi2'];
    $toodehind2=$_POST['hind2'];
    $toodevarv2=$_POST['varv2'];
    $toodelisade_nimi2=$_POST['lisade_nimi2'];
    $toodesuurus2=$_POST['suurus2'];

    $xml_root2 = $xmlDoc2->documentElement;
    $xmlDoc2->appendChild($xml_root2);

    $xml_tooded2 = $xmlDoc2->createElement("toode");
    $xmlDoc2->appendChild($xml_tooded2);

    $xml_root2->appendChild($xml_tooded2); // Чем эта строка отличается от предыдущей, и зачем мы её пишем?

    $xml_tooded2->appendChild($xmlDoc2->createElement('nimi', $toodenimi2));
    $xml_tooded2->appendChild($xmlDoc2->createElement('hind', $toodehind2));
    $xml_tooded2->appendChild($xmlDoc2->createElement('varv', $toodevarv2));

    $lisad2=$xml_tooded2->appendChild($xmlDoc2->createElement('lisad'));

    $lisad2->appendChild($xmlDoc2->createElement('nimi', $toodelisade_nimi2)); //первый параметр считывает переменную из XML, вторая из этого php файла
    $lisad2->appendChild($xmlDoc2->createElement('suurus', $toodesuurus2)); //первый параметр считывает переменную из XML, вторая из этого php файла

    $xmlDoc2->save("andmeteBaas.xml");
    header("refresh: 0;");
}

// Vormist saadud andmete lisamine XML faili (3)
if(isset($_POST['submit3']) && !empty($_POST['nimi']) && !empty($_POST['hind']) && !empty($_POST['varv']) && !empty($_POST['lisade_nimi']) && !empty($_POST['suurus'])){
    $xmlDoc3 = new DOMDocument("1.0","UTF-8");
    $xmlDoc3->preserveWhiteSpace = false;
    $xmlDoc3->load('andmeteBaas.xml');
    $xmlDoc3->formatOutput = true;

    $xml_root3 = $xmlDoc3->documentElement;
    $xmlDoc3->appendChild($xml_root3);

    $xml_tooded3 = $xmlDoc3->createElement("toode");
    $xmlDoc3->appendChild($xml_tooded3);

    $xml_root3->appendChild($xml_tooded3);

    $lisad3=$xml_tooded3->appendChild($xmlDoc3->createElement('lisad'));

    unset($_POST['submit3']);
    foreach($_POST as $voti=>$vaartus){
        if($voti=='lisade_nimi'){
            $kirje = $xmlDoc3->createElement('nimi',$vaartus);
            $lisad3->appendChild($kirje);
        } elseif ($voti=='suurus'){
            $kirje = $xmlDoc3->createElement($voti,$vaartus);
            $lisad3->appendChild($kirje);
        }
        else {
            $kirje = $xmlDoc3->createElement($voti,$vaartus);
            $xml_tooded3->appendChild($kirje);
        }
        /*if($voti=='nimi' or $voti=='hind' or $voti=='varv'){
            $kirje = $xmlDoc3->createElement($voti,$vaartus);
            $xml_tooded3->appendChild($kirje);
        }
        else {
            if($voti=='lisade_nimi'){
                $kirje = $xmlDoc3->createElement('nimi',$vaartus);
                $lisad3->appendChild($kirje);
            } else{
                $kirje = $xmlDoc3->createElement($voti,$vaartus);
                $lisad3->appendChild($kirje);
            }
        }*/
    }
    $xmlDoc3->save('andmeteBaas.xml');
    header("refresh: 0;");
}
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
<h1>XML andmete lugemine PHP abil</h1>
<h3>Esimese toode nimi:</h3>
<h4 style="color:red;"><?php
    echo $andmed->toode[0]->nimi;
?></h4>
<!--<br><br>-->
<table>
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
<h1>Vormist saadud andmete lisamine XML faili (uus XML fail)</h1>
<form method="post" action="" name="vorm1">
    <label for="nimi1">Toode nimi</label>
    <input type="text" id="nimi1" name="nimi1">
    <br>
    <label for="hind1">Toode hind</label>
    <input type="text" id="hind1" name="hind1">
    <br>
    <label for="varv1">Toode varv</label>
    <input type="text" id="varv1" name="varv1">
    <br>
    <label for="lisade_nimi1">Toode lisade nimi</label>
    <input type="text" id="lisade_nimi1" name="lisade_nimi1">
    <br>
    <label for="suurus1">Toode suurus</label>
    <input type="text" id="suurus1" name="suurus1">
    <br>
    <input type="submit" value="Sisesta" id="submit1" name="submit1">
</form>
<h1>XML faili täiendamine uute ridadega</h1>
<form method="post" action="" name="vorm2">
    <label for="nimi2">Toode nimi</label>
    <input type="text" id="nimi2" name="nimi2">
    <br>
    <label for="hind2">Toode hind</label>
    <input type="text" id="hind2" name="hind2">
    <br>
    <label for="varv2">Toode varv</label>
    <input type="text" id="varv2" name="varv2">
    <br>
    <label for="lisade_nimi2">Toode lisade nimi</label>
    <input type="text" id="lisade_nimi2" name="lisade_nimi2">
    <br>
    <label for="suurus2">Toode suurus</label>
    <input type="text" id="suurus2" name="suurus2">
    <br>
    <input type="submit" value="Sisesta" id="submit2" name="submit2">
</form>
<h1>Vormist saadud andmete lisamine XML faili (+1 variant)</h1>
<form method="post" action="" name="vorm3">
    <label for="nimi">Toode nimi</label>
    <input type="text" id="nimi" name="nimi">
    <br>
    <label for="hind">Toode hind</label>
    <input type="text" id="hind" name="hind">
    <br>
    <label for="varv">Toode varv</label>
    <input type="text" id="varv" name="varv">
    <br>
    <label for="lisade_nimi">Toode lisade nimi</label>
    <input type="text" id="lisade_nimi" name="lisade_nimi">
    <br>
    <label for="suurus">Toode suurus</label>
    <input type="text" id="suurus" name="suurus">
    <br>
    <input type="submit" value="Sisesta" id="submit3" name="submit3">
</form>
</main>
    <?php
    include('../../footer.php');
    ?>
</body>
</html>
