<?php
$dsn = "pgsql:host=127.0.0.1;port=5432;dbname=cysec;user=postgres;password=postgres";

$username = $_SESSION["session_username"];
// gets data from post request

try {
    // db connection
    $pdo = new PDO($dsn);

    // configure PDO to raise exceptions in error cases
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // query file table
        $stmt_own_file = $pdo->prepare('SELECT own_file.file, encrypted_files.encrypted_data, encrypted_files.encrypted_key FROM own_file JOIN encrypted_files ON own_file.file = encrypted_files.id WHERE own_file.username = :username');
        $stmt_own_file->execute(['username' => $username]);
        
        $own_files = $stmt_own_file->fetchAll(PDO::FETCH_ASSOC); 

        if ($own_files) {
            foreach ($own_files as $own_file) {
                $serial = $own_file['file'];
                $encrypted_data = $own_file['encrypted_data'];
                $encrypted_key = $own_file['encrypted_key'];
                echo "<a href='https://project_domain.local/backend/download.php?serial=$serial&encrypted_data=$encrypted_data&encrypted_key=$encrypted_key'>File " . $serial . "</a><br>";
            }
        }
        // update ownership table
        $serial = $pdo->lastInsertId();
        $username = $_SESSION['username'];
        $stmt = $pdo->prepare('INSERT INTO own_file (username, file) VALUES (:username, :serial)');
        $stmt->execute(['username' => $username, 'serial' => $serial]);
        
        // commit transaction
        $pdo->commit();

        error_log("Returning correctly to select_file.php after upload success.");
        echo json_encode(['message' => 'File uploaded successfully']);
        // header('Location: ../frontend/select_file.php?success=1');
    } catch (PDOException $e) {
    // handles db connection errors
    http_response_code(500);
    echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    error_log("Database error: " . $e->getMessage());
};