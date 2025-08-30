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

<h2>Registrace</h2>
<?php if ($chyba) echo "<p style='color:red;'>$chyba</p>"; ?>
<form method="post">
    Jméno: <input name="jmeno" required><br>
    E-mail: <input type="email" name="email" required><br>
    Heslo: <input type="password" name="heslo" required><br>
    <button type="submit">Registrovat</button>
</form>
<p>Už máš účet? <a href="login.php">Přihlas se</a>.</p>
