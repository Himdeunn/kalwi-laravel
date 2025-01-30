<?php
require './src/config/connection.php'; // Sesuaikan dengan file koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $name_product = $_POST['name_product'];
    $price_product = $_POST['price_product'];
    $description_product = $_POST['description_product'];

    // Upload File
    $icon_name = $_FILES['icon_product']['name'];
    $icon_tmp = $_FILES['icon_product']['tmp_name'];
    $icon_destination = "./src/assets/storage/" . basename($icon_name); // Pastikan folder uploads ada

    if (move_uploaded_file($icon_tmp, $icon_destination)) {
        // Simpan ke database
        $sql = "INSERT INTO product (category_id, name_product, price_product, description_product, icon_product, created_at, update_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $category_id, $name_product, $price_product, $description_product, $icon_destination);

        if ($stmt->execute()) {
            echo "<script>alert('Product added successfully'); window.location.href='admindashboard.php';</script>";
        } else {
            echo "<script>alert('Error adding product'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('File upload for icon product failed'); window.history.back();</script>";
    }
}
?>


<!-- Main modal -->
<div id="modalAddProduct" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:text-white">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Add New Product
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modalAddProduct">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form id="addProductForm" method="POST" enctype="multipart/form-data">
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                            <select id="category_id" name="category_id" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                <option value="">Select Category</option>
                                <?php
                                require './src/config/connection.php'; // Koneksi database

                                $result = $conn->query("SELECT id, name FROM categories"); // Sesuaikan nama tabel
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No Categories Available</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Product Name -->
                        <div>
                            <label for="name_product" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Name</label>
                            <input type="text" id="name_product" name="name_product" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        </div>
                        <!-- Price -->
                        <div>
                            <label for="price_product" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                            <input type="number" id="price_product" name="price_product" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        </div>
                        <!-- Description -->
                        <div>
                            <label for="description_product" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea id="description_product" name="description_product" rows="3" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"></textarea>
                        </div>
                        <!-- Icon Product -->
                        <div>
                            <label for="icon_product" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Icon</label>
                            <input type="file" id="icon_product" name="icon_product" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add Product</button>
                    <button data-modal-hide="modalAddProduct" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
