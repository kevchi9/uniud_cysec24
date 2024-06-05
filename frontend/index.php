<?php include '../backend/verify_session.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Project Domain</title>
    <link rel="stylesheet" href="../style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
</head>

<body>
<div id="header">
    <h1 id="page_title">PoggerShare</h1>
    <button id="logout_button" > Logout </button>
</div>

    <button id="inbox_button"> Inbox </button>
    <button id="send_button"> Send </button>
    <button> Publish </button>

    <h3>Published:</h3>
    <div class="content_table" id="hierarchy">
        <?php include '../backend/fetch_files.php'; ?>
    </div>
</body>

<script type="text/javascript" src="../js/index.js"></script>
<script type="text/javascript" src="../js/logout.js"></script>

</html>