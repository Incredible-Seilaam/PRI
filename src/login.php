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
<h2>Přihlášení</h2>
<?php if ($chyba) echo "<p style='color:red;'>$chyba</p>"; ?>
<form method="post">
    E-mail: <input name="email" type="email" required><br>
    Heslo: <input name="heslo" type="password" required><br>
    <button type="submit">Přihlásit se</button>
</form>
<p>Nemáš účet? <a href="register.php">Registruj se</a>.</p>
<p><a href="index.php">Zpět na hlavní stránku</a></p>
<script src="js/script.js" defer></script>