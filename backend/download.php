<?php
// Recupera i parametri dall'URL
$serial = $_GET['serial'];
$encrypted_data = $_GET['encrypted_data'];
$encrypted_key = $_GET['encrypted_key'];

// Effettua il download del file cifrato
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="encrypted_file_' . $serial . '.txt"');
echo base64_decode($encrypted_data);
?>