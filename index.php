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
    <div class="offcanvas-body ">
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
  </div>

  <div id="products" class="container py-4">
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
        while($row = $result->fetch_assoc()) { ?>
          <div class="col-12 col-md-6">
            <div class="card h-100 shadow-sm border-0">
              <img src="<?php echo $row["ImagePath"]; ?>" class="product-image p-3" alt="Image could not be found">
              <div class="card-body pt-0">
                <h5 class="card-title fw-bold text-uppercase mb-2"><?php echo $row["Name"]; ?></h5>
                <p class="card-text fs-5 mb-0">Price: ₱ <?php echo $row["Price"]; ?></p>
              </div>
            </div>
          </div>
        <?php }
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