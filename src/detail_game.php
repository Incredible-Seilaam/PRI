<?php
require 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Chybí ID.");
}

$stmt = $pdo->prepare("SELECT * FROM hry WHERE id = ?");
$stmt->execute([$id]);
$hra = $stmt->fetch();

if (!$hra) {
    die("Hra nenalezena.");
}

// vytvoření XML
$doc = new DOMDocument('1.0', 'UTF-8');
$hraNode = $doc->createElement('hra');

$hraNode->appendChild($doc->createElement('nazev', $hra['nazev']));
$hraNode->appendChild($doc->createElement('zanr', $hra['zanr']));
$hraNode->appendChild($doc->createElement('platforma', $hra['platforma']));
$hraNode->appendChild($doc->createElement('rok', $hra['rok']));
$hraNode->appendChild($doc->createElement('hodnoceni', $hra['hodnoceni']));

$doc->appendChild($hraNode);

// načtení a použití XSL
$xsl = new DOMDocument;
$xsl->load('data/hra_detail.xsl');

$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);
echo $proc->transformToXML($doc);
