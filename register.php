<?php
session_start();
$pageTitle = "Kalwi | Register";

include './src/layouts/header.php';
include './src/layouts/footer.php';
include './src/layouts/navbar.php';

// Include database connection
include './src/config/connection.php';

// Cek jika form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($username) || empty($email) || empty($first_name) || empty($last_name) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Cek apakah email sudah ada di database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email is already registered.";
        } else {
            // Hash password sebelum disimpan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Masukkan data pengguna baru ke database
            $stmt = $conn->prepare("INSERT INTO users (username, email, first_name, last_name, password, type) VALUES (?, ?, ?, ?, ?, 1)");
            $stmt->bind_param("sssss", $username, $email, $first_name, $last_name, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $conn->insert_id;  // Simpan user_id di session
                $_SESSION['user_type'] = 1;  // Set user type menjadi 1 (biasa)

                // Redirect ke halaman login atau dashboard setelah sukses
                ob_start();
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error: Could not register. Please try again.";
            }
        }
    }
}
?>

<main class="px-5 py-12 sm:px-6 md:px-9 lg:px-16">
    <div class="flex flex-col items-center justify-center">
        <div class="flex justify-center items-center mb-3 bg-gray-900 border-gray-700 border rounded-lg sm:max-w-xl w-full">
            <a href="#" class="flex items-center uppercase text-2xl font-semibold text-white">
                <img class="w-16 h-16" src="./src/assets/favicon/logo.png" alt="logo">
                <span>KalWi</span>
            </a>
        </div>
        <div class="w-full rounded-lg shadow border md:mt-0 sm:max-w-xl xl:p-0 bg-gray-900 border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-white md:text-2xl">
                    Create an Account
                </h1>
                <?php if (isset($error_message)): ?>
                    <p class="text-red-500"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <form class="space-y-4 md:space-y-6" method="POST" action="">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-white">Username</label>
                            <input type="text" name="username" id="username" class="bg-gray-800 border border-gray-600 text-white rounded-lg block w-full p-2.5 placeholder-gray-400 focus:outline-none focus:ring-0" placeholder="johndoe" required="">
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-white">Email</label>
                            <input type="text" name="email" id="email" class="bg-gray-800 border border-gray-600 text-white rounded-lg block w-full p-2.5 placeholder-gray-400 focus:outline-none focus:ring-0" placeholder="johndoe@example.id" required="">
                        </div>
                        <div>
                            <label for="first_name" class="block mb-2 text-sm font-medium text-white">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="bg-gray-800 border border-gray-600 text-white rounded-lg block w-full p-2.5 placeholder-gray-400 focus:outline-none focus:ring-0" placeholder="John" required="">
                        </div>
                        <div>
                            <label for="last_name" class="block mb-2 text-sm font-medium text-white">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="bg-gray-800 border border-gray-600 text-white rounded-lg block w-full p-2.5 placeholder-gray-400 focus:outline-none focus:ring-0" placeholder="Doe" required="">
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-white">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-800 border border-gray-600 text-white rounded-lg block w-full p-2.5 placeholder-gray-400 focus:outline-none focus:ring-0" required="" />
                        </div>
                        <div>
                            <label for="confirm_password" class="block mb-2 text-sm font-medium text-white">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••" class="bg-gray-800 border border-gray-600 text-white rounded-lg block w-full p-2.5 placeholder-gray-400 focus:outline-none focus:ring-0" required="" />
                        </div>
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign Up</button>
                    <p class="text-sm font-light text-gray-400">
                        Already have an account? <a href="login.php" class="font-medium text-blue-500 hover:underline">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</main>