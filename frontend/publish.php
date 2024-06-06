<?php include '../backend/verify_session.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Project Domain - Publish</title>
    <link rel="stylesheet" href="../style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
</head>

<body>
<?php include '../backend/search_user.php'; ?>
<?php
if (isset($_GET['success'])) {
    echo "<div id='notification_box'>";
    if($_GET['success'] == 1) {
        echo "<p id='notification' style='color:green'>File uploaded successfully</p>";
    } else {
        echo "<p id='notification' style='color:red'>Error in file upload</p>";
    }
    echo "</div>";
}
?>
    <div id="header">
        <div id="session_header"></div>
        <h1 id="page_title">Choose a file to encrypt</h1>
        <div id="session_header">
            <?php echo "<p id='session_banner'>"."Logged as: ".$_SESSION['session_username']."</p>";?>
            <button id="logout_button" > Logout </button>
        </div>
    </div>
    <form id="publish_form" method="POST" enctype="multipart/form-data">
        <p>Select a file to encrypt and publish</p>
        <input type="file" id="upload" name="upload">
        <input class="textbox" type="text" id="enc_pswd" placeholder="Encryption password" required>
        <input class="submit" type="submit" id="send" name="send" value="Send">
        <button type="button" id="cancel_button"> Cancel </button>

        <script type="text/javascript">

        </script>
    </form>
</body>

<script type="text/javascript" src="../js/publish.js"></script>
<script type="text/javascript" src="../js/notification.js"></script>
<script type="text/javascript" src="../js/logout.js"></script>

</html>