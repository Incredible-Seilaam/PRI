<?php
session_start();
require 'db.php';

$chyba = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $heslo = $_POST['heslo'];

    $stmt = $pdo->prepare("SELECT * FROM uzivatele WHERE email = ?");
    $stmt->execute([$email]);
    $uzivatel = $stmt->fetch();

    if ($uzivatel && password_verify($heslo, $uzivatel['heslo_hash'])) {
        $_SESSION['user_id'] = $uzivatel['id'];
        $_SESSION['jmeno'] = $uzivatel['jmeno'];
        header("Location: index.php");
        exit;
    } else {
        $chyba = "Neplatné přihlašovací údaje.";
    }
}
?>
<link rel="stylesheet" href="css/style.css">

<div class="full-center">
    <h1>Přihlášení</h1>

    <?php if ($chyba): ?>
        <p id="zprava" style="color:red;"><?= htmlspecialchars($chyba) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="email">E-mail:</label>
        <input id="email" name="email" type="email" required>

        <label for="heslo">Heslo:</label>
        <input id="heslo" name="heslo" type="password" required>

        <button type="submit">Přihlásit se</button>
    </form>

    <div class="form-links">
        <p>Nemáš účet? <a href="register.php">Registruj se.</a></p>
        <p><a href="index.php">Zpět na hlavní stránku</a></p>
    </div>
</div>

<script src="js/script.js" defer></script>
