<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="../assets/styling/style.css">
  <title>Register</title>
</head>
<body class="flex justify-center items-center h-screen bg-gray-100">
  <div class="max-w-md w-full">
    <?php
        if (isset($_GET['error'])) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">' .$_GET['error'] . '</div>';
        }

        if (isset($_GET['success'])) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">' . $_GET['success'] . '</div>';
        }
    ?>
    <form id="registerForm" action="../auth/register.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <h2 class="text-2xl font-bold mb-6">Sign up</h2>
      <input
        type="text"
        value=""
        placeholder="Username"
        name="username"
        class="w-full p-2 mb-4 border border-gray-300 rounded"
      />

      <input
        type="email"
        name="email"
        value=""
        placeholder="Email address"
        class="w-full p-2 mb-4 border border-gray-300 rounded"
      />

      <div class="mb-4 relative">
        <input
          type="password"
          name="password"
          id="password"
          value=""
          placeholder="Password"
          class="w-full p-2 mb-2 border border-gray-300 rounded pr-10"
        />
        <div class="w-5 h-5 flex justify-center items-center absolute text-xl right-3 top-3 cursor-pointer text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>
        </div>
      </div>

      <input
        type="password"
        value=""
        name="repassword"
        placeholder="Re-enter Password"
        class="w-full p-2 mb-2 border border-gray-300 rounded"
      />

      <button type="button" class="block rounded bg-blue-500 text-white py-1 px-2 mb-4" onclick="generatePassword()">Generate password</button>

      <button type="submit" class="mt-5 bg-blue-500 text-white py-2 px-4 rounded-full">Sign up</button>

      <p class="mt-4">Do you have an account? <a href="userlogin.php" class="text-blue-500">Sign in</a></p>
    </form>
  </div>

  <script>
    function generatePassword() {
      const length = 12;
      const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      let password = "";
      for (let i = 0; i < length; ++i) {
        password += charset.charAt(Math.floor(Math.random() * charset.length));
      }
      document.getElementById("password").value = password;
    }

    function togglePasswordVisibility() {
      const passwordInput = document.getElementById("password");
      const eyeIcon = document.getElementById("eyeIcon");
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
      } else {
        passwordInput.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
      }
    }
  </script>
</body>
</html>
