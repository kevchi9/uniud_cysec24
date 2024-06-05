async function generateKey() {
    const key = await crypto.subtle.generateKey(
        {
            name: "RSA-OAEP",
            modulusLength: 4096,
            publicExponent: new Uint8Array([1, 0, 1]),
            hash: "SHA-256",
        },
        true, // Usabile per esportazione
        ["encrypt", "decrypt"]
    );
    return key;
}

async function exportKeyToJWK(key) {
    const rawKey = await crypto.subtle.exportKey('jwk', key);
    return rawKey;
}

(async function () {

    let keyPair = await generateKey();

    // extract keys from key pair
    let pub = await exportKeyToJWK(keyPair.publicKey);
    let priv = await exportKeyToJWK(keyPair.privateKey);

    // 768 chars
    let json_pub_key = JSON.stringify(pub);
    let json_priv_key = JSON.stringify(priv);

    sessionStorage.setItem('publicKey', json_pub_key);
    sessionStorage.setItem('privateKey', json_priv_key);

    // send public key to php script 
    let form = document.getElementById('send_form');
    var input = document.createElement('input');

    input.setAttribute('name', 'json_pub_key');
    input.setAttribute('value', json_pub_key);
    input.setAttribute('type', 'hidden');
    form.appendChild(input);

})()

document.getElementById("login_button").onclick = function () {
    location.href = "login.php";
}