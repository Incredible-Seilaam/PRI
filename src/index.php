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

<h1>Game Library</h1>

<?php if (isset($_SESSION['user_id'])): ?>
    <p>Vítej, <?= htmlspecialchars($_SESSION['jmeno']) ?> | <a href="add_game.php">Přidat hru</a> | <a href="logout.php">Odhlásit</a></p>
<?php else: ?>
    <p><a href="login.php">Přihlásit</a> | <a href="register.php">Registrovat</a></p>
<?php endif; ?>

<form method="get">
    Filtrovat podle žánru: <input name="zanr" value="<?= htmlspecialchars($filtr) ?>">
    <button type="submit">Filtrovat</button>
</form>

<table border="1" cellpadding="4">
    <tr>
        <th>Název</th>
        <th>Žánr</th>
        <th>Platforma</th>
        <th>Rok</th>
        <th>Hodnocení</th>
        <th>Přidal</th>
        <th>Detail</th>
    </tr>
    <?php foreach ($hry as $hra): ?>
        <tr>
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
