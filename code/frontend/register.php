<!DOCTYPE html>
<html>
<head>
    <title>Project Domain - Login</title>
    <link rel="stylesheet" href="../style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Reddit+Mono:wght@200..900&family=Tiny5&display=swap');</style>

</head>
<?php
include '../backend/register.php';
?>
<body>
    <h1>Register</h1>
    <form id="send_form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="saveUsername()">
        <input class="textbox" type="text" id="username" name="username" placeholder="Username"><br>
        <input class="textbox" type="password" name="password" placeholder="password"><br>
        <input class="submit" type="submit" id="register" name="register" value="Register">
        <button class="form_button" type="button" id="login_button"> Login </button>
    </form>
</body>
<script type="text/javascript" src="../js/register.js"></script>
<script type="text/javascript">
function saveUsername() {
    var username = document.getElementById('username').value;
    sessionStorage.setItem('currentUsername', username);
}
</script>
</html>