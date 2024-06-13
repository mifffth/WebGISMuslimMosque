<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Persebaran Peribadatan Umat Islam di Kabupaten Sleman</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
        crossorigin="anonymous">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">

    <link href="https://unsorry.net/assets-date/images/favicon.png" rel="shortcut icon" type="image/png">


    
    <style>
    body {
        background-color: #d3ffd3;
        color: #006400;
        font-family: Arial, sans-serif;
        text-align: center;
        margin: 0;
        padding-top: 56px; /* Add padding-top to accommodate the fixed navbar */
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh; /* Use min-height to ensure the container takes at least the full viewport height */
    }

    #form-container {
        max-width: 400px;
        padding: 20px;
        background-color: #f5f5f5;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    h1 {
        text-align: center;
        color: #006400;
    }

    form {
        margin-top: 20px;
    }

    label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }

    input[type="text"] {
        width: 90%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    input[type="submit"] {
        background-color: #006400;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #8db600;
    }
    </style>
</head>

<body>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
        <a class="navbar-brand"
            href="#"><b><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;WEBGIS | Persebaran Peribadatan Umat Islam
                di Kabupaten Sleman</b></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto d-flex">
                <li class="nav-item">
                    <a id="measureLink" class="nav-link" href="index.html" target="_blank"><i
                            class="fas fa-leaf"></i>Landing Page</a>
                </li>
                <li class="nav-item">
                    <a id="measureLink" class="nav-link" href="index.php" target="_blank"><i
                            class="fas fa-leaf"></i>Persebaran</a>
                </li>
                <li class="nav-item">
                    <a id="measureLink" class="nav-link" href="geoserver.html" target="_blank"><i
                            class="fas fa-leaf"></i>WebMap</a>
                </li>
            </ul>
        </div>
    </nav>
    <div id="form-container">
        <h1>Input Persebaran Peribadatan Umat Islam di Kabupaten Sleman</h1>

        <form method="post" action="">
            <label for="Nama">Nama:</label>
            <input type="text" name="Nama" id="Nama">
            <br>
            <label for="Latitude">Latitude:</label>
            <input type="text" name="Latitude" id="Latitude">
            <br>
            <label for="Longitude">Longitude:</label>
            <input type="text" name="Longitude" id="Longitude">
            <br>
            <label for="Tipe">Tipe peribadatan:</label>
            <input type="text" name="Tipe" id="Tipe">

            <input type="submit" name="submit" value="Simpan">
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $nama = $_POST['Nama'];
            $latitude = $_POST['Latitude'];
            $longitude = $_POST['Longitude'];
            $kategori = $_POST['Tipe'];

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "pgwebresp";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO responsi (Nama, Longitude, Latitude, Tipe) VALUES ('$nama', '$longitude', '$latitude', '$kategori')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        }
        ?>
    </div>
</body>

</html>
