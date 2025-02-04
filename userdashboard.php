<?php
include './src/config/connection.php';
$pageTitle = "Kalwi | User Dashboard";
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit();
}

$userid = $_SESSION['userid'];
$success = false;
$error = "";

// 📌 Kode untuk memeriksa sesi sukses
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']); // Hapus sesi setelah diakses
}

// 📌 Kode untuk memeriksa sesi eror
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']); // Hapus sesi setelah diakses
}

// 📌 Kode untuk mengambil data users
$stmt = $conn->prepare("SELECT password, type FROM users WHERE id = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// 📌 Kode untuk memeriksa apakah user adalah tipe 3 (admin)
if (!$user || $user['type'] != 1) {
    header("location: home.php");
    exit();
}

// 📌 Kode untuk mengirim data ke table users untuk kolom password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Periksa apakah password saat ini cocok
    if (!password_verify($current_password, $user['password'])) {
        $_SESSION['error'] = "Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $_SESSION['error'] = "New passwords do not match.";
    } else {
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password di database
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $userid);
        if ($stmt->execute()) {
            $_SESSION['success'] = true;
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }

    // Redirect untuk menghindari form resubmission
    header("Location: userdashboard.php");
    exit();
}


// 📌 Kode untuk breadcrumb
$currentPage = basename($_SERVER['PHP_SELF'], ".php");

$breadcrumbs = [
    "userdashboard" => ["User Dashboard" => "userdashboard.php"],
    "profile" => ["User Dashboard" => "userdashboard.php", "Profile" => "profile.php"],
    "history" => ["User Dashboard" => "userdashboard.php", "History" => "history.php"],

    // Edit pages
    // "edit_referral" => ["User Dashboard" => "userdashboard.php", "Referral" => "referral.php", "Edit Referral" => "#"],
    // "edit_product" => ["User Dashboard" => "userdashboard.php", "Product" => "product.php", "Edit Product" => "#"],
    // "edit_categories" => ["User Dashboard" => "userdashboard.php", "Categories" => "categories.php", "Edit Categories" => "#"],
];

$breadcrumbTrail = isset($breadcrumbs[$currentPage]) ? $breadcrumbs[$currentPage] : [];


// 📌 Ambil saldo wallet dari tabel users sesuai user yang login dan tipe 1
$query_wallet = "SELECT wallet FROM users WHERE id = ? AND type = 1";
$stmt = $conn->prepare($query_wallet);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result_wallet = $stmt->get_result();
$row_wallet = $result_wallet->fetch_assoc();
$stmt->close();
$total_wallet = $row_wallet ? $row_wallet['wallet'] : 0;
?>

<?php
// layout dan komponen lainnya
include_once './src/layouts/header.php';
include_once './src/layouts/footer.php';
include_once './src/components/navbar_userdashboard.php';
include_once './src/components/alertsUpdatePassword.php';
?>

<main class="px-5 py-14 sm:px-6 md:px-9 lg:px-10">
    <div class="text-gray-200 mx-auto max-w-1xl lg:max-w-7xl space-y-5">
        <!-- Greeting User -->
        <h1 class="mx-auto mt-2 max-w-screen-lg text-center text-4xl mb-10 font-semibold tracking-tight text-balance text-gray-200 sm:text-7xl">
            Selamat datang, <?= $_SESSION['username'] ?>, di Dashboard!
        </h1>

        <div class="text-gray-200 max-w-1xl lg:max-w-screen space-y-5">
            <!-- Breadcrumb -->
            <nav class="flex px-10 py-5 text-gray-400 border border-gray-700 rounded-lg bg-gray-800" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <!-- Home -->
                    <li class="inline-flex items-center">
                        <a href="home.php" class="inline-flex items-center text-xl font-medium text-gray-400 hover:text-white">
                            <svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
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
                                <svg class="rtl:rotate-180 block w-4 h-4 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <?php if ($counter < $totalItems): ?>
                                    <a href="<?= htmlspecialchars($link) ?>" class="ms-1 text-xl font-medium text-gray-400 hover:text-white md:ms-2"><?= htmlspecialchars($name) ?></a>
                                <?php else: ?>
                                    <span class="ms-1 text-xl font-medium text-gray-500 md:ms-2"><?= htmlspecialchars($name) ?></span>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>

            <!-- Grid untuk Card -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- card left -->
                <div class="relative border-2 border-gray-700 lg:row-span-2 rounded-lg bg-gray-800 p-2 sm:p-6">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200 dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Latest History Buying</h3>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-5">
                        <!-- card 1 -->
                        <div class="w-full max-w-full mx-auto">
                            <div class="p-4 rounded-lg shadow-lg border-2 border-gray-600 bg-gray-700">
                                <div class="flex items-center justify-center">
                                    <img src="./src/assets/storage/LOGO_PRIMARY.png" class="w-26 h-26" alt="">
                                </div>
                                    
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card right -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Card 1: Login as -->
                    <div class="w-full max-w-full mx-auto">
                        <div class="p-4 rounded-lg shadow-lg border-2 border-gray-700 bg-gray-800">
                            <div class="flex items-center justify-between">
                                <h2 class="text-2xl font-bold text-gray-100">Profile</h2>
                                <button class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                    <i class="fa-solid fa-user w-5 text-gray-900"></i>
                                </button>
                            </div>
                            <h1 class="mt-4 text-5xl font-extrabold text-gray-100 uppercase"><?= $_SESSION['username'] ?></h1>
                        </div>
                    </div>
                    <!-- Card 1: Login as -->
                    <div class="w-full max-w-full mx-auto">
                        <div class="p-4 rounded-lg shadow-lg border-2 border-gray-700 bg-gray-800">
                            <div class="flex items-center justify-between">
                                <h2 class="text-2xl font-bold text-gray-100">Total Wallet</h2>
                                <button class="flex items-center justify-center w-10 h-10 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                    <i class="fa-solid fa-wallet w-5 text-gray-900"></i>
                                </button>
                            </div>
                            <h1 class="mt-4 text-5xl font-extrabold text-gray-100 uppercase"><?php echo $total_wallet; ?></h1>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>
</main>