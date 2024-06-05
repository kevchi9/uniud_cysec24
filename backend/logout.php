<?php
session_start();

// Cancella la variabile di sessione
unset($_SESSION["session_username"]);

// Distruggi completamente la sessione
session_destroy();

// Rispondi con un messaggio di successo o qualsiasi altra cosa necessaria
header("location: ../frontend/login.php");
?>