<?php
require_once 'db_config.php';

$conn = mysqli_init();
mysqli_ssl_set($conn, null, null, null, null, null);

$sslFlags = MYSQLI_CLIENT_SSL;
if (defined('MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT')) {
  $sslFlags |= MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT;
}

$connected = mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, null, $sslFlags);

if (!$connected) {
  die("Connection failed: " . mysqli_connect_error());
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Potato Corner Menu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }

    .title-green {
      color: #0a8f1f;
      font-weight: 700;
    }

    .product-image {
      width: 100%;
      height: 220px;
      object-fit: contain;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <h1 class="text-center title-green mb-4">POTATO CORNER MENU LIST</h1>

  <?php
  $sql = "SELECT ID, Name, Price, ImagePath FROM Products";
  // Execute the SQL query
  $result = $conn->query($sql);

  // Process the result set
  if ($result->num_rows > 0) {
  ?>

  <div class="row g-4">
    <?php
      while($row = $result->fetch_assoc()) {
        echo '<div class="col-12 col-md-6">';
        echo '  <div class="card h-100 shadow-sm border-0">';
        echo '    <img src="' . $row["ImagePath"] . '" class="product-image p-3" alt="Image could not be found">';
        echo '    <div class="card-body pt-0">';
        echo '      <h5 class="card-title fw-bold text-uppercase mb-2">' . $row["Name"] . '</h5>';
        echo '      <p class="card-text fs-5 mb-0">Price: ₱ ' . $row["Price"] . '</p>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
      }
    ?>
  </div>
  <?php
  } else {
    echo '<div class="alert alert-warning text-center">0 results</div>';
  }

  $conn->close();
  ?>

  <p class="text-center title-green mt-5 mb-0">CCS06-RESTAURANT</p>
</div>
</body>
</html>