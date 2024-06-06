<?php include '../backend/verify_session.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Project Domain - Send</title>
    <link rel="stylesheet" href="../style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
</head> 
<?php include '../backend/search_user.php'; ?>
<body>
    <div id="header">
        <div id="session_header"></div>
        <h1 id="page_title">Search recipient username</h1>
        <div id="session_header">
            <?php echo "<p id='session_banner'>"."Logged as: ".$_SESSION['session_username']."</p>";?>
            <button id="logout_button" > Logout </button>
        </div>
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