<?php
include "db_config.php";

$errorMessage = "";
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: menuproduct.php?msg=Invalid menu product ID");
    exit;
}

$menuOptions = [];
$productOptions = [];
$currentMenuId = 0;
$currentProductId = 0;

$currentStmt = mysqli_prepare($connected, "SELECT MenuID, ProductID FROM MenuProducts WHERE ID = ? LIMIT 1");
if ($currentStmt) {
    mysqli_stmt_bind_param($currentStmt, "i", $id);
    mysqli_stmt_execute($currentStmt);
    $currentResult = mysqli_stmt_get_result($currentStmt);
    $currentRow = mysqli_fetch_assoc($currentResult);
    mysqli_stmt_close($currentStmt);

    if (!$currentRow) {
        header("Location: menuproduct.php?msg=Menu product not found");
        exit;
    }

    $currentMenuId = (int)$currentRow["MenuID"];
    $currentProductId = (int)$currentRow["ProductID"];
} else {
    $errorMessage = "Failed to prepare current record query: " . mysqli_error($connected);
}

$menuResult = mysqli_query($connected, "SELECT ID FROM Menus ORDER BY ID ASC");
if ($menuResult) {
    while ($row = mysqli_fetch_assoc($menuResult)) {
        $menuOptions[] = (int)$row["ID"];
    }
}

$productResult = mysqli_query($connected, "SELECT ID FROM Products ORDER BY ID ASC");
if ($productResult) {
    while ($row = mysqli_fetch_assoc($productResult)) {
        $productOptions[] = (int)$row["ID"];
    }
}

if (isset($_POST["submit"])) {
    $menuId = isset($_POST["menuID"]) ? (int)$_POST["menuID"] : 0;
    $productId = isset($_POST["productID"]) ? (int)$_POST["productID"] : 0;

    if ($menuId <= 0 || $productId <= 0) {
        $errorMessage = "Please select both a menu and a product.";
    } elseif (!in_array($menuId, $menuOptions, true) || !in_array($productId, $productOptions, true)) {
        $errorMessage = "Invalid menu or product selection.";
    } else {
        $updateStmt = mysqli_prepare($connected, "UPDATE MenuProducts SET MenuID = ?, ProductID = ? WHERE ID = ?");

        if ($updateStmt) {
            mysqli_stmt_bind_param($updateStmt, "iii", $menuId, $productId, $id);
            $updated = mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);

            if ($updated) {
                header("Location: menuproduct.php?msg=Menu product updated successfully");
                exit;
            }

            $errorMessage = "Failed: " . mysqli_error($connected);
        } else {
            $errorMessage = "Failed to prepare update query: " . mysqli_error($connected);
        }
    }

    if ($errorMessage === "") {
        $currentMenuId = $menuId;
        $currentProductId = $productId;
    } else {
        $currentMenuId = $menuId > 0 ? $menuId : $currentMenuId;
        $currentProductId = $productId > 0 ? $productId : $currentProductId;
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
            <h3>Edit Menu Product</h3>
            <p class="text-muted">Select a menu and a product, then click update</p>
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
                            <?php foreach ($menuOptions as $menuIdOption) { ?>
                                <option value="<?php echo (int)$menuIdOption; ?>" <?php echo ((int)$currentMenuId === (int)$menuIdOption) ? 'selected' : ''; ?>>
                                    <?php echo (int)$menuIdOption; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Product ID:</label>
                        <select class="form-select" name="productID" required>
                            <option value="">Select product</option>
                            <?php foreach ($productOptions as $productIdOption) { ?>
                                <option value="<?php echo (int)$productIdOption; ?>" <?php echo ((int)$currentProductId === (int)$productIdOption) ? 'selected' : ''; ?>>
                                    <?php echo (int)$productIdOption; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <br>

                <div>
                    <button type="submit" class="btn btn-success" name="submit">Update</button>
                    <a href="menuproduct.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
