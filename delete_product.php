<?php
include "db_config.php";
$id = $_GET["id"];
$sql = "DELETE FROM `Products` WHERE ID = $id";
$result = mysqli_query($conn, $sql);

if ($result) {
  header("Location: product.php?msg=Product deleted successfully");
} else {
  echo "Failed: " . mysqli_error($conn);
}