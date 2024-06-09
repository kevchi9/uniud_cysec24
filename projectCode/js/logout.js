$(document).ready(function () {
    // Gestisci il clic sul pulsante di logout
    $('#logout_button').click(function () {
        // Effettua una richiesta AJAX al server per eseguire il logout
        $.ajax({
            url: '../backend/logout.php', // Sostituisci 'logout.php' con il percorso del tuo script PHP di logout
            type: 'POST',
            success: function (response) {
                // Reindirizza l'utente alla pagina di login dopo il logout
                window.location.href = '../frontend/login.php';
            },
            error: function (xhr, status, error) {
                // Gestisci eventuali errori qui
                console.error(error);
            }
        });
    });
});