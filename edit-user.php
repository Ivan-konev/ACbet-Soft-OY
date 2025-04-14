<?php
include_once "include/header.php";

$allUserRoles = $pdo->query("SELECT * FROM roles")->fetchAll();



if(isset($_GET['uid'])){
	$userId = $_GET['uid'];
	$currentUserInfo = $user_obj->selectUserInfo($userId);
	//print_r($currentUserInfo);
}

if(isset($_POST['deleteuser-submit'])){
	header("Location: delete-user.php?uid={$userId}");
}

if(isset($_POST['edituser-submit'])){
	echo "<h2>User updated</h2>";
	
	$uname = cleanInput ($_POST["uname"]);
	$fname = cleanInput ($_POST["ufname"]);
	$lname = cleanInput ($_POST["ulname"]);
	$umail = trim($_POST["umail"]);
	$upass = $_POST["upass"];
	$upassrpt = $_POST["upassrpt"];
	$urole = cleanInput ($_POST["urole"]);
	
	$result = $user_obj->checkUserRegisterInfo($uname,  $umail, $upass, $upassrpt, "edit");
	if (!$result['success']){
		echo "Error:" . $result['error'];
	} else {
		$result = $user_obj->editUser($userId, $uname, $fname, $lname, $umail, $upass, $urole);
		if (!$result['success']) {
			echo "Error:" . $result['error'];
		} else {
			echo "user updated";
		};
	};
}

?><div class="container mt-5">
    <h2 class="mb-4">Edit User</h2>

    <!-- Edit User Form -->
    <form action="" method="POST" class="row g-3">

        <div class="col-md-6">
            <label for="uname" class="form-label">Username</label>
            <input type="text" class="form-control" id="uname" name="uname" 
                value="<?= htmlspecialchars($currentUserInfo['data']['u_name']) ?>">
        </div>

        <div class="col-md-6">
            <label for="ufname" class="form-label">First Name</label>
            <input type="text" class="form-control" id="ufname" name="ufname" 
                value="<?= htmlspecialchars($currentUserInfo['data']['u_fname']) ?>">
        </div>

        <div class="col-md-6">
            <label for="ulname" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="ulname" name="ulname" 
                value="<?= htmlspecialchars($currentUserInfo['data']['u_lname']) ?>">
        </div>

        <div class="col-md-6">
            <label for="umail" class="form-label">Email</label>
            <input type="email" class="form-control" id="umail" name="umail" 
                value="<?= htmlspecialchars($currentUserInfo['data']['u_email']) ?>">
        </div>

        <div class="col-md-6">
            <label for="upass" class="form-label">Password</label>
            <input type="password" class="form-control" id="upass" name="upass">
        </div>

        <div class="col-md-6">
            <label for="upassrpt" class="form-label">Repeat Password</label>
            <input type="password" class="form-control" id="upassrpt" name="upassrpt">
        </div>

        <div class="col-md-6">
            <label for="urole" class="form-label">User Role</label>
            <select id="urole" name="urole" class="form-select">
                <?php 
                    $currentRole = $currentUserInfo['data']['u_role_fk'];
                    foreach ($allUserRoles as $role) {
                        $selected = ($role['r_id'] == $currentRole) ? 'selected' : '';
                        echo "<option value='{$role['r_id']}' $selected>{$role['r_name']}</option>";
                    }
                ?>
            </select>
        </div>

        <div class="col-12 d-flex gap-2">
            <button type="submit" name="edituser-submit" class="btn btn-success">Save Changes</button>
            <form action="" method="POST">
                <button type="submit" name="deleteuser-submit" class="btn btn-danger">Delete User</button>
            </form>
        </div>

    </form>
</div>