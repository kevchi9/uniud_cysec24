<?php include '../backend/verify_session.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Tiny5&display=swap');</style>
</head>

<body>
<div id="header">
    <div id="session_header"></div>
    <h1 id="homepage_title">SecureShare</h1>
    <div id="session_header">
        <?php echo "<p id='session_banner'>"."Logged as: ".$_SESSION['session_username']."</p>";?>
        <button id="logout_button" > Logout </button>
    </div>
</div>

    <button id="inbox_button"> Inbox </button>
    <button id="send_button"> Send </button>
    <button id="publish_button"> Publish </button>

    <h3>Search public files</h3>
    <p>Insert publisher username to start</p>
    <form id="search_form" method="POST" enctype="multipart/form-data" action="../backend/load_published_files.php">
        <input class="textbox" type="text" id="publisher" name="publisher" placeholder="Publisher username">
        <button class="search_button" type="submit" id="search_user" name="search_user"> Search </button><br>
    </form>
    <br><br>
    <div id="published_results" class="content_table"></div>
</body>
<script type="text/javascript" src="../js/index.js"></script>
<script type="text/javascript" src="../js/logout.js"></script>
</html>