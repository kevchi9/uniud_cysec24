<?php include '../backend/verify_session.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Project Domain - Send</title>
    <link rel="stylesheet" href="../style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Reddit+Mono:wght@200..900&family=Tiny5&display=swap');</style>
</head>

<body>
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
    <form id="send_form" method="POST" enctype="multipart/form-data">
        <p>Select a file to encrypt and send to</p>
        <p><?php echo $_SESSION['username']; ?> </p>
        <input type="file" id="upload" name="upload">
        <input class="submit" type="submit" id="send" name="send" value="Send">
        <button type="button" id="cancel_button"> Cancel </button>
        <?php 
        $_SESSION['serialized_key'] = serialize($_SESSION['recipient_pkey']);
        $session_value=(isset($_SESSION['serialized_key']))?$_SESSION['serialized_key']:''; 
        preg_match('/\{[^{}]*\}/', $session_value , $matches);
        ?>

        <script type="text/javascript">
            
            var cleaned = '<?php echo $matches[0];?>'; 
            var keyObj = JSON.parse(cleaned);
            var key;
            
            window.crypto.subtle.importKey(
                'jwk', keyObj,
                { name: 'RSA-OAEP', hash: { name: 'SHA-256' } }, 
                true, ['encrypt']
            )
            .then(function(cryptoKey) {
                key = cryptoKey;
            })
            .catch(function(error) {
                console.error("Error while creating AES key:", error);
            });
        </script>
    </form>
</body>

<script type="text/javascript" src="../js/send.js"></script>
<script type="text/javascript" src="../js/notification.js"></script>
<script type="text/javascript" src="../js/logout.js"></script>

</html>