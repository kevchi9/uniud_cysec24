<?php

include '../backend/verify_session.php';

$username = $_SESSION['session_username'];
$dsn = "pgsql:host=127.0.0.1;port=5432;dbname=cysec;";
$pdo = new PDO($dsn, 'postgres', 'postgres');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = 'SELECT ef.encrypted_data, ef.encrypted_key, ef.file_type, ef.file_name, ef.uploaded_at
          FROM own_file of JOIN encrypted_files ef ON of.file = ef.id
          WHERE of.username = :username';
$stmt = $pdo->prepare($query);
$stmt->execute(['username' => $username]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>