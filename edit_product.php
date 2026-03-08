<?php
include "db_config.php";

$errorMessage = "";
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: product.php?msg=Invalid product ID");
    exit;
}

$stmt = mysqli_prepare($connected, "SELECT Name, Price, ImagePath FROM Products WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$product) {
    header("Location: product.php?msg=Product not found");
    exit;
}

if (isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $newName = $name !== '' ? $name : $product['Name'];
    $newPrice = $price !== '' ? (float) $price : (float) $product['Price'];
    $newImagePath = $product['ImagePath'];
    $newUploadedFilePath = '';

    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = $_FILES['fileToUpload']['name'];
        $tmpFile = $_FILES['fileToUpload']['tmp_name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (getimagesize($tmpFile) === false) {
            $errorMessage = "Uploaded file is not a valid image.";
        } elseif (!in_array($extension, $allowedExtensions, true)) {
            $errorMessage = "Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.";
        } else {
            $safeBaseName = preg_replace('/[^A-Za-z0-9_-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
            if ($safeBaseName === '' || $safeBaseName === null) {
                $safeBaseName = 'product_image';
            }

            $fileName = $safeBaseName . '_' . uniqid('', true) . '.' . $extension;
            $destinationPath = $uploadDir . $fileName;

            if (!move_uploaded_file($tmpFile, $destinationPath)) {
                $errorMessage = "Failed to upload image. Please try again.";
            } else {
                $newUploadedFilePath = $destinationPath;
                $newImagePath = 'assets/' . $fileName;
            }
        }
    }

    if ($errorMessage === '') {
        $stmt = mysqli_prepare($connected, "UPDATE Products SET Name = ?, Price = ?, ImagePath = ? WHERE ID = ?");
        mysqli_stmt_bind_param($stmt, "sdsi", $newName, $newPrice, $newImagePath, $id);
        $saved = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($saved) {
            // Remove old file only after successful DB update and only if image was replaced.
            if ($newUploadedFilePath !== '' && !empty($product['ImagePath'])) {
                $oldFilePath = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $product['ImagePath']);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            header("Location: product.php?msg=Product updated successfully");
            exit;
        }

        if ($newUploadedFilePath !== '' && file_exists($newUploadedFilePath)) {
            unlink($newUploadedFilePath);
        }
        $errorMessage = "Failed: " . mysqli_error($connected);
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
            <h3>Edit Product</h3>
        </div>

        <?php if ($errorMessage !== "") { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php } ?>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" enctype="multipart/form-data" style="width:50vw; min-width:300px;">
                <div class="row">
                    <div class="col">
                        <label class="form-label">Name:</label>
                        <input type="text" class="form-control" name="name" placeholder="<?php echo htmlspecialchars($product['Name']); ?>">
                    </div>
                    <div class="col">
                        <label class="form-label">Price:</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="price" placeholder="<?php echo htmlspecialchars(number_format((float) $product['Price'], 2)); ?>">
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col">
                        <label class="form-label" for="fileToUpload">Product Image:</label>
                        <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" accept="image/*">
                        <small class="text-muted"><?php echo htmlspecialchars($product['ImagePath']); ?></small>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-success" name="submit">Save</button>
                        <a href="product.php" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>