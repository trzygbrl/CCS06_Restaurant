<?php
// Database Configuration - Keep this file secure and don't commit to Git
$servername = "restaurantdb-ccs06.mysql.database.azure.com";  // Replace with your Azure server
$username = "admin_3z";                      // Replace with your Azure username
$password = "Gabrielnicolas2026";                             // Replace with your Azure password
$dbname = "restaurant";

$conn = mysqli_init();
mysqli_ssl_set($conn, null, null, null, null, null);

$sslFlags = MYSQLI_CLIENT_SSL;
if (defined('MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT')) {
  $sslFlags |= MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT;
}

if (!mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, null, $sslFlags)) {
    die("Connection failed: " . mysqli_connect_error());
}

$connected = $conn;

?>
