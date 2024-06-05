<?php
// Configura la connessione al database PostgreSQL

$dsn = "pgsql:host=127.0.0.1;port=5432;dbname=cysec;user=postgres;password=postgres";

session_start();

// gets data from post request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['encryptedData']) && isset($data['encryptedKey'])) {
    $encryptedData = $data['encryptedData'];
    $encryptedKey = $data['encryptedKey'];
    $fileType = $data['fileType'];
    try {
        // db connection
        $pdo = new PDO($dsn);

        // configure PDO to raise exceptions in error cases
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        error_log("Received data: " . print_r($data, true));

        if (is_array($data)) {
            error_log("Data is an array");
            if (array_key_exists('encryptedData', $data)) {
                error_log("encryptedData exists");
            } else {
                error_log("encryptedData does not exist");
            }
            if (array_key_exists('encryptedKey', $data)) {
                error_log("encryptedKey exists");
            } else {
                error_log("encryptedKey does not exist");
            }
        } else {
            error_log("Data is not an array");
        }
            // begin transaction
            $pdo->beginTransaction();
            
            // update file table
            $sql_command = 'INSERT INTO encrypted_files (encrypted_data, encrypted_key, file_type) VALUES (:encryptedData, :encryptedKey, :fileType)';
            $stmt = $pdo->prepare($sql_command);
            $stmt->execute(['encryptedData' => $encryptedData, 'encryptedKey' => $encryptedKey, 'fileType' => $fileType]);
            
            // update ownership table
            $serial = $pdo->lastInsertId();
            $username = $_SESSION['username'];
            $stmt = $pdo->prepare('INSERT INTO own_file (username, file) VALUES (:username, :serial)');
            $stmt->execute(['username' => $username, 'serial' => $serial]);
            
            // commit transaction
            $pdo->commit();

            // Risposta di successo
            error_log("Returning correctly to select_file.php after upload success.");
            echo json_encode(['message' => 'File uploaded successfully']);
            // header('Location: ../frontend/select_file.php?success=1');
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