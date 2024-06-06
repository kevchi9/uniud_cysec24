<?php
// Configura la connessione al database PostgreSQL

$dsn = "pgsql:host=127.0.0.1;port=5432;dbname=cysec;user=postgres;password=postgres";

session_start();

// gets data from post request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['encryptedData']) && isset($data['fileName']) && isset($data['publisher'])) {
    $encryptedData = $data['encryptedData'];
    $fileName = $data['fileName'];
    $publisher = $data['publisher'];
    try {
        // db connection
        $pdo = new PDO($dsn);

        // configure PDO to raise exceptions in error cases
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        // update file table
        $sql_command = 'INSERT INTO published_encrypted_files (encrypted_data, publisher, file_name) VALUES (:encryptedData, :publisher, :fileName)';
        $stmt = $pdo->prepare($sql_command);
        $stmt->execute(['encryptedData' => $encryptedData, 'publisher' => $publisher, 'fileName' => $fileName]);
            
        error_log("Returning correctly to select_file.php after upload success.");
        echo json_encode(['message' => 'File uploaded successfully']);
    } catch (PDOException $e) {
        // Gestisci gli errori di connessione al database
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
        error_log("Database error: " . $e->getMessage());
        }
    } else {
    // Risposta di errore
    http_response_code(400);
    error_log("Returning error code 400");
    // echo json_encode(['message' => 'Invalid input']);
}