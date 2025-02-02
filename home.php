<?php
session_start();
$pageTitle = "Kalwi | Home";

// // Periksa apakah pengguna sudah login
if (!isset($_SESSION['userid'])) {
    // Jika belum login, arahkan ke halaman login
    header("location:login.php");
    exit(); // Pastikan script berhenti setelah redirect
}

?>

<?php
// Sertakan layout dan komponen lainnya
require_once './src/layouts/header.php';
require_once './src/layouts/footer.php';
require_once './src/layouts/navbar_logout.php';
require_once './src/components/alertsCopyToClipboard.php';
?>

<main class="px-5 py-14 sm:px-6 md:px-9 lg:px-10">
    <div class="text-gray-200 mx-auto max-w-1xl lg:max-w-7xl">

        <!-- title header -->
        <h2 class="text-center tex-base text-2xl sm:text-4xl font-semibold text-indigo-400">The Biggest Minecraft Server</h2>
        <p class="mx-auto mt-2 max-w-screen-md text-center text-4xl font-semibold tracking-tight text-balance text-gray-200 sm:text-7xl">Kandang Alwi, Selalu jadi yang terbaik!</p>

        <!-- card bento -->
        <div class="mt-10 grid gap-4 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2">
            <!-- Official Store Card -->
            <div class="relative lg:row-span-2">
                <div class="absolute inset-px blur rounded-lg lg:rounded-l-[2rem]"></div>
                <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-l-[calc(2rem+1px)]">
                    <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
                        <p class="mt-2 text-lg font-medium tracking-tight text-gray-200 max-lg:text-center">Official Store</p>
                        <p class="mt-2 max-w-lg text-sm/6 text-gray-400 max-lg:text-center">Kita mendedikasikan diri untuk membuat sebuah toko official untuk mempermudah para player bermain.</p>
                    </div>
                    <div class="px-8 pt-5 pb-3 sm:px-10 sm:pt-6 sm:pb-0">
                        <a href="#" class="mt-2 rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Visit Store
                            <span aria-hidden="true" class="text-lg">→</span>
                        </a>
                    </div>
                    <div class="@container relative min-h-[30rem] w-full grow max-lg:mx-auto max-lg:max-w-sm">
                        <div class="absolute inset-x-20 top-10 bottom-5 overflow-hidden rounded-t-[12cqw] rounded-b-[12cqw] border-x-[3cqw] border-t-[3cqw] border-b-[3cqw] border-gray-950 bg-gray-800 shadow-2xl">
                            <img class="size-full object-cover object-top" src="./src/assets/img/prototype/mobile1.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="pointer-events-none absolute inset-px rounded-lg ring-1 shadow-sm ring-black/20 lg:rounded-l-[2rem]"></div>
            </div>

            <!-- Performance Card -->
            <div class="relative max-lg:row-start-1">
                <div class="absolute inset-px rounded-lg bg-gray-800 blur max-lg:rounded-t-[2rem]"></div>
                <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)]">
                    <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                        <p class="mt-2 text-lg font-medium tracking-tight text-gray-200 max-lg:text-center">Performance</p>
                        <p class="mt-2 max-w-lg text-sm/6 text-gray-400 max-lg:text-center">Tentunya, server kita selalu cepat setiap saat dan server kita tidak pernah lag. Jika lag? langsung kita maintenance!</p>
                    </div>
                    <div class="flex flex-1 items-center justify-center my-2 px-8 max-lg:pt-10 max-lg:pb-12 sm:px-10 lg:pb-2">
                        <img class="w-full max-lg:max-w-xs rounded-lg border-4 border-gray-600" src="./src/assets/img/bg/bg-6.png" alt="">
                    </div>
                </div>
                <div class="pointer-events-none absolute inset-px rounded-lg ring-1 shadow-sm ring-black/20 max-lg:rounded-t-[2rem]"></div>
            </div>

            <!-- Security Card -->
            <div class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2">
                <div class="absolute inset-px rounded-lg blur bg-gray-800"></div>
                <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)]">
                    <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                        <p class="mt-2 text-lg font-medium tracking-tight text-gray-200 max-lg:text-center">Security</p>
                        <p class="mt-2 max-w-lg text-sm/6 text-gray-400 max-lg:text-center">Server kita sangat aman sekali, kita menyediakan sesi login pada akun premium maupun akun crack!</p>
                    </div>
                    <div class="@container flex flex-1 items-center max-lg:py-6 lg:pb-2">
                        <img class="h-[min(152px,40cqw)] object-cover" src="https://tailwindui.com/plus/img/component-images/bento-03-security.png" alt="">
                    </div>
                </div>
                <div class="pointer-events-none absolute inset-px rounded-lg ring-1 shadow-sm ring-black/20"></div>
            </div>

            <!-- All Platform & Version Card -->
            <div class="relative lg:row-span-2">
                <div class="absolute inset-px rounded-lg blur max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]"></div>
                <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-b-[calc(2rem+1px)] lg:rounded-r-[calc(2rem+1px)]">
                    <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
                        <p class="mt-2 text-lg font-medium tracking-tight text-gray-200 max-lg:text-center">All Platform & Version</p>
                        <p class="mt-2 max-w-lg text-sm/6 text-gray-400 max-lg:text-center">Semua perangkat bisa bergabung ke server kita, Bedrock-Java-Pocket Edition? Bisa bergabung tentunya!</p>
                    </div>
                    <div class="px-8 pt-5 pb-3 sm:px-10 sm:pt-6 sm:pb-0">
                        <button
                            class="mt-2 rounded-md bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            onclick="copyToClipboard(this)"
                            data-copy-text="kandangalwi.me">
                            Join Server
                            <span aria-hidden="true" class="text-lg">→</span>
                        </button>
                    </div>
                    <div class="relative min-h-[30rem] w-full grow">
                        <div class="absolute top-10 right-0 bottom-0 left-10 overflow-hidden rounded-tl-xl bg-gray-800 shadow-2xl">
                            <img class="h-full object-cover" src="./src/assets/img/prototype/allPlatformVersion.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="pointer-events-none absolute inset-px rounded-lg ring-1 shadow-sm ring-black/20 max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]"></div>
            </div>
        </div>

        <!-- testi section -->
        <div class="flex mt-32 flex-col space-y-10">
            <!-- title -->
            <div class="space-y-5">
                <h1 class="mx-auto mt-2 max-w-full text-center text-4xl font-semibold tracking-tight text-balance text-gray-200 sm:text-5xl">
                    Dipercaya para player minecraft di Indonesia
                </h1>
                <p class="text-center text-base font-semibold text-gray-400">Player minecraft mempercayai kita sebagai server terbaik yang pernah ada dan server terbesar yang ada di Indonesia</p>
            </div>
            <!-- card -->
            <div class="card rounded-xl max-w-screen border-3 border-gray-700 rounded-[calc(var(--radius-lg)+1px)] lg:rounded-[calc(2rem+1px)] py-24 sm:py-32">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <dl class="grid grid-cols-1 gap-x-8 gap-y-16 text-center lg:grid-cols-3">
                        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                            <dt class="text-base/7 text-gray-300">Player Join in Our Servers</dt>
                            <dd class="order-first text-3xl font-semibold tracking-tight text-white sm:text-5xl">2569+</dd>
                        </div>
                        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                            <dt class="text-base/7 text-gray-300">Player Active in Server</dt>
                            <dd class="order-first text-3xl font-semibold tracking-tight text-white sm:text-5xl">1094+</dd>
                        </div>
                        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                            <dt class="text-base/7 text-gray-300">New users monthly</dt>
                            <dd class="order-first text-3xl font-semibold tracking-tight text-white sm:text-5xl">175+</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        
    </div>
</main>

<!-- js code for alert -->
<script>
    function copyToClipboard(element) {
        const textToCopy = element.getAttribute('data-copy-text');
        navigator.clipboard.writeText(textToCopy)
            .then(() => {
                showAlert();
            })
            .catch(err => {
                console.error('Gagal menyalin teks: ', err);
            });
    }

    function showAlert() {
        const alert = document.getElementById('success-alert');
        alert.classList.remove('hidden'); // Menampilkan alert
        setTimeout(() => {
            alert.classList.add('hidden'); // Menyembunyikan alert setelah 3 detik
        }, 3000);
    }
</script>