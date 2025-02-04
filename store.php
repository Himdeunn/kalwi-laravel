<?php
include './src/config/connection.php';
$pageTitle = "Kalwi | Store";
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit();
}

$userid = $_SESSION['userid'];
$success = $_SESSION['success'] ?? false;
$error = $_SESSION['error'] ?? "";

// Hapus sesi setelah diakses
unset($_SESSION['success'], $_SESSION['error']);

// Ambil data pengguna
$stmt = $conn->prepare("SELECT password, type FROM users WHERE id = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Ambil filter dari URL
$activeFilter = $_GET['filter'] ?? 'all';

// Query untuk mengambil semua filter unik yang ada di tabel "product"
$filterQuery = mysqli_query($conn, "SELECT DISTINCT filter FROM product");
$availableFilters = [];

while ($row = mysqli_fetch_assoc($filterQuery)) {
    $availableFilters[] = $row['filter'];
}

// Pastikan hanya filter yang valid yang dipilih
if ($activeFilter !== 'all' && !in_array($activeFilter, $availableFilters)) {
    $activeFilter = 'all';
}

// Query produk dengan filter yang dipilih
$sql = ($activeFilter === 'all') ?
    mysqli_query($conn, "
        SELECT p.id, p.category_id, p.name_product, p.real_price_product, 
            p.discount_price_product, p.description_product, p.command_product, 
            p.icon_product, p.created_at, p.filter, c.name AS category_name
        FROM product p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC
    ") :
    mysqli_query($conn, "
        SELECT p.id, p.category_id, p.name_product, p.real_price_product, 
            p.discount_price_product, p.description_product, p.command_product, 
            p.icon_product, p.created_at, p.filter, c.name AS category_name
        FROM product p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.filter = '$activeFilter'
        ORDER BY p.created_at DESC
    ");



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Periksa apakah pengguna sudah login
    if (!isset($_SESSION['userid'])) {
        header("Location: login.php");
        exit();
    }

    $userid = $_SESSION['userid'];

    // Validasi input product_id
    if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
        $_SESSION['error'] = "Invalid product selection.";
        header("Location: store.php");
        exit();
    }

    $product_id = (int) $_POST['product_id'];
    $quantity = 1;
    $created_at = date('Y-m-d H:i:s');

    // Simpan ke dalam tabel cart
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $userid, $product_id, $quantity, $created_at);
    if ($stmt->execute()) {
        $_SESSION['success'] = true;
    } else {
        $_SESSION['error'] = "Failed to add product to cart.";
    }
    $stmt->close();

    // Redirect ke store.php setelah penyimpanan
    header("Location: store.php");
    exit();
}


function getCartCountFromDatabase($conn)
{
    if (!isset($_SESSION['userid'])) {
        return 0; // Jika user belum login, jumlah cart dianggap 0
    }

    $user_id = $_SESSION['userid']; // Ambil user ID dari session

    // Gunakan prepared statement untuk keamanan
    $sql = "SELECT COUNT(*) AS total_items FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row["total_items"];
    } else {
        return 0;
    }
}

// Panggil fungsi untuk mendapatkan jumlah produk dalam cart
$cartCount = getCartCountFromDatabase($conn);
?>


<?php
// layout dan komponen lainnya
include_once './src/layouts/header.php';
include_once './src/layouts/footer.php';
include_once './src/layouts/navbar_logout.php';
include_once './src/components/alertsAddToCart.php';
?>


<main class="px-5 py-14 sm:px-6 md:px-9 lg:px-10">
    <div class="text-gray-200 mx-auto max-w-1xl lg:max-w-7xl space-y-5">
        <!-- Greeting User -->
        <div class="mb-10">
            <h2 class="text-center tex-base text-lg sm:text-3xl font-semibold text-indigo-400">Selamat Menikmati dan Selamat Berbelanja di KalWi Store!</h2>
            <h1 class="mx-auto mt-2 max-w-screen-lg text-center text-4xl mb-5 font-semibold tracking-tight text-balance text-gray-200 sm:text-7xl">
                Selamat datang <?= $_SESSION['username'] ?>, di Store!
            </h1>
        </div>

        <div class="text-gray-200 max-w-1xl lg:max-w-screen space-y-3">
            <!-- Button Back & Cart -->
            <div class="flex items-center justify-between gap-4">
                <a href="home.php" class="py-3 px-12 mb-2 text-md font-semibold text-white focus:outline-none focus:ring-0 bg-gray-800 rounded-xl border border-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-6 h-6">
                        <path fill="currentColor" d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" />
                    </svg>
                </a>

                <div class="hidden sm:flex justify-center items-center py-3 px-4 w-full mb-2 focus:outline-none focus:ring-0 bg-gray-800 rounded-xl border border-gray-700">
                    <h2 class="tex-base text-lg font-semibold text-white">
                        Jika ada yang ingin ditanyakan, kalian bisa mengunjungi discord support kami di
                        <a href="" class="text-blue-500 underline">Discord Support</a>
                    </h2>
                </div>
                <a href="cart.php" class="relative inline-flex items-center py-3 px-12 mb-2 text-md font-semibold text-white focus:outline-none focus:ring-0 bg-gray-800 rounded-xl border border-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-6 h-6">
                        <path fill="currentColor" d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                    </svg>
                    <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -end-2 dark:border-gray-900"><?php echo $cartCount; ?></div>
                </a>
            </div>


            <!-- Card Product Navbar -->
            <div class="px-10 py-5 text-gray-400 border border-gray-700 rounded-lg bg-gray-800">
                <div class="hidden md:flex items-center justify-between">
                    <ul class="flex space-x-6">
                        <li><a href="?filter=all" class="hover:text-white <?= ($activeFilter === 'all') ? 'text-white' : 'text-gray-400' ?>">All</a></li>
                        <?php foreach ($availableFilters as $filter) : ?>
                            <li>
                                <a href="?filter=<?= urlencode($filter) ?>" class="hover:text-white <?= ($activeFilter === $filter) ? 'text-white' : 'text-gray-400' ?>">
                                    <?= ucwords(str_replace('_', ' ', $filter)) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Dropdown for small screens -->
                <div class="md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-white">
                        Menu
                        <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <ul x-show="open" @click.away="open = false" class="mt-5 space-y-4">
                        <li><a href="?filter=all" class="block hover:text-white">All</a></li>
                        <?php foreach ($availableFilters as $filter) : ?>
                            <li>
                                <a href="?filter=<?= urlencode($filter) ?>" class="block hover:text-white">
                                    <?= ucwords(str_replace('_', ' ', $filter)) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>


            <!-- Card Product -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 py-5">
                <?php while ($data = mysqli_fetch_array($sql)) { ?>
                    <div class="w-full max-w-lg bg-gray-800 border border-gray-700 rounded-lg shadow-sm">
                        <img class="p-8" src="./src/assets/storage/<?= htmlspecialchars($data['icon_product']) ?>" alt="product image">
                        <div class="px-5 pb-5">
                            <div class="space-y-1">
                                <h5 class="text-3xl font-semibold tracking-tight text-white"><?= htmlspecialchars($data['name_product']) ?></h5>
                                <h5 class="text-sm mb-3 font-semibold tracking-tight text-white"><?= htmlspecialchars($data['category_name']) ?></h5>
                                <h5 class="text-xl font-semibold tracking-tight text-white"><?= htmlspecialchars($data['description_product']) ?></h5>
                            </div>

                            <!-- Harga Produk -->
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-left flex-col">
                                    <span class="text-md font-bold text-red-500 line-through">Rp. <?= number_format($data['real_price_product'], 0, ',', '.') ?></span>
                                    <span class="text-xl font-bold text-white">Rp. <?= number_format($data['discount_price_product'], 0, ',', '.') ?></span>
                                </div>
                                <!-- Button Add To Cart -->
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="<?= $data['id'] ?>">
                                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm p-3 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-6 h-6">
                                            <path fill="currentColor" d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>


        </div>
    </div>
</main>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if ($success): ?>
            let successAlert = document.getElementById("success-alert");
            successAlert.classList.remove("hidden");
            setTimeout(() => {
                successAlert.classList.add("hidden");
            }, 3000);
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            let errorAlert = document.getElementById("error-alert");
            errorAlert.classList.remove("hidden");
            setTimeout(() => {
                errorAlert.classList.add("hidden");
            }, 5000);
        <?php endif; ?>
    });
</script>