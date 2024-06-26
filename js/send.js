function include(file) {

    let script = document.createElement('script');
    script.src = file;
    script.type = 'text/javascript';
    script.defer = true;

    document.getElementsByTagName('head').item(0).appendChild(script);

}

include("https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js");
include("https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js");

// gets username
var user;
$.ajax({
    url: '/backend/echo_username.php',
    type: 'GET',
    success: function (response) {
        // 'response' will contain the session variable value
        user = response;
    }
});

async function uploadEncryptedData(encryptedData, encryptedKey, fileType) {
    let response = await fetch('/backend/upload.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            encryptedData: encryptedData,
            encryptedKey: encryptedKey,
            fileType: fileType // Aggiungi il tipo di file al payload
        })
    });

    if (response.ok) {
        console.log("File uploaded successfully");
    } else {
        console.error("File upload failed");
    }
}

function arrayBufferToBase64(buffer) {
    let binary = '';
    let bytes = new Uint8Array(buffer);
    let len = bytes.byteLength;
    for (let i = 0; i < len; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return window.btoa(binary);
}

async function generateAESKey() {
    return await window.crypto.subtle.generateKey(
        {
            name: "AES-GCM",
            length: 256,
        },
        true,
        ["encrypt", "decrypt"]
    );
}

async function encryptAES(data, key) {
    let encryptedData = await window.crypto.subtle.encrypt(
        {
            name: "AES-GCM",
            iv: crypto.getRandomValues(new Uint8Array(12)), // Genera un vettore di inizializzazione casuale
        },
        key,
        data
    );
    return encryptedData;
}

async function encryptRSA(data, publicKey) {
    let encodedData = getMessageEncoding(data);
    let encryptedData = await window.crypto.subtle.encrypt(
        {
            name: "RSA-OAEP",
        },
        publicKey,
        encodedData
    );
    return encryptedData;
}

function getMessageEncoding(data) {
    let enc = new TextEncoder();
    return enc.encode(data);
}

function readFileAsync(file) {
    return new Promise((resolve, reject) => {
        var reader = new FileReader();
        reader.onload = function () {
            resolve(reader.result);
        };
        reader.onerror = function (error) {
            reject(error);
        };
        reader.readAsArrayBuffer(file); // Leggi come un array di byte (buffer)
    });
}

async function handleFileUpload(e) {
    e.preventDefault();
    if (user != null && document.getElementById('upload').files.length > 0) {
        var pkey = key; // this is from select_file.php (works fine)
        let file = document.getElementById('upload').files[0];
        try {
            // Encrypt file here
            var file_content = await readFileAsync(file);

            // Generate random AES key
            let AES_key = await generateAESKey(); // 32-byte key for AES-256
            var encrypted_data = await encryptAES(file_content, AES_key);

            let aesKeyData = await window.crypto.subtle.exportKey("jwk", AES_key);
            let aesKeyJson = JSON.stringify(aesKeyData);
            let encoded_key = getMessageEncoding(aesKeyJson);


            // Encrypt AES key with user public key
            let encrypted_key = await encryptRSA(encoded_key, pkey);
            let buffer_key = arrayBufferToBase64(encrypted_key);

            // Upload encrypted data to server
            await uploadEncryptedData(encrypted_data, buffer_key, file.type);
            window.location.href = "select_file.php?success=1";

        } catch (err) {
            console.error(err);
            window.location.href = "select_file.php?success=0";
        }
    }
};

var input = document.getElementById("send");
input.addEventListener('click', handleFileUpload);

document.getElementById("cancel_button").onclick = function () {
    location.href = "search_user.php"
}


var input = document.getElementById("send");
input.addEventListener('click', handleFileUpload);

document.getElementById("cancel_button").onclick = function () {
    location.href = "search_user.php"
}
