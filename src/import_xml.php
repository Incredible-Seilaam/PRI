<?php
require 'session_check.php';
require 'db.php';

$zprava = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xml_file'])) {
    $xmlFile = $_FILES['xml_file']['tmp_name'];
    $xml = new DOMDocument();
    $xml->load($xmlFile);

    // validace proti XSD
    if (!$xml->schemaValidate('data/hry.xsd')) {
        $zprava = "❌ Neplatné XML podle hry.xsd!";
    } else {
        $hry = $xml->getElementsByTagName('hra');
        $vlozeno = 0;
        $preskoceno = 0;

        foreach ($hry as $hra) {
            $nazev = $hra->getElementsByTagName('nazev')->item(0)?->nodeValue;
            $zanr = $hra->getElementsByTagName('zanr')->item(0)?->nodeValue;
            $platforma = $hra->getElementsByTagName('platforma')->item(0)?->nodeValue;
            $rok = $hra->getElementsByTagName('rok')->item(0)?->nodeValue;
            $hodnoceni = $hra->getElementsByTagName('hodnoceni')->item(0)?->nodeValue;

            if (!$nazev || !$rok || $hodnoceni === null) {
                continue;
            }

            try {
                // Najít nebo vložit hru do `games`
                $stmt = $pdo->prepare("SELECT id FROM games WHERE nazev = ? AND zanr = ? AND platforma = ? AND rok = ?");
                $stmt->execute([$nazev, $zanr, $platforma, $rok]);
                $game = $stmt->fetch();

                if ($game) {
                    $game_id = $game['id'];
                } else {
                    $stmt = $pdo->prepare("INSERT INTO games (nazev, zanr, platforma, rok) VALUES (?, ?, ?, ?) RETURNING id");
                    $stmt->execute([$nazev, $zanr ?: null, $platforma ?: null, $rok]);
                    $game_id = $stmt->fetchColumn();
                }

                // Pokus o vložení do `user_games`
                $stmt = $pdo->prepare("INSERT INTO user_games (user_id, game_id, hodnoceni) VALUES (?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $game_id, $hodnoceni]);
                $vlozeno++;

            } catch (PDOException $e) {
                if ($e->getCode() === '23505') {
                    $preskoceno++; // již přidaná hra
                } else {
                    $zprava .= "⚠️ Chyba při importu hry: " . $nazev . "<br>";
                }
            }
        }

        $zprava .= "✅ Importováno $vlozeno her. ";
        if ($preskoceno) {
            $zprava .= "⚠️ Přeskočeno $preskoceno již existujících.";
        }
    }
}
?>

<link rel="stylesheet" href="css/style.css">

<div class ="full-center">
    <h1>Import her z XML</h1>

    <?php if ($zprava): ?>
        <div id="zprava" style="background-color:#e0ffe0; padding:10px; border-radius:5px; margin-bottom:1em;">
            <?= $zprava ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Vyber XML soubor:</label>
        <input type="file" name="xml_file" accept=".xml" required>
        <button type="submit">Importovat</button>
    </form>

    <div class="form-links">
        <p><a href="my_library.php">Zpět do mé knihovny</a></p>
    </div>
</div>

<script src="js/script.js" defer></script>
