<?php
include "db_config.php";
$id = $_GET["id"];
$sql = "DELETE FROM `MenuProducts` WHERE ID = $id";
$result = mysqli_query($conn, $sql);

if ($result) {
  header("Location: menuproduct.php?msg=Menu product deleted successfully");
} else {
  echo "Failed: " . mysqli_error($conn);
}