<html>

<head>
    <title>Potato Corner Menu</title>
</head>

<?php
// Include database configuration
require_once 'db_config.php';

// Create secure connection (Azure MySQL requires TLS)
$conn = mysqli_init();

if (!$conn) {
  die("MySQL initialization failed.");
}

mysqli_ssl_set($conn, null, null, null, null, null);

$sslFlags = MYSQLI_CLIENT_SSL;
if (defined('MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT')) {
  $sslFlags |= MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT;
}

$connected = mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, null, $sslFlags);

// Check connection
if (!$connected) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully <br>";

$sql = "SELECT ID, Name, Price, ImagePath FROM Products";
// Execute the SQL query
$result = $conn->query($sql);

// Process the result set
if ($result->num_rows > 0) {
  // Output data of each row
?>

<?php
  while($row = $result->fetch_assoc()) { ?>

<?php
    echo "id: " . $row["ID"].
         " - Product Name: " . $row["Name"]. 
         " - Price: " . $row["Price"]. "<br>" . 
         '<img src="' . $row["ImagePath"] . '" alt="Image could not be found" width="250" height="250" />' . "<br>";
  }
} else {
  echo "0 results";
}

$conn->close();
?>

</html>