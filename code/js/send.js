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

async function uploadEncryptedData(encryptedData, encryptedKey, fileType, fileName) {
    let response = await fetch('/backend/upload.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            encryptedData: encryptedData,
            encryptedKey: encryptedKey,
            fileType: fileType, // Aggiungi il tipo di file al payload
            fileName: fileName
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

function base64ToArrayBuffer(base64String) {
    let binaryString = window.atob(base64String);
    let byteArray = new Uint8Array(binaryString.length);
    for (let i = 0; i < binaryString.length; i++) {
        byteArray[i] = binaryString.charCodeAt(i);
    }
    return byteArray;
}

async function encryptMessage(publicKey, encoded) {
    return await window.crypto.subtle.encrypt(
        {
            name: "RSA-OAEP",
        },
        publicKey,
        encoded,
    );
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
        reader.readAsArrayBuffer(file);
    });
}

async function handleFileUpload(e) {
    e.preventDefault();
    if (user != null && document.getElementById('upload').files.length > 0) {

        var pkey = key; // this is from select_file.php (works fine)
        let file = document.getElementById('upload').files[0];
        try {
            // Encrypt file here
            var file_content = arrayBufferToBase64(await readFileAsync(file));

            // Generate random AES key
            let AES_key = Math.random().toString(36).slice(2);
            // encrypt data with AES key
            var encrypted_data = CryptoJS.AES.encrypt(file_content, AES_key).toString();

            // encrypt AES key with user public key
            let encoded_key = getMessageEncoding(AES_key);
            let res = await encryptMessage(pkey, encoded_key);
            let encrypted_key = arrayBufferToBase64(res);

            // upload encrypted data to server
            await uploadEncryptedData(encrypted_data, encrypted_key, file.type, file.name);
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