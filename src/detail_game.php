<?php
require 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Chybí ID hry.");
}

// Load game info
$stmt = $pdo->prepare("
    SELECT 
        games.nazev, games.zanr, games.platforma, games.rok,
        ROUND(AVG(user_games.hodnoceni), 1) AS prumer
    FROM games
    JOIN user_games ON games.id = user_games.game_id
    WHERE games.id = ?
    GROUP BY games.nazev, games.zanr, games.platforma, games.rok
");
$stmt->execute([$id]);
$game = $stmt->fetch();

if (!$game) {
    die("Hra nenalezena.");
}

// Build XML
$doc = new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput = true;

$hra = $doc->createElement('hra');
$hra->appendChild($doc->createElement('nazev', $game['nazev']));
$hra->appendChild($doc->createElement('zanr', $game['zanr']));
$hra->appendChild($doc->createElement('platforma', $game['platforma']));
$hra->appendChild($doc->createElement('rok', $game['rok']));
$hra->appendChild($doc->createElement('prumer', $game['prumer']));

$doc->appendChild($hra);

// Load XSL
$xsl = new DOMDocument();
$xsl->load('data/hra_detail.xsl');

$proc = new XSLTProcessor();
$proc->importStyleSheet($xsl);
echo $proc->transformToXML($doc);
