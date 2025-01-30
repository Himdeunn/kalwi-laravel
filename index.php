<?php
session_start();
$pageTitle = "Kalwi | Welcome";

?>

<?php
include './src/layouts/header.php';
include './src/layouts/footer.php';
include './src/layouts/navbar_login.php';
?>

<main class="px-5 py-14 sm:px-6 md:px-9 lg:px-16">
    <div class="flex flex-col justify-center space-y-5 items-center my-5 px-0 sm:px-32">
        <h2 class="text-5xl font-extrabold text-gray-200 text-center sm:text-6xl md:text-7xl lg:text-8xl">
            KalWi
        </h2>
        <h2 class="text-lg font-extrabold uppercase text-gray-200 text-center sm:text-1xl md:text-2xl lg:text-3xl">
            selamat datang di minecraft server terbesar indonesia <span class="hidden md:block">Rasakan Pengalaman Bermain yang Tak Terlupakan di Dunia Virtual Kami!</span>
        </h2>
        <div class="flex justify-between items-center">
            <a href="login.php" class="py-3 px-10 me-2 mb-2 text-sm font-medium focus:outline-none rounded-xl border bg-gray-900 focus:ring-gray-700 bg-gray-800 text-gray-400 border-gray-600 hover:text-white hover:bg-gray-800">Login</a>
            <a href="register.php" class="py-3 px-10 me-2 mb-2 text-sm font-medium focus:outline-none rounded-xl border bg-gray-900 focus:ring-gray-700 bg-gray-800 text-gray-400 border-gray-600 hover:text-white hover:bg-gray-800">Register</a>
        </div>
    </div>
</main>