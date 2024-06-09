(async function () {
    let keyPair = await window.crypto.subtle.generateKey(
        {
            name: "RSA-OAEP",
            modulusLength: 4096,
            publicExponent: new Uint8Array([1, 0, 1]),
            hash: "SHA-256",
        },
        true,
        ["encrypt", "decrypt"],
    );

    // extract keys from key pair
    let pkey = await window.crypto.subtle.exportKey("jwk", keyPair.publicKey);
    let priv = await window.crypto.subtle.exportKey("jwk", keyPair.privateKey);
    let json_key = JSON.stringify(pkey);

    // store key pair in client browser
    localStorage.privateKey = JSON.stringify(priv);
    localStorage.publicKey = json_key;

    // send public key to php script 
    var form = document.getElementById('send_form');
    var input = document.createElement('input');
    input.setAttribute('name', 'json_key');
    input.setAttribute('value', json_key);
    input.setAttribute('type', 'hidden');
    form.appendChild(input);
})()