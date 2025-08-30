<?php
require 'session_check.php';
require 'db.php';

$zprava = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xml_file'])) {
    $xmlFile = $_FILES['xml_file']['tmp_name'];

    $xml = new DOMDocument();
    $xml->load($xmlFile);

    // Validace vůči XSD
    if (!$xml->schemaValidate('data/hry.xsd')) {
        $zprava = "Neplatné XML podle hry.xsd!";
    } else {
        $hry = $xml->getElementsByTagName('hra');
        $vlozeno = 0;

        foreach ($hry as $hra) {
            $nazev = $hra->getElementsByTagName('nazev')->item(0)?->nodeValue;
            $zanr = $hra->getElementsByTagName('zanr')->item(0)?->nodeValue;
            $platforma = $hra->getElementsByTagName('platforma')->item(0)?->nodeValue;
            $rok = $hra->getElementsByTagName('rok')->item(0)?->nodeValue;
            $hodnoceni = $hra->getElementsByTagName('hodnoceni')->item(0)?->nodeValue;

            if ($nazev && $rok && $hodnoceni) {
                $stmt = $pdo->prepare("INSERT INTO hry (nazev, zanr, platforma, rok, hodnoceni, uzivatel_id) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $nazev,
                    $zanr,
                    $platforma,
                    $rok,
                    $hodnoceni,
                    $_SESSION['user_id']
                ]);
                $vlozeno++;
            }
        }

        $zprava = "Importováno $vlozeno her.";
    }
}
?>

<h2>Import her z XML</h2>

<?php if ($zprava) echo "<p><b>$zprava</b></p>"; ?>

<form method="post" enctype="multipart/form-data">
    <label>Vyber XML soubor:</label>
    <input type="file" name="xml_file" accept=".xml" required>
    <button type="submit">Importovat</button>
</form>

<p><a href="index.php">Zpět na přehled</a></p>
