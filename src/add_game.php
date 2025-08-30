<?php
require 'session_check.php';
require 'db.php';

$chyba = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazev = $_POST['nazev'];
    $zanr = $_POST['zanr'];
    $platforma = $_POST['platforma'];
    $rok = $_POST['rok'];
    $hodnoceni = $_POST['hodnoceni'];

    if ($nazev && $rok && $hodnoceni) {
        $stmt = $pdo->prepare("INSERT INTO hry (nazev, zanr, platforma, rok, hodnoceni, uzivatel_id) VALUES (?, ?, ?, ?, ?, ?)");
        try {
            $stmt->execute([
                $nazev,
                $zanr ?: null,
                $platforma ?: null,
                $rok,
                $hodnoceni,
                $_SESSION['user_id']
            ]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $chyba = "Chyba při ukládání: " . $e->getMessage();
        }
    } else {
        $chyba = "Vyplň povinná pole: název, rok, hodnocení.";
    }
}
?>

<h2>Přidat novou hru</h2>
<?php if ($chyba) echo "<p style='color:red;'>$chyba</p>"; ?>
<form method="post">
    Název: <input name="nazev" required><br>
    Žánr: <input name="zanr"><br>
    Platforma: <input name="platforma"><br>
    Rok: <input name="rok" type="number" min="1970" max="2099" required><br>
    Hodnocení (0–10): <input name="hodnoceni" type="number" step="0.1" min="0" max="10" required><br>
    <button type="submit">Uložit</button>
</form>
<p><a href="index.php">Zpět na přehled</a></p>
