<?php
$exists = false;
if(isset($_POST['send']) && $_POST['send']=="Send"){
    
    $username = $_POST['recipient'];
    $file = $_POST['upload'];

    $conn = pg_connect("host=127.0.0.1 port=5432 dbname=cysec user=postgres password=postgres");
    
    if($conn) {
        $query = "select * from does_exist($1)";
        $res = pg_query_params($conn, $query, array($username));
        $result = pg_fetch_object($res);

        if($result){
            $exists=$result->does_exist==1;
        }
    }

    if(!$exists) {
        echo "<p style='color:red'>User does not exist.</p>";

    } else {
        echo "<p style='color:green'>File sent.</p>";
    }
}
?>