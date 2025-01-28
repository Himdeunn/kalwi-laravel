<?php
session_start();
$pageTitle = "Kalwi | Welcome";

?>

<?php
include './src/layouts/header.php';
include './src/layouts/tes.php';
include './src/layouts/footer.php';
// include './src/layouts/navbar.php';
?>

<main class="px-4 py-48 sm:px-6 md:px-9 lg:px-12" id="home">
    <div class="flex flex-col justify-center space-y-1 items-left">
        <p class="mb-3 text-3xl font-semibold text-gray-400 sm:text-3xl md:text-3xl lg:text-3xl">
            Selamat Datang di Server Kandang Alwi.
        </p>
        <h1 class="text-5xl font-extrabold uppercase text-gray-200 sm:text-6xl md:text-7xl lg:text-8xl">
            minecraft server terbesar di indonesia<span class="hidden md:block"> Rasakan Pengalaman Bermain yang Tak Terlupakan di Dunia Virtual Kami!</span>
        </h1>
    </div>

</main>