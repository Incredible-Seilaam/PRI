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

<form method="get">
    <label>Filtrovat podle žánru:</label>
    <input name="zanr" value="<?= htmlspecialchars($filtr) ?>">
    <button type="submit">Filtrovat</button>
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