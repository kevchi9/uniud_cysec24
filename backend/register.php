<?php

$registered = false;
if(isset($_POST['register']) && $_POST['register']=="Register"){
    
    $username = $_POST['username'];
    $pwd = $_POST['password'];

    $conn = pg_connect("host=127.0.0.1 port=5432 dbname=cysec user=postgres password=postgres");

    if($conn) {
        $query = "select * from does_exist($1)";
        $res = pg_query_params($conn, $query, array($username));
        $result = pg_fetch_object($res);

        if($result){
            $registered=$result->does_exist!=0;
        }
    }

    if($registered) {
        echo "<p style='color:red'>Username already picked.</p>";
    } else {
        $query = "insert into users(username, pswd) values($1, crypt($2, gen_salt('md5')));";
        pg_query_params($conn, $query, array($username, $pwd));
        echo "<p style='color:Green'>User successfully registered.</p>";
        echo "<p>Use the submitted credentials to log in.</p>";
    }
}
?>