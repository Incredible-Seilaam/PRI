<?php
require 'session_check.php';
require 'db.php';

$user_id = $_SESSION['user_id'];
$filtr = $_GET['zanr'] ?? '';

// SQL to get user's own games + rating
$query = "
SELECT
    games.id,
    games.nazev,
    games.zanr,
    games.platforma,
    games.rok,
    user_games.hodnoceni
FROM user_games
JOIN games ON games.id = user_games.game_id
WHERE user_games.user_id = ?
";

$params = [$user_id];
if ($filtr) {
    $query .= " AND games.zanr ILIKE ?";
    $params[] = "%$filtr%";
}

$query .= " ORDER BY games.nazev";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$hry = $stmt->fetchAll();

// Genre filter
$zanryStmt = $pdo->prepare("SELECT DISTINCT games.zanr FROM user_games JOIN games ON games.id = user_games.game_id WHERE user_games.user_id = ? AND games.zanr IS NOT NULL AND games.zanr <> '' ORDER BY games.zanr");
$zanryStmt->execute([$user_id]);
$zanry = $zanryStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<link rel="stylesheet" href="css/style.css">

<h1>🎮 Moje herní knihovna</h1>

<nav>
    <b><?= htmlspecialchars($_SESSION['jmeno']) ?></b> |
    <a href="index.php">📚 Veřejná knihovna</a> |
    <a href="add_game.php">➕ Přidat hru</a> |
    <a href="import_xml.php">📥 Import XML</a> |
    <a href="export_xml.php" target="_blank">📤 Export XML</a> |
    <a href="logout.php">🚪 Odhlásit se</a>
</nav>

<form method="get" style="margin-bottom: 1em; display: flex; align-items: center; gap: 10px; flex-wrap: nowrap;">
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
        <th>Moje hodnocení</th>
    </tr>
    <?php foreach ($hry as $hra): ?>
        <tr data-href="detail_game.php?id=<?= $hra['id'] ?>">
            <td><?= htmlspecialchars($hra['nazev']) ?></td>
            <td><?= htmlspecialchars($hra['zanr']) ?></td>
            <td><?= htmlspecialchars($hra['platforma']) ?></td>
            <td><?= $hra['rok'] ?></td>
            <td><?= $hra['hodnoceni'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p>Celkem her: <?= count($hry) ?></p>

<script src="js/script.js" defer></script>
