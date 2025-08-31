<?php
require 'session_check.php';
require 'db.php';

header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename="moje_hry.xml"');

// načti hry uživatele
$stmt = $pdo->prepare("
    SELECT g.nazev, g.zanr, g.platforma, g.rok, ug.hodnoceni
    FROM user_games ug
    JOIN games g ON g.id = ug.game_id
    WHERE ug.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$hry = $stmt->fetchAll();

$doc = new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput = true;

$root = $doc->createElement('hry');

foreach ($hry as $hra) {
    $hraNode = $doc->createElement('hra');

    $hraNode->appendChild($doc->createElement('nazev', $hra['nazev']));
    $hraNode->appendChild($doc->createElement('zanr', $hra['zanr']));
    $hraNode->appendChild($doc->createElement('platforma', $hra['platforma']));
    $hraNode->appendChild($doc->createElement('rok', $hra['rok']));
    $hraNode->appendChild($doc->createElement('hodnoceni', $hra['hodnoceni']));

    $root->appendChild($hraNode);
}

$doc->appendChild($root);
echo $doc->saveXML();
