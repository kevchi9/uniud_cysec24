<!DOCTYPE html>
<html>

<head>
    <title>Project Domain - Send</title>
    <link rel="stylesheet" href="../style.css">
    <!-- DELETE THIS IF EMPTY -->
    <link rel="stylesheet" href="../send.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
</head>

<body>
    <div id="send_head">
    <?php
    session_start();
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        unset($_SESSION['upload_processed']);
        echo "<p style='color:green'>File caricato con successo</p>";
    }
    ?>

        <h1>Send an encrypted file</h1>
    </div>
    <form id="send_form" method="POST" enctype="multipart/form-data" action="../backend/upload.php">
        <p><?php echo "Select a file to send to "; ?></p>
        <p><?php echo $_SESSION['username']; ?> </p>
         
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
                console.error("Errore durante la creazione della CryptoKey:", error);
            });

        </script>

        <input type="file" id="upload" name="upload">
        <input class="submit" type="submit" id="send" name="send" value="Send">
        <button type="button" id="cancel_button"> Cancel </button>
    </form>
</body>

<script type="text/javascript" src="../js/send.js"></script>

</html>