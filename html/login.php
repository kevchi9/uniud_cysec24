<!DOCTYPE html>
<html>

<head>
    <title>Project Domain - Login</title>
    <link rel="stylesheet" href="../style.css">
</head>

<?php
include '../backend/login_check.php';
include '../backend/register.php';
?>

<body>
    <h1>Login</h1>
    <form id="send_form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <input class="textbox" type="text" id="username" name="username" placeholder="Username"><br>
        <input class="textbox" type="password" name="password" placeholder="password"><br>
        <input class="submit" type="submit" id="login" name="login" value="Login">
        <input class="submit" type="submit" id="register" name="register" value="Register">
    </form>

</body>

</html>