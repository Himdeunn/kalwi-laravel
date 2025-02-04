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
        <div class="flex my-32 flex-col space-y-10">
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

        <!-- FAQ Section -->
        <div class="flex flex-col my-32 space-y-10">
            <!-- title -->
            <div class="space-y-5">
                <h1 class="mx-auto max-w-full text-center text-4xl font-semibold tracking-tight text-balance text-gray-200 sm:text-5xl">
                    Pertanyaan yang sering diajukan.
                </h1>
                <p class="text-center text-base font-semibold text-gray-400">Kita sering mendapatkan pertanyaan yang sama tentang server kita, jadi kita sudah menjawab semuanya dibawah ini.</p>
            </div>
            <div class="space-y-4">
                <!-- card faq -->
                <div class="mx-auto space-y-5 w-full">
                    <!-- faq 1 -->
                    <div class="card-sm rounded-xl border border-gray-700 cursor-pointer">
                        <button type="button" id="question1" data-state="closed" class="flex items-center justify-between w-full px-4 py-5 sm:p-6">
                            <span class="flex text-lg font-semibold text-white">Bagaimana cara join server KalWi?</span>
                            <svg id="arrow1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="answer1" style="display:none" class="px-4 pb-5 pt-2 border-t border-gray-700 sm:px-6 sm:pb-6 sm:pt-4 text-gray-300">
                            <p>
                                Caranya sangat muda sekali, kalian perlu join <a href="https://discord.gg/kalwi" class="text-blue-500 underline">discord</a>
                                kita dan kalian lihat di saluran guides atau bisa langsung tekan tombol guides di navbar website kita ini.
                            </p>
                        </div>
                    </div>

                    <!-- faq 2 -->
                    <div class="card-sm rounded-xl border border-gray-700 cursor-pointer">
                        <button type="button" id="question2" data-state="closed" class="flex items-center justify-between w-full px-4 py-5 sm:p-6">
                            <span class="flex text-lg font-semibold text-white">Apakah di dalam server KalWi ada banyak modenya?</span>
                            <svg id="arrow1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="answer2" style="display:none" class="px-4 pb-5 pt-2 border-t border-gray-700 sm:px-6 sm:pb-6 sm:pt-4 text-gray-300">
                            <p>
                                Banyak sekali tentunya, di server kami kalian bisa bermain Survival Vanilla, Survival RPG, Survival Economy, dan juga masih banyak lagi.
                            </p>
                        </div>
                    </div>

                    <!-- faq 3 -->
                    <div class="card-sm rounded-xl border border-gray-700 cursor-pointer">
                        <button type="button" id="question3" data-state="closed" class="flex items-center justify-between w-full px-4 py-5 sm:p-6">
                            <span class="flex text-lg font-semibold text-white">Apakah official storenya juga ada untuk pembelian rank dan coin?</span>
                            <svg id="arrow1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="answer3" style="display:none" class="px-4 pb-5 pt-2 border-t border-gray-700 sm:px-6 sm:pb-6 sm:pt-4 text-gray-300">
                            <p>
                                Wuishh, tidak perlu diragukan lagi. Kita juga sudah lama menyediakan official store untuk kalian yang ingin membeli coins dan rank buat kalian bermain di server kita.
                                Jika ingin melihat official store kita, kalian bisa melihat linknya yang ada di <a href="https://discord.gg/kalwi" class="text-blue-500 underline">discord</a> kita atau
                                kalian bisa langsung pergi ke <span class="text-blue-500 underline">store.kalwi.net</span>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- subtitle -->
                <p class="text-center text-base font-semibold text-gray-400">
                    Masih memiliki pertanyaan yang ingi diajukan?
                    <a href="https://discord.gg/kandangalwi" class="cursor-pointer hover:underline font-medium text-blue-400 transition-all duration-200 hover:text-blue-300">Beritahu kepada Admin Support kita</a>
                </p>
            </div>
        </div>

        <div class="card-sm border border-gray-700 rounded-xl mt-64">
            <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
                <!-- menu -->
                <div class="md:flex md:justify-between">
                    <div class="mb-6 md:mb-0">
                        <a href="#" class="flex items-center">
                            <img src="./src/assets/favicon/logo.png" class="w-16 me-3" alt="Kalwi Logo" />
                            <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">Kalwi</span>
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                        <div>
                            <h2 class="mb-6 text-sm font-semibold text-white uppercase">More From Us</h2>
                            <ul class="text-gray-400 font-medium">
                                <li class="mb-4">
                                    <a href="#" class="hover:underline">Official Store</a>
                                </li>
                                <li class="mb-4">
                                    <a href="" class="hover:underline">Website Support</a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="mb-6 text-sm font-semibold text-white uppercase">Follow us</h2>
                            <ul class="text-gray-400 font-medium">
                                <li class="mb-4">
                                    <a href="" class="hover:underline">TikTok</a>
                                </li>
                                <li class="mb-4">
                                    <a href="" class="hover:underline">Discord</a>
                                </li>
                                <li class="mb-4">
                                    <a href="" class="hover:underline">Instagram</a>
                                </li>
                                <li class="mb-4">
                                    <a href="" class="hover:underline">Facebook</a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="mb-6 text-sm font-semibold text-white uppercase">Legal</h2>
                            <ul class="text-gray-400 font-medium">
                                <li class="mb-4">
                                    <a href="#" class="hover:underline">Privacy Policy</a>
                                </li>
                                <li>
                                    <a href="#" class="hover:underline">Terms &amp; Conditions</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr class="my-6 border-gray-700 sm:mx-auto lg:my-8" />
                <!-- icon and copyright -->
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="flex items-start flex-col">
                        <span class="text-sm text-gray-400 sm:text-center">Copyright © 2020 <a href="" class="hover:underline">Kandang Alwi™</a>. All Rights Reserved.</span>
                        <span class="text-sm text-gray-400 sm:text-center">We are not affiliated with Mojab AB.</span>
                    </div>
                    <div class="flex mt-4 sm:justify-center sm:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512" class="w-5 h-5">
                                <path d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z" />
                            </svg>
                            <span class="sr-only">Facebook page</span>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white ms-5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512" class="w-5 h-5">
                                <path d="M524.5 69.8a1.5 1.5 0 0 0 -.8-.7A485.1 485.1 0 0 0 404.1 32a1.8 1.8 0 0 0 -1.9 .9 337.5 337.5 0 0 0 -14.9 30.6 447.8 447.8 0 0 0 -134.4 0 309.5 309.5 0 0 0 -15.1-30.6 1.9 1.9 0 0 0 -1.9-.9A483.7 483.7 0 0 0 116.1 69.1a1.7 1.7 0 0 0 -.8 .7C39.1 183.7 18.2 294.7 28.4 404.4a2 2 0 0 0 .8 1.4A487.7 487.7 0 0 0 176 479.9a1.9 1.9 0 0 0 2.1-.7A348.2 348.2 0 0 0 208.1 430.4a1.9 1.9 0 0 0 -1-2.6 321.2 321.2 0 0 1 -45.9-21.9 1.9 1.9 0 0 1 -.2-3.1c3.1-2.3 6.2-4.7 9.1-7.1a1.8 1.8 0 0 1 1.9-.3c96.2 43.9 200.4 43.9 295.5 0a1.8 1.8 0 0 1 1.9 .2c2.9 2.4 6 4.9 9.1 7.2a1.9 1.9 0 0 1 -.2 3.1 301.4 301.4 0 0 1 -45.9 21.8 1.9 1.9 0 0 0 -1 2.6 391.1 391.1 0 0 0 30 48.8 1.9 1.9 0 0 0 2.1 .7A486 486 0 0 0 610.7 405.7a1.9 1.9 0 0 0 .8-1.4C623.7 277.6 590.9 167.5 524.5 69.8zM222.5 337.6c-29 0-52.8-26.6-52.8-59.2S193.1 219.1 222.5 219.1c29.7 0 53.3 26.8 52.8 59.2C275.3 311 251.9 337.6 222.5 337.6zm195.4 0c-29 0-52.8-26.6-52.8-59.2S388.4 219.1 417.9 219.1c29.7 0 53.3 26.8 52.8 59.2C470.7 311 447.5 337.6 417.9 337.6z" />
                            </svg>
                            <span class="sr-only">Discord community</span>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white ms-5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512" class="w-5 h-5">
                                <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" />
                            </svg>
                            <span class="sr-only">Instagram</span>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white ms-5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512" class="w-5 h-5">
                                <path d="M547.6 103.8L490.3 13.1C485.2 5 476.1 0 466.4 0L109.6 0C99.9 0 90.8 5 85.7 13.1L28.3 103.8c-29.6 46.8-3.4 111.9 51.9 119.4c4 .5 8.1 .8 12.1 .8c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.2 0 49.3-11.4 65.2-29c16 17.6 39.1 29 65.2 29c4.1 0 8.1-.3 12.1-.8c55.5-7.4 81.8-72.5 52.1-119.4zM499.7 254.9c0 0 0 0-.1 0c-5.3 .7-10.7 1.1-16.2 1.1c-12.4 0-24.3-1.9-35.4-5.3L448 384l-320 0 0-133.4c-11.2 3.5-23.2 5.4-35.6 5.4c-5.5 0-11-.4-16.3-1.1l-.1 0c-4.1-.6-8.1-1.3-12-2.3L64 384l0 64c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-64 0-131.4c-4 1-8 1.8-12.3 2.3z" />
                            </svg>
                            <span class="sr-only">Official Store</span>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white ms-5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512" class="w-5 h-5">
                                <path d="M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z" />
                            </svg>
                            <span class="sr-only">TikTok</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
    // js code for alert copy to clipboard
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

    // js code for faq section
    document.querySelectorAll('[id^="question"]').forEach(function(button, index) {
        button.addEventListener('click', function() {
            var answer = document.getElementById('answer' + (index + 1));
            var arrow = document.getElementById('arrow' + (index + 1));

            if (answer.style.display === 'none' || answer.style.display === '') {
                answer.style.display = 'block';
                arrow.style.transform = 'rotate(0deg)';
            } else {
                answer.style.display = 'none';
                arrow.style.transform = 'rotate(-180deg)';
            }
        });
    });
</script>