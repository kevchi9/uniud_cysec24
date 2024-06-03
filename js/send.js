{/* <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script> */ }


document.getElementById("cancel_button").onclick = function () {
    location.href = "index.html"
}

function include(file) {

    let script = document.createElement('script');
    script.src = file;
    script.type = 'text/javascript';
    script.defer = true;

    document.getElementsByTagName('head').item(0).appendChild(script);

}

include("https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js");

function encrypt(data, key) {
    var encrypted = CryptoJS.AES.encrypt(data, key);
    return encrypted;
}

var input = document.getElementById("upload")
input.addEventListener('change', function (e) {
    if (e.target.files[0]) {
        let file = e.target.files[0];
        var reader = new FileReader();
        reader.readAsText(file);
        reader.onload = function () {
            var file_content = reader.result;
            console.log("data: " + file_content);

            let key = Math.random().toString(36).slice(2);
            console.log("key: " + key);

            var encrypted = CryptoJS.AES.encrypt(file_content, Math.random().toString(36).slice(2))
            console.log("encrypted: " + encrypted);
        }
        // encrypt file here


        // var form = document.getElementById('send_form');
        // var input = document.createElement('input');
        // input.setAttribute('name', 'enc_file');
        // input.setAttribute('value', json_key);
        // input.setAttribute('type', 'hidden');
        // form.appendChild(input);

        // // AES key
        // var input_key = document.createElement('input');
    }
})