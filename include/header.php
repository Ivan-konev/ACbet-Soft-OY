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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="include/main.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script defer src="js/script.js"></script>
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
		 <li class="nav-item d-flex align-items-center">
          <a class="nav-link text-white <?php echo $currentPage == 'dashboard.php' ? 'active' : ''; ?>" href="user-managment.php">HR</a>
        </li>
      </ul>
             <form action="" method="get" >
                <button class="btn btn-outline-light text-white" type="submit" name="logout" value="true">log out</button>
             </form>
    <?php
            endif;
     ?>
    </div>
  </div>
</nav>
</body>
</html>