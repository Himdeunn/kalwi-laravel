<?php
require './src/config/connection.php'; // Pastikan file ini ada dan benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = trim($_POST['name']);
    $slug = trim($_POST['slug']);

    // Cek apakah kategori atau slug sudah ada
    $check = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
    $check->bind_param("s", $slug);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Slug already exists!'); window.history.back();</script>";
    } else {
        // Simpan ke database
        $sql = "INSERT INTO categories (name, slug, created_at, updated_at) VALUES (?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $category_name, $slug);

        if ($stmt->execute()) {
            echo "<script>alert('Category added successfully'); window.location.href='admindashboard.php';</script>";
        } else {
            echo "<script>alert('Error adding category'); window.history.back();</script>";
        }
    }
}
?>

<!-- Main modal -->
<div id="modalAddCategories" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:text-white">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Add New Categories
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modalAddCategories">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category Name</label>
                            <input type="text" id="name" name="name" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                            <input type="text" id="slug" name="slug" placeholder="This same like category name" required class="block w-full p-2 border border-gray-300 focus:outline-none focus:ring-0 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add Categories</button>
                    <button data-modal-hide="modalAddCategories" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>