<?php
session_start();
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
            
            if ($authenticated) {
                $_SESSION["session_username"] = $username;
            }
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