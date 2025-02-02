<?php
require_once './src/config/connection.php';
$pageTitle = "Kalwi | Referral";
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

// 📌 Kode untuk generate kode referal
function generateReferralCode($length = 6)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

// 📌 Kode untuk handle form pada left card
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code_number = strtoupper(trim($_POST['code_number']));
    $discount_percentage = intval($_POST['discount_percentage']);

    // Validasi discount percentage
    if ($discount_percentage < 0 || $discount_percentage > 100) {
        die("Discount percentage must be between 0 and 100.");
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO code_referral (code_number, discount_percentage, created_by, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sii", $code_number, $discount_percentage, $userid);

    if ($stmt->execute()) {
        echo "<script>alert('Referral code added successfully!'); window.location.href = 'referral.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
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

// 📌 Ambil total referal dari tabel code_referral
$query_referral = "SELECT COUNT(id) AS total_referral FROM code_referral";
$result_referral = mysqli_query($conn, $query_referral);
$row_referral = mysqli_fetch_assoc($result_referral);
$total_referral = $row_referral['total_referral'];

// 📌 Ambil total usage dari tabel code_referral
$query_usage = "SELECT SUM(usage_count) AS total_usage FROM code_referral";
$result_usage = mysqli_query($conn, $query_usage);
$row_usage = mysqli_fetch_assoc($result_usage);
$total_usage = $row_usage['total_usage'] ?? 0;

// 📌 Ambil total discount dari tabel code_referral
$query_discount = "SELECT LEFT(SUM(discount_percentage), 1) AS total_discount FROM code_referral";
$result_discount = mysqli_query($conn, $query_discount);
$row_discount = mysqli_fetch_assoc($result_discount);
$total_discount = $row_discount['total_discount'] ?? 0;

// 📌 Ambil total profit share dari tabel code_referral
$query_profit = "SELECT LEFT(SUM(profit_share), 1) AS total_profit FROM code_referral";
$result_profit = mysqli_query($conn, $query_profit);
$row_profit = mysqli_fetch_assoc($result_profit);
$total_profit = $row_profit['total_profit'] ?? 0;

// 📌 kode untuk pagination & referral card
$limit = 8; // Jumlah card per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT cr.*, u.username, SUBSTRING_INDEX(cr.discount_percentage, '.', 1) AS discount
        FROM code_referral cr
        INNER JOIN users u ON cr.created_by = u.id
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

$total_sql = "SELECT COUNT(*) AS total FROM code_referral";
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
            Selamat datang, <?= htmlspecialchars($user['username']) ?>, di Referral Area!
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
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Add New Referral</h3>
                        </div>
                        <form method="POST">
                            <div class="p-4 md:p-5 space-y-4">
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Referral Code -->
                                    <div>
                                        <label for="code_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referral Code</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="text" id="code_number" name="code_number" value="<?= generateReferralCode(); ?>" readonly required
                                                class="block w-full p-2 border border-gray-300 bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                            <button type="button" onclick="generateCode()" aria-label="Generate new referral code"
                                                class="cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2" viewBox="0 0 640 512">
                                                    <path fill="#ffffff" d="M274.9 34.3c-28.1-28.1-73.7-28.1-101.8 0L34.3 173.1c-28.1 28.1-28.1 73.7 0 101.8L173.1 413.7c28.1 28.1 73.7 28.1 101.8 0L413.7 274.9c28.1-28.1 28.1-73.7 0-101.8L274.9 34.3zM200 224a24 24 0 1 1 48 0 24 24 0 1 1 -48 0zM96 200a24 24 0 1 1 0 48 24 24 0 1 1 0-48zM224 376a24 24 0 1 1 0-48 24 24 0 1 1 0 48zM352 200a24 24 0 1 1 0 48 24 24 0 1 1 0-48zM224 120a24 24 0 1 1 0-48 24 24 0 1 1 0 48zm96 328c0 35.3 28.7 64 64 64l192 0c35.3 0 64-28.7 64-64l0-192c0-35.3-28.7-64-64-64l-114.3 0c11.6 36 3.1 77-25.4 105.5L320 413.8l0 34.2zM480 328a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Discount Percentage -->
                                    <div>
                                        <label for="discount_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Percentage</label>
                                        <input type="number" id="discount_percentage" name="discount_percentage" min="0" max="100" required
                                            class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                    <!-- Created By -->
                                    <div>
                                        <label for="created_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Created By</label>
                                        <input type="text" id="created_by" name="created_by" value="<?= htmlspecialchars($user['username']); ?>" readonly
                                            class="block w-full p-2 border border-gray-300 bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                </div>
                            </div>
                            <!-- Form Buttons -->
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-500 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 transition">
                                    Add Referral
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
                                The 4 cards below are a card that calculates the total of all in the referral area.
                                So it is specifically for calculating only and cannot be used for anything else.
                                If you want to see the referral code, you can see it at the bottom!
                            </p>
                        </div>
                        <!-- grid card -->
                        <div class="grid gap-4 lg:grid-cols-2 grid-cols-1">
                            <!-- Card 2: Total Referral -->
                            <div class="w-full max-w-full mx-auto">
                                <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-2xl font-bold text-gray-100">Total Referral</h2>
                                        <div class="flex justify-between items-center space-x-0.5">
                                            <div class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                                <i class="fa-solid fa-ticket w-5 text-gray-900 ml-0.5"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-4 text-5xl font-extrabold text-gray-100"><?php echo $total_referral; ?></h1>
                                </div>
                            </div>

                            <!-- Card 3: Total Usage -->
                            <div class="w-full max-w-full mx-auto">
                                <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-2xl font-bold text-gray-100">Total Usage</h2>
                                        <div class="flex justify-between items-center space-x-0.5">
                                            <div class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                                <i class="fa-solid fa-handshake w-5 text-gray-900"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-4 text-5xl font-extrabold text-gray-100"><?php echo $total_usage; ?></h1>
                                </div>
                            </div>

                            <!-- Card 4: Total Discount -->
                            <div class="w-full max-w-full mx-auto">
                                <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-2xl font-bold text-gray-100">Total Discount</h2>
                                        <div class="flex justify-between items-center space-x-0.5">
                                            <div class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                                <i class="fa-solid fa-tag w-5 text-gray-900 ml-1.5 mt-0.5"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-4 text-5xl font-extrabold text-gray-100"><?php echo $total_discount; ?>%</h1>
                                </div>
                            </div>

                            <!-- Card 5: Total Profit Share -->
                            <div class="w-full max-w-full mx-auto">
                                <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-2xl font-bold text-gray-100">Total Profit</h2>
                                        <div class="flex justify-between items-center space-x-0.5">
                                            <div class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                                <i class="fa-solid fa-money-bill w-5 text-gray-900 ml-0.5"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-4 text-5xl font-extrabold text-gray-100">Rp. <?php echo $total_profit; ?></h1>
                                </div>
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
                            Referral Card Area
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
                                            <!-- Sub title top -->
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-sm font-base text-gray-100">
                                                    Created By : <?= htmlspecialchars($row['username']) ?>
                                                </h4>
                                                <div class="flex items-center justify-between space-x-2">
                                                    <a href="edit_referral.php?id=<?= $row['id'] ?>"
                                                        class="cursor-pointer flex items-center justify-center w-8 h-8 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                                        <i class="fa-solid fa-pencil w-5 text-gray-900 ml-1"></i>
                                                    </a>
                                                    <!-- Button Delete Referral -->
                                                    <a href="delete_referral.php?id=<?= $row['id'] ?>"
                                                        onclick="return confirm('Are you sure you want to delete this referral?');"
                                                        class="cursor-pointer flex items-center justify-center w-8 h-8 border border-red-500 rounded-full shadow-lg bg-gradient-to-l from-red-200 via-red-400 to-red-500 hover:bg-gradient-to-br">
                                                        <i class="fa-solid fa-trash w-5 text-gray-900 ml-1.5"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- Main title -->
                                            <div class="flex items-center justify-between">
                                                <h1 class="text-sm font-base text-gray-100 flex items-center space-x-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 576 512">
                                                        <path fill="#ffffff" d="M64 64C28.7 64 0 92.7 0 128l0 64c0 8.8 7.4 15.7 15.7 18.6C34.5 217.1 48 235 48 256s-13.5 38.9-32.3 45.4C7.4 304.3 0 311.2 0 320l0 64c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-64c0-8.8-7.4-15.7-15.7-18.6C541.5 294.9 528 277 528 256s13.5-38.9 32.3-45.4c8.3-2.9 15.7-9.8 15.7-18.6l0-64c0-35.3-28.7-64-64-64L64 64zm64 112l0 160c0 8.8 7.2 16 16 16l288 0c8.8 0 16-7.2 16-16l0-160c0-8.8-7.2-16-16-16l-288 0c-8.8 0-16 7.2-16 16zM96 160c0-17.7 14.3-32 32-32l320 0c17.7 0 32 14.3 32 32l0 192c0 17.7-14.3 32-32 32l-320 0c-17.7 0-32-14.3-32-32l0-192z" />
                                                    </svg>
                                                    <span class="text-2xl font-bold text-gray-100">
                                                        <?= htmlspecialchars($row['code_number']) ?>
                                                    </span>
                                                </h1>
                                                <h3 class="font-base text-md text-gray-100">
                                                    <?= htmlspecialchars($row['discount']) ?>%
                                                </h3>
                                            </div>
                                            <hr class="h-px my-2 border-1 border-dashed bg-gray-700">
                                            <!-- Sub title bottom -->
                                            <div class="flex items-center justify-between">
                                                <h2 class="text-md font-bold text-gray-100">
                                                    <?= htmlspecialchars($row['usage_count']) ?> Uses
                                                </h2>
                                                <h2 class="text-md font-bold text-gray-100">
                                                    Rp. <?= htmlspecialchars($row['profit_share']) ?>
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
</main>

<!-- kode js untuk button acak kode referal -->
<script>
    function generateCode() {
        const length = 6;
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = '';

        for (let i = 0; i < length; i++) {
            code += characters.charAt(Math.floor(Math.random() * characters.length));
        }

        document.getElementById('code_number').value = code;
    }
</script>