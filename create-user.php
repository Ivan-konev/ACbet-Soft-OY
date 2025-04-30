<?php
include_once "include/header.php";

$allUserRoles = $pdo->query("SELECT * FROM roles")->fetchAll();


if(isset($_POST['register-submit'])){
	echo "<h2>Form submitted</h2>";
	
	$uname = cleanInput ($_POST["uname"]);
	$fname = cleanInput ($_POST["ufname"]);
	$lname = cleanInput ($_POST["ulname"]);
	$umail = trim($_POST["umail"]);
	$upass = $_POST["upass"];
	$upassrpt = $_POST["upassrpt"];
	$urole = cleanInput ($_POST["urole"]);
	
	$result = $user_obj->checkUserRegisterInfo($uname,  $umail, $upass, $upassrpt, "create");
	if (!$result['success']){
		echo "Error:" . $result['error'];
	} else {
		$result = $user_obj->createUser($uname, $fname, $lname, $umail, $upass, $urole);
		if (!$result['success']) {
			echo "Error:" . $result['error'];
		} else {
			echo "user made";
		};
	};
}

?>
<ul class="nav nav-tabs" id="inventory-tabs">
                <li class="nav-item">
                    <a class="nav-link active" id="search-tab"  href="user-managment.php">User management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="add-tab"  href="create-user.php">Lägg till User</a>
                </li>
            </ul>
<div class="container mt-5">
    <h2 class="mb-4">Create User</h2>
    <form action="" method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="uname" class="form-label">Username</label>
            <input type="text" class="form-control" id="uname" name="uname" required>
        </div>

        <div class="col-md-6">
            <label for="ufname" class="form-label">First Name</label>
            <input type="text" class="form-control" id="ufname" name="ufname" required>
        </div>

        <div class="col-md-6">
            <label for="ulname" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="ulname" name="ulname" required>
        </div>

        <div class="col-md-6">
            <label for="umail" class="form-label">Email</label>
            <input type="email" class="form-control" id="umail" name="umail" required>
        </div>

        <div class="col-md-6">
            <label for="upass" class="form-label">Password</label>
            <input type="password" class="form-control" id="upass" name="upass" required>
        </div>

        <div class="col-md-6">
            <label for="upassrpt" class="form-label">Repeat Password</label>
            <input type="password" class="form-control" id="upassrpt" name="upassrpt" required>
        </div>

        <div class="col-md-6">
            <label for="urole" class="form-label">User Role</label>
            <select id="urole" name="urole" class="form-select" required>
                <?php 
                    foreach ($allUserRoles as $role) {
                        echo "<option value='{$role['r_id']}'>{$role['r_name']}</option>";
                    }
                ?>
            </select>
        </div>

        <div class="col-12">
            <button type="submit" name="register-submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>