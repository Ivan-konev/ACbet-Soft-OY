<?php
include_once "class-user.php";
include_once "config.php";
include_once "functions.php";

if(isset($_GET['logout']) && $_GET['logout']=== "true"){
	$user_obj->logout();
	header("Location: login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Create/Edit/Delete management</title>

<!-- CSS Order -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="include/main.css">

<!-- JS Order -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script defer src="include/mainJS.js"></script>

</head>
<body>
<nav id="Navbar" class="navbar navbar-expand-lg " style="background-color:#308c54;">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
        if(isset($_SESSION['user'] ['id']) && $user_obj->checkLoginStatus($_SESSION['user'] ['id'])):

        ?>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <li class="nav-item d-flex align-items-center">
          <a class="nav-link text-white <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" href="index.php" target="_blank">Website</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link text-white <?php echo $currentPage == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
        </li>
		<?php if ($user_obj->checkUserRole($_SESSION['user']['role'], [300, 9999])): ?>
			<li class="nav-item d-flex align-items-center">
				<a class="nav-link text-white <?= $currentPage == 'dashboard.php' ? 'active' : ''; ?>" href="user-managment.php">HR</a>
			</li>
		<?php endif; ?>

      </ul>
	  <div class="d-flex pe-4">
          <?php if (isset($_SESSION['user'])): ?>
            <a href="edit-user.php?id=<?= $_SESSION['user']['id'] ?>" class="nav-link text-white d-flex align-items-center gap-2 mb-0">
              <div><p class="mb-0 fs-4"><?= htmlspecialchars($_SESSION['user']['name']) ?></p></div>
              <div><i class="bi bi-person-circle fs-2"></i></div>
            </a>
          <?php endif; ?>
    </div>

		<div class="d-flex align-items-center gap-3">
			

			<form action="" method="get" class="mb-0">
				<button class="btn btn-outline-light text-white" type="submit" name="logout" value="true">Log out</button>
			</form>
		</div>
    <?php
            endif;
     ?>
    </div>
  </div>

  
</nav>
</body>
</html>