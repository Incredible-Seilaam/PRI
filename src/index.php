<?php
session_start();
require 'db.php';

$filtr = $_GET['zanr'] ?? '';

$query = "
SELECT 
    games.id,
    games.nazev,
    games.zanr,
    games.platforma,
    games.rok,
    ROUND(AVG(user_games.hodnoceni), 1) AS prumer
FROM games
JOIN user_games ON games.id = user_games.game_id
";

$params = [];
if ($filtr) {
    $query .= " WHERE games.zanr ILIKE ?";
    $params[] = "%$filtr%";
}

$query .= " GROUP BY games.id ORDER BY games.nazev";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$hry = $stmt->fetchAll();

// Get genre list for filter dropdown
$zanryStmt = $pdo->query("SELECT DISTINCT zanr FROM games WHERE zanr IS NOT NULL AND zanr <> '' ORDER BY zanr");
$zanry = $zanryStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<link rel="stylesheet" href="css/style.css">

<h1>📚 Veřejná knihovna her</h1>

<nav>
    <?php if (isset($_SESSION['user_id'])): ?>
        <b><?= htmlspecialchars($_SESSION['jmeno']) ?></b> |
        <a href="my_library.php">🎮 Moje knihovna</a> |
        <a href="add_game.php">➕ Přidat hru</a> |
        <a href="import_xml.php">📥 Import XML</a> |
        <a href="export_xml.php" target="_blank">📤 Export XML</a> |
        <a href="logout.php">🚪 Odhlásit se</a>
    <?php else: ?>
        <a href="login.php">🔑 Přihlásit se</a> |
        <a href="register.php">🆕 Registrovat</a>
    <?php endif; ?>
</nav>

<form method="get" class="filter-form">
    <label for="zanr" style="margin: 0; white-space: nowrap;">
        Filtrovat podle žánru:
    </label>
    <select name="zanr" id="zanr" onchange="this.form.submit()">
        <option value="">— Zobrazit vše —</option>
        <?php foreach ($zanry as $z): ?>
            <option value="<?= htmlspecialchars($z) ?>" <?= $filtr === $z ? 'selected' : '' ?>>
                <?= htmlspecialchars($z) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<table>
    <tr>
        <th>Název</th>
        <th>Žánr</th>
        <th>Platforma</th>
        <th>Rok</th>
        <th>Průměrné hodnocení</th>
    </tr>
    <?php foreach ($hry as $hra): ?>
        <tr data-href="detail_game.php?id=<?= $hra['id'] ?>">
            <td><?= htmlspecialchars($hra['nazev']) ?></td>
            <td><?= htmlspecialchars($hra['zanr']) ?></td>
            <td><?= htmlspecialchars($hra['platforma']) ?></td>
            <td><?= $hra['rok'] ?></td>
            <td><?= $hra['prumer'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p>Celkem her: <?= count($hry) ?></p>

<script src="js/script.js" defer></script>
