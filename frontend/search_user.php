<!DOCTYPE html>
<html>

<head>
    <title>Project Domain - Send</title>
    <link rel="stylesheet" href="../style.css">
    <!-- DELETE THIS IF EMPTY -->
    <link rel="stylesheet" href="../send.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
</head>

<?php 

if(isset($_POST['recipient'])) {
    $username = $_POST['recipient'];
    echo $username; // used for retrieving username by ajax request
    $conn = pg_connect("host=127.0.0.1 port=5432 dbname=cysec user=postgres password=postgres");

    if($conn) {
        $query = "select pkey from pkey where username = $1";
        $res = pg_query_params($conn, $query, array($username));
        $result = pg_fetch_object($res);
        if($result){
            session_start();
            $_SESSION['recipient_pkey'] = $result;
            $_SESSION['username'] = $username;
            header('location: select_file.php');
        } else {
            echo "<h2 style='color:red'>Submitted user does not exist.</h2>";
        }
    } 
}
?>

<body>
    <div id="send_head">
        <h1>Search recipient username</h1>
        <br><br>
    </div>
    <form id="send_form" method="POST" enctype="multipart/form-data">
        <input class="textbox" type="text" id="recipient" name="recipient" placeholder="Recipient username">
        <button class="search_button" type="submit" id="search_user" name="search_user"> Search </button><br>
        <button type="button" id="cancel_button"> Cancel </button>
    </form>
</body>

<script>
document.getElementById("cancel_button").onclick = function () {
    location.href = "index.php"
}
</script>

</html>