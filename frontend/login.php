<!DOCTYPE html>
<html>

<head>
    <title>Project Domain - Login</title>
    <link rel="stylesheet" href="../style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
</head>

<?php
include '../backend/login_check.php';
include '../backend/register.php';
?>

<body>
    <h1>Login</h1>
    <form id="send_form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="saveUsername()">
        <input class="textbox" type="text" id="username" name="username" placeholder="Username"><br>
        <input class="textbox" type="password" name="password" placeholder="password"><br>
        <input class="submit" type="submit" id="login" name="login" value="Login">
        <button class="form_button" type="button" id="register_button"> Register </button>
    </form>
</body>

<script type="text/javascript" src="../js/login.js"></script>
<script type="text/javascript">
function saveUsername() {
    var username = document.getElementById('username').value;
    sessionStorage.setItem('currentUsername', username);
}
</script>
</html>
