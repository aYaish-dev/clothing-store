<?php
session_start();
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
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        // إدخال المنتج في جدول products
        $sql = "INSERT INTO products (name, price, description, image, category_id)
                VALUES ('$name', '$price', '$description', '$image', '$category_id')";

        if (mysqli_query($conn, $sql)) {
            $product_id = mysqli_insert_id($conn); // الحصول على ID المنتج الجديد

            // إدخال الكمية لكل مقاس في جدول product_sizes
            foreach ($sizes as $size) {
                $qty = (int)($_POST['size_qty'][$size] ?? 0);
                $stmt = mysqli_prepare($conn, "INSERT INTO product_sizes (product_id, size, quantity) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "isi", $product_id, $size, $qty);
                mysqli_stmt_execute($stmt);
            }

            $success = "✅ Product added successfully with sizes!";
        } else {
            $error = "❌ Database error: " . mysqli_error($conn);
        }
    } else {
        $error = "❌ Image upload failed.";
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
            <div class="mb-3">
                <label class="form-label">Product Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price ($):</label>
                <input type="number" name="price" step="0.01" class="form-control" required>
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
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
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
