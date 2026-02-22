<html>

<head>
    <title>Potato Corner Menu</title>
</head>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
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