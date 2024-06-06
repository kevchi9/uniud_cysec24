document.getElementById("cancel_button").onclick = function () {
    location.href = "index.php";
}

function include(file) {

    let script = document.createElement('script');
    script.src = file;
    script.type = 'text/javascript';
    script.defer = true;

    document.getElementsByTagName('head').item(0).appendChild(script);

}

include("https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js");
include("https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js");

async function uploadEncryptedData(encryptedData, fileName, publisher) {

    // TODO: Change this URL
    let response = await fetch('/backend/publish.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            encryptedData: encryptedData,
            fileName: fileName,
            publisher: publisher
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
    let pswd = document.getElementById('enc_pswd').value;
    let publisher = sessionStorage.getItem('currentUsername')
    if (publisher != null && pswd != null && document.getElementById('upload').files.length > 0) {

        let file = document.getElementById('upload').files[0];
        try {
            // Encrypt file here
            var file_content = arrayBufferToBase64(await readFileAsync(file));
            console.log(file_content);
            console.log(pswd);
            // encrypt data with pswd
            var encrypted_data = CryptoJS.AES.encrypt(file_content, pswd).toString();

            // upload encrypted data to server
            await uploadEncryptedData(encrypted_data, file.name, publisher);
            window.location.href = "publish.php?success=1";
        } catch (err) {
            console.error(err);
            window.location.href = "publish.php?success=0";
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('publish_form');
    form.addEventListener('submit', handleFileUpload);

});