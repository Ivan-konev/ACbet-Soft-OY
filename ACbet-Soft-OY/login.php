<?php
include_once "include/header.php";

if(isset($_POST['login-submit'])){
	$userNameMail = $_POST['email-usernamn'];
	$password = $_POST["password"];
	$result = $user_obj->login($userNameMail, $password);
	
	if($result['success']){
		header("Location: dashboard.php");

	}
	
}
?>

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