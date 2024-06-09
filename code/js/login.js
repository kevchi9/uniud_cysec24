document.getElementById("register_button").onclick = function () {
    location.href = "register.php";
};

const urlParams = new URLSearchParams(window.location.search);
const isRegistered = urlParams.get('registered');
const username = urlParams.get('username');

function transferSessionToLocalStorage() {
    if (sessionStorage.getItem('registered') === 'true') {
        let currentUsername = sessionStorage.getItem('currentUsername');
        if (!currentUsername) {
            console.error("Username not found in sessionStorage");
            return;
        }
        let len = Object.keys(localStorage).filter(key => key.startsWith('privateKey')).length;
        localStorage.setItem('privateKey' + len + '_' + currentUsername, sessionStorage.getItem('privateKey'));
        localStorage.setItem('publicKey' + len + '_' + currentUsername, sessionStorage.getItem('publicKey'));
        sessionStorage.setItem('registered', 'false');
    }
}

if (isRegistered === 'true' && username) {
    sessionStorage.setItem('currentUsername', username);
    sessionStorage.setItem('registered', 'true');
    transferSessionToLocalStorage();
}