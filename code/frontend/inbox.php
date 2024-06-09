<?php include '../backend/verify_session.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Project Domain - Inbox</title>
    <link rel="stylesheet" href="../style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Reddit+Mono:wght@200..900&family=Tiny5&display=swap');</style>
</head>

<body>

<div id="header">
    <div id="session_header"></div>
    <h1 id="page_title">Inbox</h1>
    <div id="session_header">
        <?php echo "<p id='session_banner'>"."Logged as: ".$_SESSION['session_username']."</p>";?>
        <button id="logout_button" > Logout </button>
    </div>
</div>
<div id="table_container">
    <div class="content_table" id="inbox_table">
        <?php include '../backend/fetch_files.php'; ?>
        <?php foreach ($files as $file): ?>
        <a href="#" class="download-link" 
            data-encrypted-data="<?php echo $file['encrypted_data']; ?>" 
            data-encrypted-key="<?php echo $file['encrypted_key']; ?>"
            data-file-type="<?php echo $file['file_type']; ?>">
            <?php echo $file['file_name'] . " - " . substr($file['uploaded_at'], 0, 19); ?>
        </a><br>
        <?php endforeach; ?>
    </div>
    <button id="cancel_button"> Cancel </button>
</div>
</body>

<script type="text/javascript" src="../js/inbox.js"></script>
<script>
function base64ToArrayBuffer(base64String) {
    let binaryString = window.atob(base64String);
    let byteArray = new Uint8Array(binaryString.length);
    for (let i = 0; i < binaryString.length; i++) {
        byteArray[i] = binaryString.charCodeAt(i);
    }
    return byteArray;
}

async function decryptMessage(privateKey, encrypted) {
    return await window.crypto.subtle.decrypt(
        {
            name: "RSA-OAEP",
        },
        privateKey,
        encrypted,
    );
}

async function downloadFile(encryptedData, encryptedKey) {
    let privateKey;
    try {
        let currentUsername = sessionStorage.getItem("currentUsername");
        if (!currentUsername) {
            console.error("Current user not found in sessionStorage");
            return;
        }

        // Get the right key from localStorage
        for (let i = 0; i < localStorage.length; i++) {
            let key = localStorage.key(i);
            if (key.startsWith('privateKey') && key.includes(currentUsername)) {
                let privateKeyData = JSON.parse(localStorage.getItem(key));
                privateKey = await window.crypto.subtle.importKey(
                    'jwk', privateKeyData,
                    { name: 'RSA-OAEP', hash: { name: 'SHA-256' } },
                    true, ['decrypt']
                );
                break;
            }
        }

        if (!privateKey) {
            console.error('No key found for current user.');
            return;
        }

        // decrypt AES key
        let decodedKey = base64ToArrayBuffer(encryptedKey);
        let decryptedKeyBuffer = await decryptMessage(privateKey, decodedKey);
        let decryptedKey = new TextDecoder().decode(decryptedKeyBuffer);

        // Decrypt the file data with the AES key atob(CryptoJS.AES.decrypt(encrypted_data, decryptedKey).toString(CryptoJS.enc.Utf8));
        let decryptedData = atob(CryptoJS.AES.decrypt(encryptedData, decryptedKey).toString(CryptoJS.enc.Utf8));
        console.log('Decrypted Data:', decryptedData);

        // Create a blob and initiate the download
        let blob = new Blob([decryptedData], { type: 'text/plain' });
        let url = URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = "<?php echo $file['file_name']; ?>";
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);

    } catch (err) {
        console.error('Errore durante la decifratura:', err);
    }
}

// Event listener for all generated links
document.querySelectorAll('.download-link').forEach(link => {
    link.addEventListener('click', function (event) {
        event.preventDefault();
        let encryptedData = this.getAttribute('data-encrypted-data');
        let encryptedKey = this.getAttribute('data-encrypted-key');
        downloadFile(encryptedData, encryptedKey);
    });
});
</script>
<script type="text/javascript" src="../js/logout.js"></script>

</html>