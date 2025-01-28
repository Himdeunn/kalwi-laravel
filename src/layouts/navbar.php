<nav class="fixed w-full z-20 px-5 top-5">
    <div class="max-w-full rounded-lg flex flex-wrap items-center justify-between mx-auto bg-gray-800 border border-gray-700 p-4">
        <a href="https://flowbite.com/" class="flex items-center space-x-1 rtl:space-x-reverse"> 
            <img src="./src/assets/favicon/logo.png" class="h-10" alt="">
            <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">KalWi</span>
        </a>
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <a href="" class="text-white hover:bg-gray-700 border border-gray-700 focus:ring-0 focus:outline-none font-medium rounded-full sm:rounded-lg text-sm lg:px-6 lg:py-3 px-4 py-2 text-center">Login</a>
            <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-full md:hidden border border-gray-700 focus:outline-none focus:ring-0 text-gray-400 hover:bg-gray-700" aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Open main menu</span> <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
        </div>
        <div class="items-center justify-between hidden md:flex md:w-auto md:order-1 w-full">
            <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 bg-gray-800 border-gray-700">
                <li>
                    <a href="#" class="block hover:underline py-2 px-3 text-white" aria-current="page">Home</a>
                </li>
                <li>
                    <a href="#" class="block hover:underline py-2 px-3 text-white">Product</a>
                </li>
                <li>
                    <a href="#" class="block hover:underline py-2 px-3 text-white">Cart</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- mobile -->
    <div class="items-center justify-between hidden md:w-auto md:order-1 w-full" id="navbar-sticky">
        <ul class="flex flex-col p-4 space-y-3 mt-4 font-medium rounded-lg bg-gray-800 border border-gray-700">
            <li>
                <a href="#" class="block py-3 px-5 text-white rounded-lg hover:bg-gray-700" aria-current="page">Home</a>
            </li>
            <li>
                <a href="#" class="block py-3 px-5 text-white rounded-lg hover:bg-gray-700">Product</a>
            </li>
            <li>
                <a href="#" class="block py-3 px-5 text-white rounded-lg hover:bg-gray-700">Cart</a>
            </li>
        </ul>
    </div>
</nav>