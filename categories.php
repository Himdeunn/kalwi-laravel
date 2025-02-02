<?php
require_once './src/config/connection.php';
$pageTitle = "Kalwi | Categories";
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


// 📌 Kode untuk breadcrumb
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = trim($_POST['name']);
    $slug = trim($_POST['slug']);

    // Cek apakah kategori atau slug sudah ada
    $check = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
    $check->bind_param("s", $slug);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Slug already exists!'); window.history.back();</script>";
    } else {
        // Simpan ke database
        $sql = "INSERT INTO categories (name, slug, created_at, updated_at) VALUES (?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $category_name, $slug);

        if ($stmt->execute()) {
            echo "<script>alert('Category added successfully'); window.location.href='categories.php';</script>";
        } else {
            echo "<script>alert('Error adding category'); window.history.back();</script>";
        }
    }
}


// 📌 Ambil total name dari tabel categories
$query_categories = "SELECT COUNT(id) AS total_categories FROM categories";
$result_categories = mysqli_query($conn, $query_categories);
$row_categories = mysqli_fetch_assoc($result_categories);
$total_categories = $row_categories['total_categories'];


// 📌 kode untuk pagination & referral card
$limit = 8; // Jumlah card per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT c.name AS category_name, created_at
        FROM categories c
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

$total_sql = "SELECT COUNT(*) AS total FROM categories";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_data = $total_row['total'];
$total_pages = ceil($total_data / $limit);

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
            Selamat datang, <?= htmlspecialchars($user['username']) ?>, di Categories Area!
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
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Add New Categories</h3>
                        </div>
                        <form method="POST">
                            <div class="p-4 md:p-5 space-y-4">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category Name</label>
                                        <input type="text" id="name" name="name" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                    <div>
                                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                                        <input type="text" id="slug" name="slug" placeholder="This same like category name" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                </div>
                            </div>
                            <!-- Form Buttons -->
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-500 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 transition">
                                    Add Categories
                                </button>
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
                                The 4 cards below are a card that calculates the total of all in the categories area.
                                So it is specifically for calculating only and cannot be used for anything else.
                                If you want to see the categories, you can see it at the bottom!
                            </p>
                        </div>
                        <!-- Card 2: Total Name -->
                        <div class="w-full max-w-full mx-auto">
                            <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-2xl font-bold text-gray-100">Total Categories</h2>
                                    <div class="flex justify-between items-center space-x-0.5">
                                        <div class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                            <i class="fa-solid fa-layer-group w-5 text-gray-900 ml-0.5"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-4 text-5xl font-extrabold text-gray-100"><?php echo $total_categories; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- grid card for referral -->
                <div class="space-y-4">
                    <!-- title -->
                    <div class="flex flex-col md:flex-row px-4 md:px-10 py-3 border items-center justify-center space-y-2 md:space-y-0 md:space-x-2 border-gray-700 rounded-lg bg-gray-800">
                        <h1 class="text-xl md:text-3xl font-bold uppercase tracking-wide text-gray-100 text-center md:text-left">
                            <i class="fa-solid fa-arrow-down w-5 text-gray-100"></i>
                            Categories Card Area
                            <i class="fa-solid fa-arrow-down w-5 text-gray-100"></i>
                        </h1>
                    </div>
                    
                    <!-- grid card -->
                    <div class="relative">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                            <!-- card -->
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="w-full max-w-full mx-auto">
                                    <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                        <div class="space-y-2">
                                            <!-- Main title -->
                                            <div class="flex items-center justify-between">
                                                <h1 class="text-sm font-base text-gray-100 flex items-center space-x-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 576 512">
                                                        <path fill="#ffffff" d="M64 64C28.7 64 0 92.7 0 128l0 64c0 8.8 7.4 15.7 15.7 18.6C34.5 217.1 48 235 48 256s-13.5 38.9-32.3 45.4C7.4 304.3 0 311.2 0 320l0 64c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-64c0-8.8-7.4-15.7-15.7-18.6C541.5 294.9 528 277 528 256s13.5-38.9 32.3-45.4c8.3-2.9 15.7-9.8 15.7-18.6l0-64c0-35.3-28.7-64-64-64L64 64zm64 112l0 160c0 8.8 7.2 16 16 16l288 0c8.8 0 16-7.2 16-16l0-160c0-8.8-7.2-16-16-16l-288 0c-8.8 0-16 7.2-16 16zM96 160c0-17.7 14.3-32 32-32l320 0c17.7 0 32 14.3 32 32l0 192c0 17.7-14.3 32-32 32l-320 0c-17.7 0-32-14.3-32-32l0-192z" />
                                                    </svg>
                                                    <span class="text-2xl font-bold text-gray-100">
                                                        <?= htmlspecialchars($row['category_name']) ?>
                                                    </span>
                                                </h1>
                                            </div>
                                            <hr class="h-px my-2 border-1 border-dashed bg-gray-700">
                                            <!-- Additional Info -->
                                            <div class="flex items-center justify-between">
                                                <h2 class="text-md font-bold text-gray-100">
                                                    Created At: <?= date('l, jS Y', strtotime($row['created_at'])) ?>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center space-x-4 mt-6">
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
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
</main>