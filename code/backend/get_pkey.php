<?php 

$username = $_POST['username'];
$conn = pg_connect("host=127.0.0.1 port=5432 dbname=cysec user=postgres password=postgres");

if($conn) {
    $query = "select * from users where username = $1";
    $res = pg_query_params($conn, $query, array($username));
    $result = pg_fetch_object($res);
    if($result){
        echo $result;
    }
} else {
    echo "Error...";
}
?>