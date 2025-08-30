<?php
require 'db.php';

header("Content-Type: application/xml; charset=utf-8");

$doc = new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput = true;

$root = $doc->createElement('hry');

$stmt = $pdo->query("SELECT * FROM hry ORDER BY id DESC");
foreach ($stmt as $hra) {
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
