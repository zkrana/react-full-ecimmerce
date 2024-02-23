<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../assets/styling/style.css">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

<div class="test sm:w-full w-[90%]">
    <h2 class="text-2xl font-semibold mb-6">Login</h2>

    <?php
        if (isset($_GET['error'])) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">' .$_GET['error'] . '</div>';
        }

        if (isset($_GET['success'])) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">' . $_GET['success'] . '</div>';
        }
    ?>

    <form action="../auth/login.php" method="post">
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email address</label>
            <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" name="password" id="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4 flex items-center">
            <input type="checkbox" id="showPassword" class="mr-2" onclick="togglePassword()">
            <label for="showPassword" class="text-sm text-gray-600">Show Password</label>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800">
            Login
        </button>
    </form>

    <p class="mt-5 text-gray-600">
        Don't have an account? <a href="registration.php" class="text-blue-500">Sign up</a>
    </p>
</div>

<script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        passwordField.type = passwordField.type === "password" ? "text" : "password";
    }
</script>

</body>
</html>
