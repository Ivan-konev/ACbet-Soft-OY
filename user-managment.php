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
            <?php if(isset($_GET['deleteduser'])):?>
            <div class="user-feedback bg-success text-light m-5  p-2 border rounded text-center">User was successfully deleted</div>
            <?php endif; ?>
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <form action="" method="POST">
                        <h2 class="text-center mb-4">Manage Users</h2>
                        <div class="mb-3">
                            <label for="uname" class="form-label">Username:</label>
                            <input type="text" name="uname" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="searchuser-submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <?php if (!empty($userList['data'])) : ?>
            <div class="row mt-4">
    <?php foreach ($userList['data'] as $user) : ?>
        <div class="col-12 col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><?= htmlspecialchars($user['u_name'] ?? 'N/A') ?></h5>
                        <span class="badge bg-primary"><?= htmlspecialchars($user['r_name'] ?? 'N/A') ?></span>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <strong>First Name:</strong>
                            <p class="mb-0"><?= htmlspecialchars($user['u_fname'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-6 mb-2">
                            <strong>Last Name:</strong>
                            <p class="mb-0"><?= htmlspecialchars($user['u_lname'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>Email:</strong>
                            <p class="mb-0"><?= htmlspecialchars($user['u_email'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="edit-user.php?uid=<?= urlencode($user['u_id']) ?>" class="btn btn-primary btn-sm">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; 
else:
        echo"<div class='col text-center'>No result</div>";
    ?>
    
</div>

            </div>
        <?php endif; ?>
</div>

</div>