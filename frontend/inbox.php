<?php include '../backend/verify_session.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Project Domain - Inbox</title>
    <link rel="stylesheet" href="../style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
</head>

<body>

<div id="header">
    <h1 id="page_title">Inbox</h1>
    <button id="logout_button"> Logout </button>
</div>
    <div class="content_table" id="inbox_table">
        <?php include '../backend/fetch_files.php'; ?>
        <?php foreach ($files as $file): ?>
        <a href="#" class="download-link" 
           data-encrypted-data="<?php echo $file['encrypted_data']; ?>" 
           data-encrypted-key="<?php echo $file['encrypted_key']; ?>"
           data-file-type="<?php echo $file['file_type']; ?>">
           File <?php echo $file['id']; ?>
        </a><br>
        <?php endforeach; ?>

    </div>
    <button id="cancel_button"> Cancel </button>
</body>

<script type="text/javascript" src="../js/inbox.js"></script>
<script>
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

        console.log(privateKey);
        let priv = await crypto.subtle.exportKey('jwk', privateKey);
        console.log(JSON.stringify(priv));
        // Decode the base64 encrypted key
        let encryptedKeyBuffer = Uint8Array.from(atob(encryptedKey), c => c.charCodeAt(0));

        // Decrypt the AES key with the private RSA key
        let decryptedKeyBuffer = await window.crypto.subtle.decrypt(
            { name: 'RSA-OAEP' },
            privateKey,
            encryptedKeyBuffer
        );

        let AESKey = new TextDecoder().decode(decryptedKeyBuffer);

        // Decrypt the file data with the AES key
        let decryptedData = CryptoJS.AES.decrypt(encryptedData, AESKey).toString(CryptoJS.enc.Utf8);
        console.log('Decrypted Data:', decryptedData);

        // Create a blob and initiate the download
        let blob = new Blob([decryptedData], { type: 'text/plain' });
        let url = URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = 'decrypted_file.txt';
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