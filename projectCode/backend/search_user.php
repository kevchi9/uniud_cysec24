<?php 

if(isset($_POST['recipient'])) {
    $username = $_POST['recipient'];
    if (!str_contains($username, "'")) {
        try {
            // db connection and configuration to raise exceptions in error cases
            $dsn = "pgsql:host=127.0.0.1;port=5432;dbname=cysec;user=postgres;password=postgres";
            $pdo = new PDO($dsn);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // query user table
            $stmt = $pdo->prepare('SELECT pkey FROM pkey  WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $result = $stmt->fetch();
            
            if($result){
                $_SESSION['recipient_pkey'] = $result;
                $_SESSION['username'] = $username;
                header('location: select_file.php');
                
            } else {
                echo "<div id='notification_box'>";
                echo "<p id='notification' style='color:red'>User does not exist.</p>";
                echo "</div>";
            }
        } catch (PDOException $e) {
            // handles db connection errors
            http_response_code(500);
            echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
            error_log("Database error: " . $e->getMessage());
        };
    } else {
        echo "<div id='notification_box'>";
        echo "<p id='notification' style='color:red'>User does not exist.</p>";
        echo "</div>";
    }
}
?>