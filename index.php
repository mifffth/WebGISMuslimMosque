<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Persebaran Peribadatan Umat Islam di Kabupaten Sleman</title>
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
            /* Light green background color */
            color: #006400;
            /* Dark green text color */
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px 0;
            /* Added top and bottom margins */
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            /* Added to arrange content vertically */
        }

        #title-container {
            margin-top: 70px; /* Adjusted top margin */
            margin-bottom: 20px;
            /* Added margin to separate title from the table */
        }

        #content-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #006400;
            /* Dark green border color */
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #006400;
            /* Dark green header background color */
            color: #fff;
            /* White text color */
        }

        tr:nth-child(even) {
            background-color: #fff;
            /* White background color for even rows */
        }

        tr:nth-child(odd) {
            background-color: #fff;
            /* White background color for odd rows */
        }

        tr:hover {
            background-color: #d3ffd3;
            /* Light green background color on hover */
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
                    <a id="measureLink" class="nav-link" href="input.php" target="_blank"><i
                            class="fas fa-leaf"></i>Tambah data</a>
                </li>
                <li class="nav-item">
                    <a id="measureLink" class="nav-link" href="geoserver.html" target="_blank"><i
                            class="fas fa-leaf"></i>WebMap</a>
                </li>
            </ul>
        </div>
    </nav>

    <div id="content-container">
        <div id="title-container">
            <h1 style="color: #006400;">Data Persebaran Peribadatan Umat Islam di Kabupaten Sleman</h1>
        </div>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "pgwebresp";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $checkColumnsQuery = "SHOW COLUMNS FROM responsi LIKE 'Latitude'";
        $checkColumnsResult = $conn->query($checkColumnsQuery);

        if ($checkColumnsResult->num_rows == 0) {
            $alterTableQuery = "ALTER TABLE responsi
                ADD COLUMN latitude DECIMAL(10,6) NOT NULL AFTER kecamatan,
                ADD COLUMN longitude DECIMAL(10,6) NOT NULL AFTER latitude";
            $conn->query($alterTableQuery);
        }

        $sql = "SELECT * FROM responsi";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<table class='table table-hover'>
                    <thead>
                        <tr>
                            <th scope='col'>Nomor</th>
                            <th scope='col'>Nama</th>
                            <th scope='col'>Latitude</th>
                            <th scope='col'>Longitude</th>
                            <th scope='col'>Tipe</th>
                        </tr>
                    </thead>
                    <tbody>";

            $row_number = 0;
            while ($row = $result->fetch_assoc()) {
                $row_number++;
                $row_class = $row_number % 2 == 0 ? "even-row" : "odd-row";
                echo "<tr class='$row_class'>
                        <th scope='row'>" . $row_number . "</th>
                        <td>" . $row["Nama"] . "</td>
                        <td>" . $row["Latitude"] . "</td>
                        <td>" . $row["Longitude"] . "</td>
                        <td>" . $row["Tipe"] . "</td>
                    </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>
</body>

</html>
