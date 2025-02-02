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


// 📌 Kode untuk mengirim data ke database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require './src/config/connection.php'; // Pastikan koneksi database ada

    $category_id = $_POST['category_id'];
    $name_product = $_POST['name_product'];
    $price_product = $_POST['price_product'];
    $description_product = $_POST['description_product'];

    // Upload File
    if (isset($_FILES['icon_product']) && $_FILES['icon_product']['error'] === 0) {
        $icon_name = basename($_FILES['icon_product']['name']);
        $icon_tmp = $_FILES['icon_product']['tmp_name'];
        $upload_dir = "./src/assets/storage/";

        // Pastikan folder storage ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Simpan hanya nama file, bukan path lengkap
        $icon_destination = $upload_dir . $icon_name;

        // Validasi apakah file adalah gambar
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($icon_tmp);

        if (!in_array($file_type, $allowed_types)) {
            echo "<script>alert('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.'); window.history.back();</script>";
            exit();
        }

        if (move_uploaded_file($icon_tmp, $icon_destination)) {
            // Simpan hanya nama file di database
            $sql = "INSERT INTO product (category_id, name_product, price_product, description_product, icon_product, created_at, update_at)
                    VALUES (?, ?, ?, ?, ?, NOW(), NOW())";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isdss", $category_id, $name_product, $price_product, $description_product, $icon_name);

            if ($stmt->execute()) {
                echo "<script>alert('Product added successfully'); window.location.href='product.php';</script>";
            } else {
                echo "<script>alert('Error adding product'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('File upload for icon product failed'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No file uploaded'); window.history.back();</script>";
    }
}



// 📌 Ambil total product dari tabel product
$query_product = "SELECT COUNT(id) AS total_product FROM product";
$result_product = mysqli_query($conn, $query_product);
$row_product = mysqli_fetch_assoc($result_product);
$total_product = $row_product['total_product'];


// 📌 Ambil total price dari tabel product
$query_price = "SELECT SUM(price_product) AS total_price FROM product";
$result_price = mysqli_query($conn, $query_price);
$row_price = mysqli_fetch_assoc($result_price);
$total_price = $row_price['total_price'] ?? 0;
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
            Selamat datang, <?= htmlspecialchars($user['username']) ?>, di Product Area!
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
                                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                        <select id="category_id" name="category_id" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                            <option value="">Select Category</option>
                                            <?php
                                            require './src/config/connection.php'; // Koneksi database

                                            $result = $conn->query("SELECT id, name FROM categories"); // Sesuaikan nama tabel
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>No Categories Available</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- Product Name -->
                                    <div>
                                        <label for="name_product" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Name</label>
                                        <input type="text" id="name_product" name="name_product" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                    <!-- Price -->
                                    <div>
                                        <label for="price_product" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                                        <input type="number" id="price_product" name="price_product" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                    <!-- Description -->
                                    <div>
                                        <label for="description_product" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                        <textarea id="description_product" name="description_product" rows="3" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"></textarea>
                                    </div>
                                    <!-- Icon Product -->
                                    <div>
                                        <label for="icon_product" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Icon</label>
                                        <input type="file" id="icon_product" name="icon_product" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add Product</button>
                            </div>
                        </form>
                    </div>

                    <!-- Card Right -->
                    <div class="relative lg:row-span-2 space-y-4">
                        <!-- Card 1: Information card -->
                        <div class="py-3 px-5 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                            <div class="flex items-center justify-left space-x-5">
                                <h1 class="text-2xl font-bold text-gray-100">Information Card</h1>
                                <img src="./src/assets/favicon/logo.png" class="w-16 text-gray-100" alt="">
                            </div>
                            <p class="text-md font-base text-gray-100">
                                The 2 cards below are a card that calculates the total of all in the product area.
                                So it is specifically for calculating only and cannot be used for anything else.
                                If you want to see the product, you can see it at the bottom!
                            </p>
                        </div>

                        <!-- Card 2: Total Product -->
                        <div class="w-full max-w-full mx-auto">
                            <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-2xl font-bold text-gray-100">Total Product</h2>
                                    <div class="flex justify-between items-center space-x-0.5">
                                        <div class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                            <i class="fa-solid fa-box w-5 text-gray-900 ml-1.5"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-4 text-5xl font-extrabold text-gray-100"><?php echo $total_product; ?></h1>
                            </div>
                        </div>

                        <!-- Card 3: Total Price -->
                        <div class="w-full max-w-full mx-auto">
                            <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-2xl font-bold text-gray-100">Total Price</h2>
                                    <div class="flex justify-between items-center space-x-0.5">
                                        <div class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                            <i class="fa-solid fa-sack-dollar w-5 text-gray-900 ml-1"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-4 text-5xl font-extrabold text-gray-100"><?php echo isset($total_price) ? "Rp. " . number_format($total_price, 0, ',', '.') : "Rp. 0"; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- grid card for product -->
                <div class="space-y-4">
                    <!-- title -->
                    <div class="flex flex-col md:flex-row px-4 md:px-10 py-3 border items-center justify-center space-y-2 md:space-y-0 md:space-x-2 border-gray-700 rounded-lg bg-gray-800">
                        <h1 class="text-xl md:text-3xl font-bold uppercase tracking-wide text-gray-100 text-center md:text-left">
                            <i class="fa-solid fa-arrow-down w-5 text-gray-100"></i>
                            Product Card Area
                            <i class="fa-solid fa-arrow-down w-5 text-gray-100"></i>
                        </h1>
                    </div>

                    <!-- Grid Card -->
                    <div class="relative">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Looping Produk -->
                            <?php
                            include "./src/config/connection.php";
                            $sql = mysqli_query($conn, "SELECT id, category_id, name_product, price_product, description_product, icon_product, created_at FROM product");
                            while ($data = mysqli_fetch_array($sql)) {
                                $category_name = "Unknown";
                                $category_id = $data['category_id'];

                                if (!empty($category_id)) {
                                    $categoryQuery = mysqli_query($conn, "SELECT name FROM categories WHERE id = $category_id");
                                    if ($categoryResult = mysqli_fetch_assoc($categoryQuery)) {
                                        $category_name = $categoryResult['name'];
                                    }
                                }
                            ?>
                                <div class="w-full max-w-full mx-auto">
                                    <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                        <div class="space-y-2">
                                            <!-- Sub title top -->
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-sm font-base text-gray-100">
                                                    Created At: <?= date('l, jS Y', strtotime($data['created_at'])) ?>
                                                </h4>
                                                <div class="flex items-center justify-between space-x-1">
                                                    <a href="edit_product.php?id=<?= $data['id'] ?>"
                                                        class="cursor-pointer flex items-center justify-center w-8 h-8 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                                        <i class="fa-solid fa-pencil w-5 text-gray-900 ml-1"></i>
                                                    </a>
                                                    <!-- Button Delete -->
                                                    <a href="delete_product.php?id=<?= $data['id'] ?>"
                                                        onclick="return confirm('Are you sure you want to delete this product?');"
                                                        class="cursor-pointer flex items-center justify-center w-8 h-8 border border-red-500 rounded-full shadow-lg bg-gradient-to-l from-red-200 via-red-400 to-red-500 hover:bg-gradient-to-br">
                                                        <i class="fa-solid fa-trash w-5 text-gray-900 ml-1.5"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Main title -->
                                            <div class="flex flex-col items-start space-y-3">
                                                <div class="flex items-center space-x-2">
                                                    <img src="./src/assets/storage/<?= $data['icon_product'] ?>"
                                                        class="w-10 h-10" alt="Product Image">
                                                    <span class="text-2xl font-bold text-gray-100">
                                                        <?= $data['name_product'] ?>
                                                    </span>
                                                </div>
                                                <p class="text-md font-semibold text-gray-100">
                                                    <?= htmlspecialchars(mb_substr($data['description_product'], 0, 10)) . '...'; ?>
                                                </p>
                                            </div>

                                            <hr class="h-px my-2 border-1 border-dashed bg-gray-700">

                                            <!-- Sub title bottom -->
                                            <div class="flex items-center justify-between">
                                                <h2 class="text-md font-bold text-gray-100">
                                                    Category: <?= htmlspecialchars($category_name) ?>
                                                </h2>
                                                <h2 class="text-md font-bold text-gray-100">
                                                    Rp. <?= number_format($data['price_product'], 0, ',', '.') ?>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <!-- <div class="flex justify-center space-x-4 mt-6">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="px-10 py-3 text-gray-100 font-bold border border-gray-700 rounded-lg bg-gray-800 hover:bg-gray-600">
                                Previous
                            </a>
                        <?php endif; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="px-10 py-3 text-gray-100 font-bold border border-gray-700 rounded-lg bg-gray-800 hover:bg-gray-600">
                                Next
                            </a>
                        <?php endif; ?>
                    </div> -->
                </div>

            </div>
        </div>
    </div>
</main>