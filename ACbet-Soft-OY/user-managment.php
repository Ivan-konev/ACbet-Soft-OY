<?php
include_once "include/header.php";

$userList["data"] = $pdo->query("SELECT u_id, u_name, u_fname, u_lname, u_email, r_name  
						FROM users 
						INNER JOIN roles 
						ON users.u_role_fk = roles.r_id 
						LIMIT 10");
			
//print_r($userList["data"]);

if(isset($_POST['searchuser-submit'])){
	
	$userName = cleanInput ($_POST['uname']);
	$userList = $user_obj->searchUsers($userName);

	
}

if(isset($_GET['deleteduser'])){
	
}

?>

<div class="container mt-5">
	<div class="row justify-content-center">
			<?php if(isset($_GET['deleteduser'])): ?>
			<div class="user-feedback bg-success  text-white m-4"><p class="text-center m-0">User was Killed successfully</p></div>
			<?php endif ?>
					<div class="col-md-6">
							<div class="card shadow-sm p-4">
								<h2 class="text-center mb-3">Search User</h2>
								<form action="" method="POST">
									<div class="mb-3">
										<label for="uname" class="form-label">Username:</label>
										<input type="text" id="uname" name="uname" class="form-control" placeholder="Enter username">
									</div>
									<div class="text-center">
										<button type="submit" name="searchuser-submit" class="btn btn-primary w-100">
											<i class="fas fa-search"></i> Search
										</button>
									</div>
								</form>
							</div>
				</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-md-6">
    <ul class="list-group">
        <?php
		if(!empty($userList['data'])):
		foreach ($userList['data'] as $user): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong><?= htmlspecialchars($user['u_name']); ?></strong> 
                    (<?= htmlspecialchars($user['r_name']); ?>)
                    <br>
                    <small><?= htmlspecialchars($user['u_fname']) . " " . htmlspecialchars($user['u_lname']); ?></small><br>
                    <small>Email: <?= htmlspecialchars($user['u_email']); ?></small>
                </div>
                <a href="edit-user.php?uid=<?= urlencode($user['u_id']); ?>"
                   class="btn btn-primary btn-sm">
                    View Profile
                </a>
            </li>
        <?php endforeach; 
		else:
			echo "<div class='col text-center'> No result </div>";
		endif;
		?>
		
    </ul>
</div>
	</div>
</div>