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
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <nav id="home" class="navbar main-navbar mb-5 px-3">
    <button class="btn navbar-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarNav" aria-controls="sidebarNav" aria-label="Open sidebar navigation">
      <i class="fa-solid fa-bars fs-5"></i>
    </button>
    <p class="navbar-title">PHP Complete CRUD Application</p>
  </nav>

  <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarNav" aria-labelledby="sidebarNavLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="sidebarNavLabel">Navigation</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="nav flex-column gap-2">
        <li class="nav-item">
          <a class="nav-link" href="index.php" data-bs-dismiss="offcanvas">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="menu.php" data-bs-dismiss="offcanvas">Menu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="product.php" data-bs-dismiss="offcanvas">Products</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="container">
    <?php
    if (isset($_GET["msg"])) {
      $msg = $_GET["msg"];
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      ' . $msg . '
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    ?>
  </div>

  <div id="products" class="container">
      <a href="add_product.php" class="btn btn-success mb-3">Add New</a>

      <table class="table table-hover text-center">
        <thead class="table-success">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Price</th>
            <th scope="col">Image Path</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT * FROM Products";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
          ?>
            <tr>
              <td><?php echo $row["ID"] ?></td>
              <td><?php echo $row["Name"] ?></td>
              <td>₱ <?php echo number_format($row["Price"], 2) ?></td>
              <td><?php echo $row["ImagePath"] ?></td>
              <td>
                <a href="edit_product.php?id=<?php echo $row["ID"] ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                <a href="delete_product.php?id=<?php echo $row["ID"] ?>" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
  </div>

    <p class="text-center title-green mt-5 mb-0">CCS06-RESTAURANT</p>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script>
    const sidebarLinks = document.querySelectorAll('#sidebarNav .nav-link');

    sidebarLinks.forEach((link) => {
      link.addEventListener('click', function (event) {
        const targetUrl = this.getAttribute('href');

        if (!targetUrl) {
          return;
        }

        event.preventDefault();
        window.location.href = targetUrl;
      });
    });
  </script>

</body>
</html>