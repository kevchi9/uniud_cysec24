<?php
$publisher = $_POST["publisher"];
$dsn = "pgsql:host=127.0.0.1;port=5432;dbname=cysec;user=postgres;password=postgres";

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt_own_file = $pdo->prepare('SELECT encrypted_data, file_name, uploaded_at FROM published_encrypted_files  WHERE publisher = :publisher');
    $stmt_own_file->execute(['publisher' => $publisher]);
    
    $own_files = $stmt_own_file->fetchAll(PDO::FETCH_ASSOC); 

    header('Content-Type: application/json');
    echo json_encode($own_files);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    error_log("Database error: " . $e->getMessage());
}