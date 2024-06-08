<?php
session_start();
$authenticated = false;

if(isset($_POST['login']) && $_POST['login']=="Login"){
    $username = $_POST['username'];
    if (!str_contains($username, "'")) {
        try {
            $dsn = "pgsql:host=127.0.0.1;port=5432;dbname=cysec;user=postgres;password=postgres";
            $pdo = new PDO($dsn);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $username = $_POST['username'];
            $pwd = $_POST['password'];
            
            $stmt = $pdo->prepare('SELECT verify(:username, :password)');
            $stmt->execute(['username' => $username, 'password' => $pwd]);
            
            $result = $stmt->fetch();
            // $result['verify'] can be only 0 or 1
            if($result['verify']){
                $authenticated = $result['verify']==1;
                
                if ($authenticated) {
                    $_SESSION["session_username"] = $username;
                }
            }

            if(!$authenticated) {
                echo "<p style='color:red'>Wrong credentials</p>";
            } else {
                header('location: index.php');
            }
        } catch (PDOException $e) {
            // handles db connection errors
            http_response_code(500);
            echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
            error_log("Database error: " . $e->getMessage());
        }
    } else {
        echo "<p style='color:red'>Wrong credentials</p>";
    }
}
?>