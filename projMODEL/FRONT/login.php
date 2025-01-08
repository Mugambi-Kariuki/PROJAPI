@@ -1,62 +1,63 @@

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
       body {
           background-color: #f0f8ff; /* Light background */
           font-family: Arial, sans-serif;
       }
       .form-container {
           background-color: white;
           padding: 30px;
           border-radius: 10px;
           box-shadow: 1 4px 8px rgba(0, 0, 0, 0.2);
       }
       label {
           color: black;
       }
       .btn-login {
           background-color: grey;
           color: white;
           border: none;
       }
       .btn-login:hover {
           background-color: darkgrey;
       }
   </style>
</head>
<body>
   <div class="container d-flex justify-content-center align-items-center vh-100">
       <div class="form-container col-md-6">
           <h1 class="text-center mb-4">Login</h1>
           <form action="login_process.php" method="POST">
               <div class="mb-3">
                   <label for="email" class="form-label">Email:</label>
                   <input type="email" id="email" name="email" class="form-control" required>
               </div>
               <div class="mb-3">
                   <label for="username" class="form-label">Username:</label>
                   <input type="text" id="username" name="username" class="form-control" required>
               </div>
               <div class="mb-3">
                   <label for="password" class="form-label">Password:</label>
                   <input type="password" id="password" name="password" class="form-control" required>
               </div>
               <div class="d-grid gap-2">
                   <button type="submit" class="btn btn-login">Login</button>
               </div>
           </form>
           <p class="text-center mt-3">
               Don't have an account? <a href="signup.php">Sign up here</a>.
           </p>
       </div>
   </div>
   <!-- Bootstrap JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>