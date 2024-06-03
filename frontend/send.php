<!DOCTYPE html>
<html>

<head>
    <title>Project Domain - Send</title>
    <link rel="stylesheet" href="../style.css">
    <!-- DELETE THIS IF EMPTY -->
    <link rel="stylesheet" href="../send.css">
</head>

<?php
include '../backend/verify_and_send.php';
?>

<body>
    <div id="send_head">
        <h1>Send an encrypted file</h1>
    </div>
    <form id="send_form" method="POST" enctype="multipart/form-data">
        <input class="textbox" type="text" id="recipient" name="recipient" placeholder="Recipient username"><br>
        <input type="file" id="upload" name="upload"><br>
        <input class="submit" type="submit" id="send" name="send" value="Send">
        <button type="button" id="cancel_button"> Cancel </button>
    </form>
</body>

<script type="text/javascript" src="../js/send.js"></script>

</html>