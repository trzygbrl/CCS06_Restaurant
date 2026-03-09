<?php
include "db_config.php";

$errorMessage = "";
$menuOptions = [];
$productOptions = [];

$menuResult = mysqli_query($connected, "SELECT ID FROM Menus ORDER BY ID ASC");
if ($menuResult) {
    while ($row = mysqli_fetch_assoc($menuResult)) {
        $menuOptions[] = $row["ID"];
    }
}

$productResult = mysqli_query($connected, "SELECT ID FROM Products ORDER BY ID ASC");
if ($productResult) {
    while ($row = mysqli_fetch_assoc($productResult)) {
        $productOptions[] = $row["ID"];
    }
}

if (isset($_POST['submit'])) {
    $menu_id = isset($_POST['menuID']) ? (int)$_POST['menuID'] : 0;
    $product_id = isset($_POST['productID']) ? (int)$_POST['productID'] : 0;

    if ($menu_id <= 0 || $product_id <= 0) {
        $errorMessage = "Please select both a menu and a product.";
    }

    if ($errorMessage === "") {
        $stmt = mysqli_prepare($connected, "INSERT INTO MenuProducts (ID, MenuID, ProductID) VALUES (NULL, ?, ?)");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $menu_id, $product_id);
            $saved = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($saved) {
                header("Location: menuproduct.php?msg=New menu product added successfully");
                exit;
            }

            $errorMessage = "Failed: " . mysqli_error($connected);
        } else {
            $errorMessage = "Failed to prepare query: " . mysqli_error($connected);
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #0a8f1f; color: white; font-weight: 700;">
        PHP Complete CRUD Application
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3>Add New Menu Product</h3>
            <p class="text-muted">Complete the form below to add a new menu product</p>
        </div>

        <?php if ($errorMessage !== "") { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php } ?>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px;">
                <div class="row">
                    <div class="col">
                        <label class="form-label">Menu ID:</label>
                        <select class="form-select" name="menuID" required>
                            <option value="">Select menu</option>
                            <?php foreach ($menuOptions as $menuId) { ?>
                                <option value="<?php echo (int)$menuId; ?>" <?php echo (isset($_POST['menuID']) && (int)$_POST['menuID'] === (int)$menuId) ? 'selected' : ''; ?>>
                                    <?php echo (int)$menuId; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Product ID:</label>
                        <select class="form-select" name="productID" required>
                            <option value="">Select product</option>
                            <?php foreach ($productOptions as $productId) { ?>
                                <option value="<?php echo (int)$productId; ?>" <?php echo (isset($_POST['productID']) && (int)$_POST['productID'] === (int)$productId) ? 'selected' : ''; ?>>
                                    <?php echo (int)$productId; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <br>

                <div>
                    <button type="submit" class="btn btn-success" name="submit">Save</button>
                    <a href="menuproduct.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>