<?php
include_once "include/functions.php";
include_once "include/class-user.php";
include_once "include/config.php";

if(isset($_POST['login-submit'])){
	$userNameMail = $_POST['email-usernamn'];
	$password = $_POST["password"];
	$result = $user_obj->login($userNameMail, $password);
	
	if($result['success']){
		header("Location: dashboard.php");

	}
	
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Karis Antikvariat Admin login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="include/main.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script defer src="js/script.js"></script>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="min-width: 350px;">
        <h3 class="text-center mb-3">Login</h3>

       <?php if (isset($result) && !$result['success']): ?>
			<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
				<?= htmlspecialchars($result['error']) ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email address or username</label>
                <input type="text" class="form-control" name="email-usernamn" required placeholder="you@example.com/username">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button type="submit" name="login-submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>
       </body>