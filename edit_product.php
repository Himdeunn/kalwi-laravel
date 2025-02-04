<?php
require_once './src/config/connection.php';
$pageTitle = "Kalwi | Product";
session_start();


// 📌 Kode untuk memeriksa apakah user sudah login
if (!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit();
}


// 📌 Kode untuk mengambil user dengan tipe
$userid = $_SESSION['userid'];
$stmt = $conn->prepare("SELECT type, username FROM users WHERE id = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();


// 📌 Kode untuk memeriksa apakah user adalah tipe 3 (admin)
if (!$user || $user['type'] != 3) {
    header("location: home.php");
    exit();
}


// 📌 Kode untuk breadcrumb
$currentPage = basename($_SERVER['PHP_SELF'], ".php");

$breadcrumbs = [
    "admindashboard" => ["Admin Dashboard" => "admindashboard.php"],
    "referral" => ["Admin Dashboard" => "admindashboard.php", "Referral" => "referral.php"],
    "product" => ["Admin Dashboard" => "admindashboard.php", "Product" => "product.php"],
    "categories" => ["Admin Dashboard" => "admindashboard.php", "Categories" => "categories.php"],

    // Edit pages
    "edit_referral" => ["Admin Dashboard" => "admindashboard.php", "Referral" => "referral.php", "Edit Referral" => "#"],
    "edit_product" => ["Admin Dashboard" => "admindashboard.php", "Product" => "product.php", "Edit Product" => "#"],
    "edit_categories" => ["Admin Dashboard" => "admindashboard.php", "Categories" => "categories.php", "Edit Categories" => "#"],
];

$breadcrumbTrail = isset($breadcrumbs[$currentPage]) ? $breadcrumbs[$currentPage] : [];


// 📌 Kode untuk mengambil name pada table categories dan mengambil id untuk product di table product
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: product.php");
    exit();
}
$product_id = intval($_GET['id']);

// Ambil data produk berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

// Jika produk tidak ditemukan, kembalikan ke halaman produk
if (!$product) {
    header("location: product.php");
    exit();
}

// Ambil nama kategori dari tabel categories berdasarkan category_id dari produk
$category_id = $product['category_id'];
$stmt = $conn->prepare("SELECT name AS name_categories FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
$stmt->close();

// Jika kategori ditemukan, ambil nama kategorinya
$category_name = $category ? $category['name_categories'] : "Kategori tidak ditemukan";


// Proses update produk jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id    = $_POST['category_id'];
    $name_product   = $_POST['name_product'];
    $real_price_product  = $_POST['real_price_product'];
    $discount_price_product  = $_POST['discount_price_product'];
    $description_product = $_POST['description_product'];
    $command_product = $_POST['command_product'];

    // Jika ada file baru yang diunggah, proses upload file
    if (isset($_FILES['icon_product']) && $_FILES['icon_product']['error'] === 0) {
        $icon_name = basename($_FILES['icon_product']['name']); // Ambil nama file
        $icon_tmp  = $_FILES['icon_product']['tmp_name'];
        $upload_dir = "./src/assets/storage/";
        $icon_destination = $upload_dir . $icon_name; // Path lengkap

        if (move_uploaded_file($icon_tmp, $icon_destination)) {
            // Simpan hanya nama file, bukan path lengkap
            $updateQuery = "UPDATE product SET category_id = ?, name_product = ?, real_price_product = ?, discount_price_product = ?, description_product = ?, icon_product = ?, command_product = ?, update_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("isddssii", $category_id, $name_product, $real_price_product, $discount_price_product, $description_product, $icon_name, $command_product, $product_id);
        } else {
            echo "<script>alert('File upload failed'); window.history.back();</script>";
            exit();
        }
    } else {
        // Jika tidak ada file baru, update field lainnya saja      
        $updateQuery = "UPDATE product SET category_id = ?, name_product = ?, real_price_product = ?, discount_price_product = ?, description_product = ?, command_product = ?, update_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("isddssi", $category_id, $name_product, $real_price_product, $discount_price_product, $description_product, $command_product, $product_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully'); window.location.href='product.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating product'); window.history.back();</script>";
    }

    $stmt->close();
}

// Ambil daftar kategori untuk dropdown
$categoriesResult = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
?>

<?php
// Layout dan komponen lainnya
include_once './src/layouts/header.php';
include_once './src/layouts/footer.php';
include_once './src/components/navbar_admindashboard.php';
?>

<main class="px-5 py-14 sm:px-5 md:px-9 lg:px-10">
    <div class="text-gray-200 mx-auto max-w-1xl lg:max-w-7xl space-y-5">

        <!-- Greeting User -->
        <h1 class="mx-auto mt-2 max-w-screen-lg text-center text-4xl mb-10 font-semibold tracking-tight text-balance text-gray-200 sm:text-7xl">
            Selamat datang, <?= htmlspecialchars($user['username']) ?>, di Edit Product Area!
        </h1>

        <div class="mx-auto max-w-2xl lg:max-w-7xl">
            <div class="mt-10 sm:mt-16 space-y-5">
                <!-- Breadcrumb -->
                <nav class="flex items-center px-4 py-3 text-gray-400 border border-gray-700 rounded-lg bg-gray-800 md:px-10 md:py-5 overflow-x-auto" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <!-- Home -->
                        <li class="inline-flex items-center">
                            <a href="home.php" class="inline-flex items-center text-sm md:text-lg font-medium text-gray-400 hover:text-white">
                                <svg class="w-5 h-5 md:w-6 md:h-6 me-1.5 md:me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                </svg>
                                Home
                            </a>
                        </li>

                        <!-- Dynamic Breadcrumb -->
                        <?php
                        $totalItems = count($breadcrumbTrail);
                        $counter = 0;

                        foreach ($breadcrumbTrail as $name => $link):
                            $counter++;
                        ?>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 block w-4 h-4 mx-1 text-gray-400 md:w-5 md:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <?php if ($counter < $totalItems): ?>
                                        <a href="<?= htmlspecialchars($link) ?>" class="ms-1 text-sm md:text-lg font-medium text-gray-400 hover:text-white md:ms-2"><?= htmlspecialchars($name) ?></a>
                                    <?php else: ?>
                                        <span class="ms-1 text-sm md:text-lg font-medium text-gray-500 md:ms-2"><?= htmlspecialchars($name) ?></span>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </nav>

                <!-- grid card -->
                <div class="grid gap-4 lg:grid-cols-2 lg:grid-rows-2">
                    <!-- Card left -->
                    <div class="relative border-2 border-gray-700 lg:row-span-2 rounded-lg bg-gray-800 p-2 sm:p-6">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200 dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Add New Product</h3>
                        </div>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="p-4 md:p-5 space-y-4">
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Category -->
                                    <div>
                                        <label for="category_id" class="block text-sm font-medium text-gray-300">Category</label>
                                        <select id="category_id" name="category_id" required class="block w-full p-2 border border-gray-300 rounded-lg bg-gray-700 text-gray-300">
                                            <option value="">Select Category</option>
                                            <?php while ($cat = $categoriesResult->fetch_assoc()): ?>
                                                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $product['category_id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <!-- Product Name -->
                                    <div>
                                        <label for="name_product" class="block text-sm font-medium text-gray-300">Product Name</label>
                                        <input type="text" id="name_product" name="name_product" required value="<?= htmlspecialchars($product['name_product']) ?>" class="block w-full p-2 border border-gray-300 rounded-lg bg-gray-700 text-gray-300">
                                    </div>
                                    <!-- Real Price -->
                                    <div>
                                        <label for="real_price_product" class="block text-sm font-medium text-gray-300">Real Price</label>
                                        <input type="number" id="real_price_product" name="real_price_product" required value="<?= htmlspecialchars($product['real_price_product']) ?>" class="block w-full p-2 border border-gray-300 rounded-lg bg-gray-700 text-gray-300">
                                    </div>
                                    <!-- Discount Price -->
                                    <div>
                                        <label for="discount_price_product" class="block text-sm font-medium text-gray-300">Discount Price</label>
                                        <input type="number" id="discount_price_product" name="discount_price_product" required value="<?= htmlspecialchars($product['discount_price_product']) ?>" class="block w-full p-2 border border-gray-300 rounded-lg bg-gray-700 text-gray-300">
                                    </div>
                                    <!-- Command Product -->
                                    <div>
                                        <label for="command_product" class="block text-sm font-medium text-gray-300">Command Product</label>
                                        <input type="text" id="command_product" name="command_product" required value="<?= htmlspecialchars($product['command_product']) ?>" class="block w-full p-2 border border-gray-300 rounded-lg bg-gray-700 text-gray-300">
                                    </div>
                                    <!-- Description -->
                                    <div>
                                        <label for="description_product" class="block text-sm font-medium text-gray-300">Description</label>
                                        <textarea id="description_product" name="description_product" rows="3" required class="block w-full p-2 border border-gray-300 rounded-lg bg-gray-700 text-gray-300"><?= htmlspecialchars($product['description_product']) ?></textarea>
                                    </div>
                                    <!-- Icon Product -->
                                    <div>
                                        <label for="icon_product" class="block text-sm font-medium text-gray-300">Product Icon</label>
                                        <input type="file" id="icon_product" name="icon_product" class="block w-full p-2 border border-gray-300 rounded-lg bg-gray-700 text-gray-300" aria-describedby="file_input_help">
                                        <p id="file_input_help" class="mt-1 text-sm text-gray-500">Leave empty to keep current icon.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save Product</button>
                            </div>
                        </form>
                    </div>

                    <!-- Card Right -->
                    <div class="relative lg:col-span-1 lg:row-span-2 space-y-4">
                        <!-- card product -->
                        <div class="w-full max-w-full mx-auto">
                            <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                <div class="space-y-2">
                                    <!-- Sub title top -->
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-base text-gray-100">
                                            Created At: <?= date('l, jS Y', strtotime($product['created_at'])) ?>
                                        </h4>
                                        <a href="edit_product.php?id=<?= $product['id'] ?>"
                                            class="cursor-pointer flex items-center justify-center w-8 h-8 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                            <i class="fa-solid fa-pencil w-5 text-gray-900 ml-1"></i>
                                        </a>
                                    </div>

                                    <!-- Main title -->
                                    <div class="flex flex-col items-start space-y-3">
                                        <div class="flex items-center space-x-2">
                                            <img src="./src/assets/storage/<?= $product['icon_product'] ?>"
                                                class="w-10 h-10" alt="Product Image">
                                            <span class="text-2xl font-bold text-gray-100">
                                                <?= $product['name_product'] ?>
                                            </span>
                                        </div>
                                        <h1 class="bg-gray-700 w-full px-5 font-mono text-center border-gray-600 border-2">
                                            <?= $product['command_product'] ?>
                                        </h1>
                                        <p class="text-md font-semibold text-gray-100">
                                            <?= htmlspecialchars(mb_substr($product['description_product'], 0, 10)) . '...'; ?>
                                        </p>
                                    </div>

                                    <hr class="h-px my-2 border-1 border-dashed bg-gray-700">

                                    <!-- Sub title bottom -->
                                    <div class="flex space-y-3 flex-col items-left">
                                        <h2 class="text-md font-bold text-gray-100">
                                            Category: <?= htmlspecialchars($category_name) ?>
                                        </h2>
                                        <div class="flex items-center justify-between">
                                            <h2 class="text-md line-through font-bold text-red-500">
                                                <?php echo isset($product['real_price_product']) ? "Rp. " . number_format($product['real_price_product'], 0, ',', '.') : "Rp. 0"; ?>
                                            </h2>
                                            <h2 class="text-md font-bold text-gray-100">
                                                <?php echo isset($product['discount_price_product']) ? "Rp. " . number_format($product['discount_price_product'], 0, ',', '.') : "Rp. 0"; ?>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- card information -->
                        <div class="w-full max-w-full mx-auto">
                            <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                <div class="space-y-2">
                                    <h1 class="text-2xl font-bold tracking-wide text-gray-100">
                                        Information Before Edit Product!
                                    </h1>
                                    <hr class="h-px my-5 border-1 border-dashed bg-gray-700">
                                    <p class="text-semibold text-lg tracking-wide text-gray-300">
                                        You have to make sure first what you want to edit this product, so before editing think first.
                                        For the product icon, if you want to change it, please do so and if you don't want to change it, please do so.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>