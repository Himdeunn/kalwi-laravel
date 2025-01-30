<?php
session_start();
$pageTitle = "Kalwi | Login";

include './src/layouts/header.php';
include './src/layouts/footer.php';

ob_start();

// Include database connection
include './src/config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil input dari form login
    $email = $_POST['email'];  
    $password = $_POST['password'];

    // Query untuk mencari pengguna berdasarkan email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['userid'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['type'] = $user['type']; // Menyimpan tipe pengguna di sesi

            // Redirect berdasarkan tipe user
            if ($user['type'] == 3) {
                header("Location: admindashboard.php"); // Halaman admin
            } else {
                header("Location: home.php"); // Halaman user biasa
            }
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }

    $stmt->close();
}

ob_end_flush();
?>

<main class="px-5 py-12 sm:px-6 md:px-9 lg:px-16">
    <div class="flex flex-col items-center justify-center mt-10">
        <div class="flex justify-center items-center mb-3 bg-gray-950 border-gray-700 border rounded-lg sm:max-w-md w-full">
            <a href="#" class="flex items-center uppercase text-2xl font-semibold text-white">
                <img class="w-16 h-16" src="./src/assets/favicon/logo.png" alt="logo">
                <span>KalWi</span>
            </a>
        </div>
        <div class="w-full rounded-lg shadow border md:mt-0 sm:max-w-md xl:p-0 bg-gray-950 border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-white md:text-2xl">
                    Sign in to your account
                </h1>
                <?php if (isset($error_message)): ?>
                    <p class="text-red-500"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <form class="space-y-4 md:space-y-6" method="POST">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-white">Email</label>
                        <input type="email" name="email" id="email" class="bg-gray-900 border border-gray-600 text-white rounded-lg focus:outline-none focus:ring-0 block w-full p-2.5 placeholder-gray-400" placeholder="Enter your email" required>
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-white">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-900 border border-gray-600 text-white rounded-lg focus:outline-none focus:ring-0 block w-full p-2.5 placeholder-gray-400" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-600 rounded bg-gray-900 focus:ring-0 focus:outline-none">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="remember" class="text-gray-300">Remember me</label>
                            </div>
                        </div>
                        <a href="#" class="text-sm font-medium text-blue-500 hover:underline">Forgot password?</a>
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign in</button>
                    <p class="text-sm font-light text-gray-400">
                        Don’t have an account yet? <a href="register.php" class="font-medium text-blue-500 hover:underline">Sign up</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</main>
