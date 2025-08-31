<?php
session_start();
require 'db.php';

$filtr = $_GET['zanr'] ?? '';
$query = "SELECT hry.*, uzivatele.jmeno FROM hry JOIN uzivatele ON uzivatele.id = hry.uzivatel_id";
$params = [];

if ($filtr) {
    $query .= " WHERE zanr ILIKE ?";
    $params[] = "%$filtr%";
}

$query .= " ORDER BY rok DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$hry = $stmt->fetchAll();
$zanryStmt = $pdo->query("SELECT DISTINCT zanr FROM hry WHERE zanr IS NOT NULL AND zanr <> '' ORDER BY zanr");
$zanry = $zanryStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<link rel="stylesheet" href="css/style.css">

<h1>🎮 Game Library</h1>

<nav>
<?php if (isset($_SESSION['user_id'])): ?>
    <b>Vítej, <?= htmlspecialchars($_SESSION['jmeno']) ?></b> |
    <a href="add_game.php">➕ Přidat hru</a> |
    <a href="import_xml.php">📥 Import XML</a> |
    <a href="export_xml.php" target="_blank">📤 Export XML</a> |
    <a href="logout.php">🚪 Odhlásit se</a>
<?php else: ?>
    <a href="index.php">🏠 Domů</a> |
    <a href="login.php">🔑 Přihlásit se</a> |
    <a href="register.php">🆕 Registrovat</a>
<?php endif; ?>
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
    <tr data-href="detail_game.php?id=<?= $hra['id'] ?>">
        <th>Název</th>
        <th>Žánr</th>
        <th>Platforma</th>
        <th>Rok</th>
        <th>Hodnocení</th>
        <th>Přidal</th>
        <th>Detail</th>
    </tr>
    <?php foreach ($hry as $hra): ?>
        <tr data-href="detail_game.php?id=<?= $hra['id'] ?>">
            <td><?= htmlspecialchars($hra['nazev']) ?></td>
            <td><?= htmlspecialchars($hra['zanr']) ?></td>
            <td><?= htmlspecialchars($hra['platforma']) ?></td>
            <td><?= $hra['rok'] ?></td>
            <td><?= $hra['hodnoceni'] ?></td>
            <td><?= htmlspecialchars($hra['jmeno']) ?></td>
            <td><a href="detail_game.php?id=<?= $hra['id'] ?>">Zobrazit</a></td>
        </tr>
    <?php endforeach; ?>
</table>
<p>Celkem her: <?= count($hry) ?></p>
<script src="js/script.js" defer></script>