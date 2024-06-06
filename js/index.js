// event listener for clicking on Send
document.getElementById("send_button").onclick = function () {
    location.href = "search_user.php";
}

document.getElementById("inbox_button").onclick = function () {
    location.href = "inbox.php";
}

document.getElementById("publish_button").onclick = function () {
    location.href = "publish.php";
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('search_form');
    form.addEventListener('submit', async function (event) {
        document.getElementById("published_results").style.visibility = "visible";
        event.preventDefault(); // Previene l'invio del form e il redirect

        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            const files = await response.json();
            displayFiles(files);
        } else {
            console.error('Error fetching files:', response.statusText);
        }
    });
});

function displayFiles(files) {
    const resultsDiv = document.getElementById('published_results') || document.createElement('div');
    resultsDiv.id = 'published_results';
    resultsDiv.innerHTML = ''; // Pulisce il contenuto precedente

    if (files.length === 0) {
        resultsDiv.textContent = 'No files found.';
    } else {
        files.forEach(file => {
            const link = document.createElement('a');
            link.href = "#"; // Prevent default link action
            link.textContent = file.file_name + " - " + file.uploaded_at.substring(0, 19);
            link.className = 'download-link';

            // Save file data in link element
            link.dataset.encryptedData = file.encrypted_data;

            link.addEventListener('click', function (event) {
                event.preventDefault();
                const password = prompt("Enter the decryption password:");
                if (password) {
                    decryptFile(file.encrypted_data, password, file.file_name);
                }
            });

            resultsDiv.appendChild(link);
            resultsDiv.appendChild(document.createElement('br'));
        });
    }

    const existingResultsDiv = document.getElementById('results');
    if (!existingResultsDiv) {
        form.parentElement.appendChild(resultsDiv);
    }
}

async function decryptFile(encryptedData, password, fileName) {
    try {
        const decrypted = CryptoJS.AES.decrypt(encryptedData, password);
        const decryptedText = decrypted.toString(CryptoJS.enc.Utf8);

        if (!decryptedText) {
            alert("Incorrect password or decryption failed.");
            return;
        }

        const blob = base64ToBlob(decryptedText);
        const url = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = url;
        a.download = fileName;
        a.click();
        URL.revokeObjectURL(url);
    } catch (error) {
        console.error('Decryption error:', error);
        alert('Decryption failed.');
    }
}

function base64ToBlob(base64) {
    const byteCharacters = atob(base64);
    const byteNumbers = new Array(byteCharacters.length);
    for (let i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt(i);
    }
    const byteArray = new Uint8Array(byteNumbers);
    return new Blob([byteArray]);
}