<?php
require_once './src/config/connection.php';
$pageTitle = "Kalwi | Edit Referral";
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


// 📌 Kode untuk mengirimkan data ke database
// Pastikan ada parameter referral_id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: referral.php");
    exit();
}

$referral_id = $_GET['id'];

// Ambil data referral berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM code_referral WHERE id = ?");
$stmt->bind_param("i", $referral_id);
$stmt->execute();
$result = $stmt->get_result();
$referral = $result->fetch_assoc();
$stmt->close();

if (!$referral) {
    header("location: referral.php");
    exit();
}

// Ambil nama pembuat referral
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $referral['created_by']);
$stmt->execute();
$result = $stmt->get_result();
$creator = $result->fetch_assoc();
$stmt->close();



// 📌 Kode untuk mengirimkan data ke database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $discount_percentage = $_POST['discount_percentage'];

    // Validasi angka diskon
    if ($discount_percentage < 0 || $discount_percentage > 100) {
        $error_message = "Diskon harus antara 0-100%!";
    } else {
        $stmt = $conn->prepare("UPDATE code_referral SET discount_percentage = ? WHERE id = ?");
        $stmt->bind_param("ii", $discount_percentage, $referral_id);

        if ($stmt->execute()) {
            echo "<script>alert('Referral code edited successfully!'); window.location.href = 'referral.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

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
            Selamat datang, <?= htmlspecialchars($user['username']) ?>, di Edit Referral Area!
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
                <div class="grid gap-4 lg:grid-cols-4 lg:grid-rows-2">
                    <!-- Card left -->
                    <div class="relative border-2 border-gray-700 lg:col-span-3 lg:row-span-2 rounded-lg bg-gray-800 p-2 sm:p-6">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200 dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Referral</h3>
                        </div>
                        <form method="POST">
                            <div class="p-4 md:p-5 space-y-4">
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Referral Code -->
                                    <div>
                                        <label for="code_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referral Code</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="text" id="code_number" name="code_number" value="<?= htmlspecialchars($referral['code_number']) ?>" readonly required
                                                class="block w-full p-2 border border-gray-300 bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                        </div>
                                    </div>
                                    <!-- Discount Percentage -->
                                    <div>
                                        <label for="discount_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Percentage</label>
                                        <input type="number" id="discount_percentage" name="discount_percentage" value="<?= htmlspecialchars(substr($referral['discount_percentage'], 0, 1)) ?>" min="0" max="100" required
                                            class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                    <!-- Created By -->
                                    <div>
                                        <label for="created_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Created By</label>
                                        <input type="text" id="created_by" name="created_by" value="<?= htmlspecialchars($creator['username']) ?>" readonly
                                            class="block w-full p-2 border border-gray-300 bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                </div>
                            </div>
                            <!-- Form Buttons -->
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-500 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 transition">
                                    Save Referral
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Card Right -->
                    <div class="relative lg:col-span-1 lg:row-span-2 space-y-4">
                        <!-- card referral -->
                        <div class="w-full max-w-full mx-auto">
                            <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                <div class="space-y-2">
                                    <!-- Sub title top -->
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-base text-gray-100">
                                            Created By : <?= htmlspecialchars($creator['username']) ?>
                                        </h4>
                                        <button class="cursor-pointer flex items-center justify-center w-8 h-8 border border-blue-500 rounded-full shadow-lg bg-gradient-to-l from-blue-200 via-blue-400 to-blue-500 hover:bg-gradient-to-br">
                                            <i class="fa-solid fa-pencil w-5 text-gray-900"></i>
                                        </button>
                                    </div>
                                    <!-- Main title -->
                                    <div class="flex items-center justify-between">
                                        <h1 class="text-sm font-base text-gray-100 flex items-center space-x-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 576 512">
                                                <path fill="#ffffff" d="M64 64C28.7 64 0 92.7 0 128l0 64c0 8.8 7.4 15.7 15.7 18.6C34.5 217.1 48 235 48 256s-13.5 38.9-32.3 45.4C7.4 304.3 0 311.2 0 320l0 64c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-64c0-8.8-7.4-15.7-15.7-18.6C541.5 294.9 528 277 528 256s13.5-38.9 32.3-45.4c8.3-2.9 15.7-9.8 15.7-18.6l0-64c0-35.3-28.7-64-64-64L64 64zm64 112l0 160c0 8.8 7.2 16 16 16l288 0c8.8 0 16-7.2 16-16l0-160c0-8.8-7.2-16-16-16l-288 0c-8.8 0-16 7.2-16 16zM96 160c0-17.7 14.3-32 32-32l320 0c17.7 0 32 14.3 32 32l0 192c0 17.7-14.3 32-32 32l-320 0c-17.7 0-32-14.3-32-32l0-192z" />
                                            </svg>
                                            <span class="text-2xl font-bold text-gray-100">
                                                <?= htmlspecialchars($referral['code_number']) ?>
                                            </span>
                                        </h1>
                                        <h3 class="font-base text-md text-gray-100">
                                            <?= htmlspecialchars(substr($referral['discount_percentage'], 0, 1)) ?>%
                                        </h3>
                                    </div>
                                    <hr class="h-px my-2 border-1 border-dashed bg-gray-700">
                                    <!-- Sub title bottom -->
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-md font-bold text-gray-100">
                                            <?= htmlspecialchars($referral['usage_count']) ?> Uses
                                        </h2>
                                        <h2 class="text-md font-bold text-gray-100">
                                            Rp. <?= htmlspecialchars($referral['profit_share']) ?>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- card information -->
                        <div class="w-full max-w-full mx-auto">
                            <div class="p-4 rounded-lg shadow-lg border border-gray-700 bg-gray-800">
                                <div class="space-y-2">
                                    <h1 class="text-2xl font-bold tracking-wide text-gray-100">
                                        Information Before Edit Referral!
                                    </h1>
                                    <hr class="h-px my-5 border-1 border-dashed bg-gray-700">
                                    <p class="text-semibold text-lg tracking-wide text-gray-300">
                                        You have to make sure first before editing the referral,
                                        because editing the referral only edits the discount and the
                                        code and what makes this referral cannot be edited.
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