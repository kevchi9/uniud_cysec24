<?php

$registered = false;
if(isset($_POST['register']) && $_POST['register']=="Register"){
    
    $username = $_POST['username'];
    $pwd = $_POST['password'];

    $conn = pg_connect("host=127.0.0.1 port=5432 dbname=cysec user=postgres password=postgres");


    // checks if user already exists
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
        $pkey = $_POST['json_pub_key'];
        // echo "<p style='color:red'>".$pkey->n."</p>";

        $query1 = "insert into users(username, pswd) values($1, crypt($2, gen_salt('md5')));";
        $query2 = "insert into pkey(username, pkey) values($1, $2);";

        pg_query($conn, 'BEGIN;');
        if(pg_query_params($conn, $query1, array($username, $pwd))){
            if(pg_query_params($conn, $query2, array($username, $pkey))){
                pg_query($conn, "COMMIT;");
                $_SESSION['registered'] = true;
                // sends data to client about the successful registration
                $_SESSION['username'] = $username;
                header('location: login.php?registered=true&username=' . $username);
            } else {
                pg_query($conn, "ROLLBACK;");
                echo "<p style='color:red'>Operation failed.</p>";
            }
        } else {
            pg_query($conn, "ROLLBACK;");
            echo "<p style='color:red'>Operation failed.</p>";
        }   

    }
}
?>