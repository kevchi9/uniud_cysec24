function base64ToArrayBuffer(base64String) {
    let binaryString = window.atob(base64String);
    let byteArray = new Uint8Array(binaryString.length);
    for (let i = 0; i < binaryString.length; i++) {
        byteArray[i] = binaryString.charCodeAt(i);
    }
    return byteArray;
}

async function downloadFile(encryptedData, encryptedKey, fileType) {
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
        let blob = new Blob([decryptedData], { type: fileType });
        let url = URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = 'decrypted_file';
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
        let fileType = this.getAttribute('data-file-type');
        downloadFile(encryptedData, encryptedKey, fileType);
    });
});