<?php
require 'session_check.php';
require 'db.php';

$chyba = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazev = trim($_POST['nazev']);
    $zanr = trim($_POST['zanr']);
    $platforma = trim($_POST['platforma']);
    $rok = $_POST['rok'];
    $hodnoceni = $_POST['hodnoceni'];

    if ($nazev && $rok && $hodnoceni !== '') {
        try {
            // 1. Najít nebo vložit do `games`
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

            // 2. Vložit do `user_games`
            $stmt = $pdo->prepare("INSERT INTO user_games (user_id, game_id, hodnoceni) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $game_id, $hodnoceni]);

            header("Location: my_library.php");
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() === '23505') { // unique violation (user already added this game)
                $chyba = "Tuto hru jsi už do své knihovny přidal.";
            } else {
                $chyba = "Chyba při ukládání: " . $e->getMessage();
            }
        }
    } else {
        $chyba = "Vyplň povinná pole: název, rok, hodnocení.";
    }
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="full-center">
    <h2>Přidat novou hru</h2>

    <?php if ($chyba) echo "<p style='color:red;'>$chyba</p>"; ?>

    <form method="post">
        <label for="nazev">Název:</label>
        <input name="nazev" id="nazev" required type="text">

        <label for="zanr">Žánr:</label>
        <input name="zanr" id="zanr" type="text">

        <label for="platforma">Platforma:</label>
        <input name="platforma" id="platforma" type="text">

        <label for="rok">Rok:</label>
        <input name="rok" id="rok" type="number" min="1970" max="2099" required>

        <label for="hodnoceni">Hodnocení (0–10):</label>
        <input name="hodnoceni" id="hodnoceni" type="number" step="0.1" min="0" max="10" required>

        <button type="submit">Uložit</button>
    </form>

    <p><a href="my_library.php">Zpět do mé knihovny</a></p>
</div>

<script src="js/script.js" defer></script>
