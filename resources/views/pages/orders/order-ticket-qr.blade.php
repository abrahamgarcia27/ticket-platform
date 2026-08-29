<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Ticket</title>
    <style>
        h1 {
            padding-bottom: 10px;
            font-family:Verdana, Geneva, Tahoma, sans-serif;
        }
        p {
            font-family:'Courier New', Courier, monospace;
        }
        .pdf-container {
            display: flex !important;
            justify-content: center !important;
        }

    </style>
  </head>
  <body >
    <div class="pdf-container">
        <table class="container" align="center" style="width: 100%">
            <div class="card d-flex justify-content-center" style="width: 30rem; border: none; float:">
                <img src="{{ asset('storage/' . $event_image) }}" class="card-img-top" alt="">
                <div class="card-body ">
                  <h1 class="card-title " style="text-align: center;"><strong>{{ $name_ticket }}</strong></h1>
                  <img src="{{ route('qr.generate', ['code' => $qr]) }}" style="padding-left: 125px" alt="">
                </div>
                <div class="card-footer" style="text-align: center">
                    <p>Visit our website <strong>{{ $website }}</strong></p>
                </div>
            </div>
        </table>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
