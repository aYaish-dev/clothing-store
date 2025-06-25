<?php
require_once 'session.php';
include 'db.php';

// حماية الأدمن
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

// جلب التصنيفات
$category_result = mysqli_query($conn, "SELECT * FROM categories");
$sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $name = trim($_POST['name']);
        $price = (float)$_POST['price'];
        $discount = isset($_POST['discount']) ? (float)$_POST['discount'] : 0;
        $description = trim($_POST['description']);
        $category_id = (int)$_POST['category_id'];

    $allowedExts = ['jpg', 'jpeg', 'png'];
    $allowedTypes = ['image/jpeg', 'image/png'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    $imageFile = $_FILES['image'];
    $ext = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
    $mime = mime_content_type($imageFile['tmp_name']);

    if ($imageFile['size'] > $maxSize) {
        $error = "❌ Image exceeds size limit.";
    } elseif (!in_array($ext, $allowedExts) || !in_array($mime, $allowedTypes)) {
        $error = "❌ Invalid image type.";
    } else {
        $base = preg_replace('/[^A-Za-z0-9_-]/', '', pathinfo($imageFile['name'], PATHINFO_FILENAME));
        $newName = $base . '_' . time() . '.' . $ext;
        if (move_uploaded_file($imageFile['tmp_name'], __DIR__ . "/uploads/" . $newName)) {
            $stmt = mysqli_prepare($conn, "INSERT INTO products (name, price, discount, description, image, category_id) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sddssi", $name, $price, $discount, $description, $newName, $category_id);
            if (mysqli_stmt_execute($stmt)) {
                $pid = mysqli_insert_id($conn);
                foreach ($_POST['size_qty'] as $size => $qty) {
                    $qty = (int)$qty;
                    $size_stmt = mysqli_prepare($conn, "INSERT INTO product_sizes (product_id, size, quantity) VALUES (?, ?, ?)");
                    mysqli_stmt_bind_param($size_stmt, "isi", $pid, $size, $qty);
                    mysqli_stmt_execute($size_stmt);
                }
                $success = "✅ Product added successfully.";
            } else {
                $error = "❌ Failed to add product.";
            }
        } else {
            $error = "❌ Image upload failed.";
        }
    }
}
}
?>

<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4 text-center">➕ Add New Product</h2>

    <?php if ($success): ?>
        <div class="alert alert-success text-center"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card p-4 shadow mx-auto" style="max-width: 600px;">
        <form action="add.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label class="form-label">Product Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price ($):</label>
                <input type="number" name="price" step="0.01" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Discount (%):</label>
                <input type="number" name="discount" step="0.01" class="form-control" value="0">
            </div>

            <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Category:</label>
                <select name="category_id" class="form-select" required>
                    <option value="" disabled selected>Choose category</option>
                    <?php while ($cat = mysqli_fetch_assoc($category_result)) { ?>
                        <option value="<?php echo htmlspecialchars($cat['id'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Image:</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stock Quantity per Size:</label>
                <div class="row">
                    <?php foreach ($sizes as $sz): ?>
                        <div class="col-6 mb-2">
                            <label><?php echo $sz; ?>:</label>
                            <input type="number" name="size_qty[<?php echo $sz; ?>]" class="form-control" min="0" value="0" required>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success w-100">Add Product ✅</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
