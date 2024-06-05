// cool transition to make the notification fade-out
document.addEventListener("DOMContentLoaded", function () {
    var notificationBox = document.getElementById('notification_box');
    if (notificationBox) {
        setTimeout(function () {
            notificationBox.classList.add('fade-out');
        }, 1000);
    }
});