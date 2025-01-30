<?php
include './src/config/connection.php';
$pageTitle = "Kalwi | Admin Dashboard";
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['userid'])) {
    header("location:login.php");
    exit();
}

// 📌 Ambil total produk yang terjual dari users.purchase_product
$query_sold = "SELECT COUNT(purchase_product) AS total_sold FROM users WHERE purchase_product IS NOT NULL";
$result_sold = mysqli_query($conn, $query_sold);
$row_sold = mysqli_fetch_assoc($result_sold);
$total_sold = $row_sold['total_sold'];

// 📌 Ambil total kategori dari tabel categories
$query_categories = "SELECT COUNT(id) AS total_categories FROM categories";
$result_categories = mysqli_query($conn, $query_categories);
$row_categories = mysqli_fetch_assoc($result_categories);
$total_categories = $row_categories['total_categories'];

// 📌 Ambil total produk dari tabel product
$query_products = "SELECT COUNT(id) AS total_products FROM product";
$result_products = mysqli_query($conn, $query_products);
$row_products = mysqli_fetch_assoc($result_products);
$total_products = $row_products['total_products'];

// 📌 Ambil total user dari tabel users
$query_users = "SELECT COUNT(id) AS total_users FROM users";
$result_users = mysqli_query($conn, $query_users);
$row_users = mysqli_fetch_assoc($result_users);
$total_users = $row_users['total_users'];

// ==============================================
// 📌 KODE TABLE PRODUCT (Dari Modal Product)
// ==============================================

// Tentukan jumlah produk per halaman
$limit = 5;

// Ambil nomor halaman dari parameter URL (default: halaman 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 📌 Ambil produk dengan daftar pembeli + kategori
$query_products_table = "
    SELECT product.*, 
           categories.name AS category_name, 
           categories.slug AS category_slug, 
           GROUP_CONCAT(users.username SEPARATOR ', ') AS buyers
    FROM product
    LEFT JOIN categories ON product.category_id = categories.id
    LEFT JOIN users ON users.purchase_product = product.id
    GROUP BY product.id
    LIMIT $limit OFFSET $offset
";
$result_products_table = mysqli_query($conn, $query_products_table);

// 📌 Hitung total produk untuk pagination
$total_query_products_table = "SELECT COUNT(DISTINCT id) AS total FROM product";
$total_result_products_table = mysqli_query($conn, $total_query_products_table);
$total_row_products_table = mysqli_fetch_assoc($total_result_products_table);
$total_products_table = $total_row_products_table['total'];

// 📌 Hitung total halaman
$total_pages_table = ceil($total_products_table / $limit);
?>


<?php
// layout dan komponen lainnya
require_once './src/layouts/header.php';
require_once './src/layouts/footer.php';
require_once './src/components/navbar_admindashboard.php';
require_once './src/components/modalAddProduct.php';
require_once './src/components/modalAddCategories.php';
?>

<main class="px-5 py-14 sm:px-6 md:px-9 lg:px-10">
    <div class="text-gray-200 mx-auto max-w-1xl lg:max-w-7xl space-y-5">
        <!-- Greeting User -->
        <h1 class="mx-auto mt-2 max-w-screen-lg text-center text-4xl mb-10 font-semibold tracking-tight text-balance text-gray-200 sm:text-7xl">
            Selamat datang, <?= $_SESSION['username'] ?>, di Dashboard!
        </h1>

        <div class="text-gray-200 max-w-1xl lg:max-w-screen space-y-5">
            <!-- Grid untuk Card -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
                <!-- Card 1: Total Produk Terjual -->
                <div class="w-full max-w-full mx-auto">
                    <div class="p-4 rounded-lg shadow-lg bg-gradient-to-r from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-900">Total User</h2>
                            <button class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                <i class="fa-solid fa-user w-5 text-gray-900"></i>
                            </button>
                        </div>
                        <h1 class="mt-4 text-5xl font-extrabold text-gray-900"><?php echo $total_users; ?></h1>
                    </div>
                </div>

                <!-- Card 2: Total Produk Terjual -->
                <div class="w-full max-w-full mx-auto">
                    <div class="p-4 rounded-lg shadow-lg bg-gradient-to-r from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-900">Products Sold</h2>
                            <button class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                <i class="fa-solid fa-chart-line w-5 text-gray-900"></i>
                            </button>
                        </div>
                        <h1 class="mt-4 text-5xl font-extrabold text-gray-900"><?php echo $total_sold; ?></h1>
                    </div>
                </div>

                <!-- Card 3: Total Kategori -->
                <div class="w-full max-w-full mx-auto">
                    <div class="p-4 rounded-lg shadow-lg bg-gradient-to-r from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-900">Total Categories</h2>
                            <div class="flex justify-between items-center space-x-0.5">
                                <button data-modal-target="modalAddCategories" data-modal-toggle="modalAddCategories" class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                    <i class="fa-solid fa-plus w-5 text-gray-900"></i>
                                </button>
                                <button class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                    <i class="fa-solid fa-layer-group w-5 text-gray-900"></i>
                                </button>
                            </div>
                        </div>
                        <h1 class="mt-4 text-5xl font-extrabold text-gray-900"><?php echo $total_categories; ?></h1>
                    </div>
                </div>

                <!-- Card 4: Total Produk -->
                <div class="w-full max-w-full mx-auto">
                    <div class="p-4 rounded-lg shadow-lg bg-gradient-to-r from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-900">Total Products</h2>
                            <div class="flex justify-between items-center space-x-0.5">
                                <button data-modal-target="modalAddProduct" data-modal-toggle="modalAddProduct" class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                    <i class="fa-solid fa-plus w-5 text-gray-900"></i>
                                </button>
                                <button class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                    <i class="fa-solid fa-box w-5 text-gray-900"></i>
                                </button>
                            </div>
                        </div>
                        <h1 class="mt-4 text-5xl font-extrabold text-gray-900"><?php echo $total_products; ?></h1>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-2">
                <!-- card 1 -->
                <div class="card-sm rounded-xl bg-gray-800 bg-opacity-50 max-w-screen border-3 border-gray-700 rounded-[calc(var(--radius-lg)+1px)] lg:rounded-[calc(2rem+1px)] p-6">
                    <!-- Table -->
                    <div class="relative overflow-x-auto rounded-[calc(var(--radius-lg)+1px)] lg:rounded-[calc(1rem)] bg-gray-800">
                        <table class="w-full text-sm text-left border-2 border-gray-700 text-gray-400">
                            <thead class="text-xs uppercase bg-gray-700 text-gray-300">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Product Name</th>
                                    <th scope="col" class="px-6 py-3">Category</th>
                                    <th scope="col" class="px-6 py-3">Price</th>
                                    <th scope="col" class="px-6 py-3">Description</th>
                                    <th scope="col" class="px-6 py-3">Purchased By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result_products_table)): ?>
                                    <tr class="bg-gray-800 border-b border-gray-700">
                                        <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                            <?php echo htmlspecialchars($row['name_product']); ?>
                                        </th>
                                        <td class="px-6 py-4">
                                            <?php echo htmlspecialchars($row['category_name'] ?? 'No Category'); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            $<?php echo number_format($row['price_product'], 2); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo substr(htmlspecialchars($row['description_product']), 0, 5) . '...'; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo $row['buyers'] ? htmlspecialchars($row['buyers']) : 'No Buyer'; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
    
                    <!-- Pagination -->
                    <div class="flex justify-center mt-4">
                        <?php if ($total_pages_table > 1): ?>
                            <nav class="inline-flex rounded-md shadow">
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?>"
                                        class="px-4 py-2 bg-gray-700 text-gray-300 hover:bg-gray-600 border border-gray-600 rounded-l-md">
                                        Previous
                                    </a>
                                <?php endif; ?>
    
                                <?php for ($i = 1; $i <= $total_pages_table; $i++): ?>
                                    <a href="?page=<?php echo $i; ?>"
                                        class="px-4 py-2 <?php echo ($i == $page) ? 'bg-blue-500 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'; ?> border border-gray-600">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
    
                                <?php if ($page < $total_pages_table): ?>
                                    <a href="?page=<?php echo $page + 1; ?>"
                                        class="px-4 py-2 bg-gray-700 text-gray-300 hover:bg-gray-600 border border-gray-600 rounded-r-md">
                                        Next
                                    </a>
                                <?php endif; ?>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

</main>