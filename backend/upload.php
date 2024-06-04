<?php
// Configura la connessione al database PostgreSQL

$dsn = "pgsql:host=127.0.0.1;port=5432;dbname=cysec;user=postgres;password=postgres";

session_start();

if ($_SESSION['upload_processed']) {
    http_response_code(400);
    echo json_encode(['message' => 'Request already processed']);
    header('Location: ../frontend/select_file.php?success=1');
    exit;
}

// Imposta un flag per indicare che la richiesta è stata gestita
$_SESSION['upload_processed'] = true;

try {
    // Crea una connessione al database
    $pdo = new PDO($dsn);

    // Configura PDO per lanciare eccezioni in caso di errore
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Leggi il corpo della richiesta
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

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

    if (isset($data['encryptedData']) && isset($data['encryptedKey'])) {
        $encryptedData = $data['encryptedData'];
        $encryptedKey = $data['encryptedKey'];

        // Prepara la query SQL
        $sql_command = 'INSERT INTO encrypted_files (encrypted_data, encrypted_key) VALUES (:encryptedData, :encryptedKey)';
        $stmt = $pdo->prepare($sql_command);
        $stmt->execute(['encryptedData' => $encryptedData, 'encryptedKey' => $encryptedKey]);

        // Risposta di successo
        echo json_encode(['message' => 'File uploaded successfully']);
        header('Location: ../frontend/select_file.php?success=1');
    } else {
        // Risposta di errore
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input']);
    }
} catch (PDOException $e) {
    // Gestisci gli errori di connessione al database
    http_response_code(500);
    echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}