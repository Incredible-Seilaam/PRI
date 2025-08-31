<?php
session_start();
require 'db.php';

$chyba = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jmeno = $_POST['jmeno'];
    $email = $_POST['email'];
    $heslo = $_POST['heslo'];

    if ($jmeno && $email && $heslo) {
        $hash = password_hash($heslo, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO uzivatele (jmeno, email, heslo_hash) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$jmeno, $email, $hash]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $chyba = "Registrace selhala: " . $e->getMessage();
        }
    } else {
        $chyba = "Vyplň všechna pole.";
    }
}
?>
<link rel="stylesheet" href="css/style.css">

<div class="full-center">
    <h1>Registrace</h1>

    <?php if ($chyba): ?>
        <p id="zprava" style="color:red;"><?= htmlspecialchars($chyba) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="jmeno">Jméno:</label>
        <input id="jmeno" type="text" name="jmeno" required>

        <label for="email">E-mail:</label>
        <input id="email" type="email" name="email" required>

        <label for="heslo">Heslo:</label>
        <input id="heslo" type="password" name="heslo" required>

        <button type="submit">Registrovat</button>
    </form>

    <div class="form-links">
        <p>Už máš účet? <a href="login.php">Přihlaš se</a>.</p>
        <p><a href="index.php">Zpět na hlavní stránku</a></p>
    </div>
</div>

<script src="js/script.js" defer></script>