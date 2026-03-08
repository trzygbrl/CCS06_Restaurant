<?php
require_once 'db_config.php';

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

  <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #0a8f1f; color: white; font-weight: 700;">
        PHP Complete CRUD Application
  </nav>

  <div class="container">
    <a href="add_new.php" class="btn btn-success mb-3">Add New</a>

    <table class="table table-hover text-center">
      <thead class="table-success">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Name</th>
          <th scope="col">Date Created</th>
          <th scope="col">Date Updated</th>
          <th scope="col">Date Deleted</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM Menus";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
            <td><?php echo $row["ID"] ?></td>
            <td><?php echo $row["Name"] ?></td>
            <td><?php echo $row["DateCreated"] ?></td>
            <td><?php echo $row["DateUpdated"] ?></td>
            <td><?php echo $row["DateDeleted"] ?></td>
            <td>
              <a href="edit.php?id=<?php echo $row["ID"] ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
              <a href="delete.php?id=<?php echo $row["ID"] ?>" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>

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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>