<?php
// Koneksi ke basis data MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pgweb-responsi";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil data latitude dan longitude dari tabel "pgweb-responsi" dalam basis data
$sql = "SELECT lat, lon, Nama FROM pgweb-responsi"; // Replace with your actual table and column names
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [$row['lat'], $row['lon'], $row['Nama']]; // Adjust column names
    }
}

// Menutup koneksi ke basis data
$conn->close();

// Mengirim data dalam format JSON sebagai respons
header('Content-Type: application/json');
echo json_encode($data);
?>
