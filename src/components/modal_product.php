<?php
include './src/config/connection.php';

// Tentukan jumlah produk per halaman
$limit = 5;

// Ambil nomor halaman dari parameter URL (default: halaman 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ambil produk dengan daftar pembeli + kategori
$query = "
    SELECT product.*, 
           categories.name AS category_name, 
           categories.slug AS category_slug, 
           GROUP_CONCAT(users.username SEPARATOR ', ') AS buyers
    FROM product
    LEFT JOIN categories ON product.category_id = categories.id
    LEFT JOIN users ON users.purchase_product = product.id
    GROUP BY product.id
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);

// Hitung total produk untuk pagination
$total_query = "SELECT COUNT(DISTINCT id) AS total FROM product";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];

// Hitung total halaman
$total_pages = ceil($total_products / $limit);
?>



<!-- Main modal -->
<div id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-gray-800 rounded-lg shadow-sm dark:bg-gray-800"> <!-- Gunakan bg-gray-800 untuk dark mode permanen -->
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600">
                <h3 class="text-xl font-semibold text-white">
                    Static modal
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-600 hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="static-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <!-- Table -->
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left border-2 border-gray-700 text-gray-400">
                        <thead class="text-xs uppercase bg-gray-700 text-gray-300">
                            <tr>
                                <th scope="col" class="px-6 py-3">Product Name</th>
                                <th scope="col" class="px-6 py-3">Category</th>
                                <th scope="col" class="px-6 py-3">Price</th>
                                <th scope="col" class="px-6 py-3">Description</th>
                                <th scope="col" class="px-6 py-3">Purchased By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="bg-gray-800 border-b border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                        <?php echo htmlspecialchars($row['name_product']); ?>
                                    </th>
                                    <td class="px-6 py-4">
                                        <?php echo htmlspecialchars($row['category_name'] ?? 'No Category'); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        $<?php echo number_format($row['price_product'], 2); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo substr(htmlspecialchars($row['description_product']), 0, 50) . '...'; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo $row['buyers'] ? htmlspecialchars($row['buyers']) : 'No Buyer'; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center mt-4">
                    <?php if ($total_pages > 1): ?>
                        <nav class="inline-flex rounded-md shadow">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>"
                                    class="px-4 py-2 bg-gray-700 text-gray-300 hover:bg-gray-600 border border-gray-600 rounded-l-md">
                                    Previous
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>"
                                    class="px-4 py-2 <?php echo ($i == $page) ? 'bg-blue-500 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'; ?> border border-gray-600">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?>"
                                    class="px-4 py-2 bg-gray-700 text-gray-300 hover:bg-gray-600 border border-gray-600 rounded-r-md">
                                    Next
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-600 rounded-b">
                <button data-modal-hide="static-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-500 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    I accept
                </button>
                <button data-modal-hide="static-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-400 focus:outline-none bg-gray-800 rounded-lg border border-gray-600 hover:bg-gray-700 hover:text-white focus:z-10 focus:ring-4 focus:ring-gray-700">
                    Decline
                </button>
            </div>
        </div>
    </div>
</div>