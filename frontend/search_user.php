<?php session_start();
    if(!isset($_SESSION['session_username'])) {
        header('Location: ./login.php', true);
    }
?>
<!DOCTYPE html>
<html>

<head>
    <title>Project Domain - Send</title>
    <link rel="stylesheet" href="../style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
</head>

<?php 

if(isset($_POST['recipient'])) {
    $username = $_POST['recipient'];
    $conn = pg_connect("host=127.0.0.1 port=5432 dbname=cysec user=postgres password=postgres");

    if($conn) {
        $query = "select pkey from pkey where username = $1";
        $res = pg_query_params($conn, $query, array($username));
        $result = pg_fetch_object($res);
        if($result){
            $_SESSION['recipient_pkey'] = $result;
            $_SESSION['username'] = $username;
            header('location: select_file.php');
        } else {
            echo "<div id='notification_box'>";
            echo "<p id='notification' style='color:red'>User does not exist.</p>";
            echo "</div>";
        }
    } 
}
?>

<body>
    <div id="header">
        <h1 id="page_title">Search recipient username</h1>
        <button id="logout_button"> Logout </button>
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

<script type="text/javascript" src="../js/notification.js"></script>
<script type="text/javascript" src="../js/logout.js"></script>

</html>