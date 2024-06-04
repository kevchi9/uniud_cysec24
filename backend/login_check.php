<?php

$authenticated = false;
if(isset($_POST['login']) && $_POST['login']=="Login"){
    
    $username = $_POST['username'];
    $pwd = $_POST['password'];

    $conn = pg_connect("host=127.0.0.1 port=5432 dbname=cysec user=postgres password=postgres");
    
    if($conn) {
        $query = "select * from verify($1, $2)";
        $res = pg_query_params($conn, $query, array($username, $pwd));
        $result = pg_fetch_object($res);
        if($result){
            $authenticated=$result->verify==1;
            // TODO: autenticazione
            ini_set("session.gc_maxlifetime", 3600); // 1 hour
            ini_set("session.cookie_lifetime", 0); // expire on browser close
            $_SESSION["username"] = "$username";
            setcookie("session_data", base64_encode(json_encode($_SESSION)), time() + 3600, "/");
        }
    }

    if(!$authenticated) {
        // header('location: login.php');
        echo "<p style='color:red'>Wrong credentials</p>";
    } else {
        header('location: index.php');
    }
}
?>