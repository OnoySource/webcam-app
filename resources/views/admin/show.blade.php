<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin page</title>
  <style>
    body{
      font-size:11px;
      font-family:arial;
    }
    section{
      display:flex;
    }
  </style>
</head>
<body style="font-family: arial;">
  <h1 style="font-size: 14px;">Selamat datang admin</h1>
  <span style="display:flex; justify-content:space-between;">
  <h2 style="font-size: 14px;">Image auto : {{ $countAuto }}</h2>
  <h2 style="font-size: 14px;">Image manual : {{ $countManual }}</h2>
  </span>
<section>
  <div class="div">
    
  <table border="1" style="text-align: center;">
    <tr>
      <th>No</th>
      <th>Image auto</th>
      <th>Created At</th>
    </tr>
    @php
      $i = 1;
    @endphp
    @foreach($imagesAuto as $imageA)
    <tr>
      <td>{{ $i++ }}</td>
      <td style="width: 30%;">
        <img src="{{ asset('storage/' . $imageA->path) }}" style="width: 100%;" alt="Auto Image">
      </td>
      <td>{{ $imageA->created_at }}</td>
    </tr>
    @endforeach
  </table>
  </div>
  
  <div class="div">
  <table border="1" style="text-align: center;">
    <tr>
      <th>No</th>
      <th>Image manual</th>
      <th>Created At</th>
    </tr>
    @php
      $i = 1;
    @endphp
    @foreach($imagesManual as $imageM)
    <tr>
      <td>{{ $i++ }}</td>
      <td style="width: 30%;">
        <img src="{{ asset('storage/' . $imageM->image) }}" style="width: 100%;" alt="Auto Manual">
      </td>
      <td>{{ $imageM->created_at }}</td>
    </tr>
    @endforeach
  </table>
  </div>
</section>
</body>
</html>