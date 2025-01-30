<nav class="sticky top-5 z-50 space-y-3 mx-2 lg:mx-5 md:mx-3 sm:mx-2">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 p-2 rounded-xl bg-gray-900 border border-gray-700">
        <div class="flex items-center justify-between h-16">
            <div class="flex-shrink-0">
                <a href="home.php" class="text-2xl font-bold text-gray-300 hover:text-white">
                    Kalwi
                </a>
            </div>

            <!-- nav -->
            <div class="hidden md:flex space-x-6">
                <a href="home.php" class="text-gray-300 hover:text-white font-medium">Home</a>
                <a href="store.php" class="text-gray-300 hover:text-white font-medium">
                    Store
                    <span class="bg-blue-500 text-white py-1 px-3 ml-1 rounded-full text-sm">
                        30%
                    </span>
                </a>
                <a href="home.php" class="text-gray-300 hover:text-white font-medium">Profile</a>
                <a id="dropdownHoverButton" data-dropdown-toggle="dropdownDesktop" data-dropdown-trigger="hover" class="cursor-pointer text-gray-300 hover:text-white font-medium">More</a>
            </div>

            <!-- Dropdown menu -->
            <div id="dropdownDesktop" class="z-10 hidden divide-y divide-gray-700 border-gray-700 border rounded-lg shadow-sm w-44 bg-gray-900">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHoverButton">
                    <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Product</a>
                    </li>
                    <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Categories</a>
                    </li>
                    <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Referral</a>
                    </li>
                </ul>
            </div>

            <!-- Button -->
            <div class="flex items-center space-x-4">
                <!-- logout button -->
                <div class="hidden md:flex items-center">
                    <a href="logout.php" class="bg-gray-900 p-2 rounded-xl border-2 border-gray-700 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="h-6 w-6">
                            <path fill="#ffffff" d="M320 32c0-9.9-4.5-19.2-12.3-25.2S289.8-1.4 280.2 1l-179.9 45C79 51.3 64 70.5 64 92.5L64 448l-32 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l64 0 192 0 32 0 0-32 0-448zM256 256c0 17.7-10.7 32-24 32s-24-14.3-24-32s10.7-32 24-32s24 14.3 24 32zm96-128l96 0 0 352c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-32 0 0-320c0-35.3-28.7-64-64-64l-96 0 0 64z" />
                        </svg>
                    </a>
                </div>
                <!-- mobile button -->
                <div class="md:hidden flex items-center">
                    <button
                        id="menu-button"
                        type="button"
                        class="bg-gray-900 p-2 rounded-xl border-2 border-gray-700 shadow-sm"
                        aria-controls="mobile-menu"
                        aria-expanded="false">
                        <!-- icon bars -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="h-6 w-6 text-gray-200">
                            <path fill="#ffffff" d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div
        id="mobile-menu"
        class="hidden transform z-50 transition-all duration-500 ease-in-out backdrop-blur-lg bg-opacity-75 p-2 rounded-xl bg-gray-900 border border-gray-700">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="home.php" class="block hover:bg-gray-700 rounded-xl px-4 py-2 text-gray-300 hover:text-white font-medium">
                Home
            </a>
            <a href="store.php" class="block hover:bg-gray-700 rounded-xl px-4 py-2 text-gray-300 hover:text-white font-medium">
                Store
                <span class="items-right bg-blue-500 text-white py-1 px-3 ml-1 rounded-full text-sm">
                    30%
                </span>
            </a>
            <a href="#" class="block hover:bg-gray-700 rounded-xl px-4 py-2 text-gray-300 hover:text-white font-medium">
                Profile
            </a>
            <a id="dropdownHoverButton" data-dropdown-toggle="dropdownMobile" data-dropdown-trigger="hover" class="block hover:bg-gray-700 rounded-xl px-4 py-2 text-gray-300 hover:text-white font-medium">
                More
            </a>
            <a href="logout.php" class="block hover:bg-gray-700 rounded-xl px-4 py-2 text-gray-300 hover:text-white font-medium">
                Logout
            </a>
        </div>
    </div>

    <!-- Dropdown menu -->
    <div id="dropdownMobile" class="z-10 hidden divide-y divide-gray-700 border-gray-700 border rounded-lg shadow-sm w-full bg-gray-900">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHoverButton">
            <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Product</a>
            </li>
            <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Categories</a>
            </li>
            <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Referral</a>
            </li>
        </ul>
    </div>
</nav>

<script>
    const menuButton = document.getElementById('menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    menuButton.addEventListener('click', () => {
        const isExpanded = menuButton.getAttribute('aria-expanded') === 'true';
        menuButton.setAttribute('aria-expanded', !isExpanded);
        mobileMenu.classList.toggle('hidden');
    });
</script>