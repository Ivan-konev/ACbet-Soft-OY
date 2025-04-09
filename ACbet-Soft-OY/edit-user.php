<?php
include_once "include/header.php";

$allUserRoles = $pdo->query("SELECT * FROM roles")->fetchAll();



if(isset($_GET['uid'])){
	$userId = $_GET['uid'];
	$currentUserInfo = $user_obj->selectUserInfo($userId);
	print_r($currentUserInfo);
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

?>
<h2>edit-user</h2>
<form action="" method="POST">
        <label for="uname">Username:</label>
        <input type="text" id="uname" name="uname" value="<?php echo $currentUserInfo['data'] ['u_name']; ?>" >

        <label for="ufname">First Name:</label>
        <input type="text" id="ufname" name="ufname"  value="<?php echo $currentUserInfo['data'] ['u_fname']; ?>" >

        <label for="ulname">Last Name:</label>
        <input type="text" id="ulname" name="ulname"  value="<?php echo $currentUserInfo['data'] ['u_lname']; ?>" >

        <label for="umail">Email:</label>
        <input type="email" id="umail" name="umail"  value="<?php echo $currentUserInfo['data'] ['u_email']; ?>" >

        <label for="upass">Password:</label>
        <input type="password" id="upass" name="upass" >

        <label for="upassrpt">Repeat Password:</label>
        <input type="password" id="upassrpt" name="upassrpt" >

        <label for="urole">User Role:</label>
        <select id="urole" name="urole" >
				 <?php 
					$currentRole = $currentUserInfo['data'] ['u_role_fk'];
					foreach ($allUserRoles as $role) {
						$selected = ($role['r_id'] == $currentRole) ? 'selected' : ' ' ;
					echo "<option value='{$role['r_id']} ' {$selected}> {$role['r_name']} </option>";
					}
				?>
        </select>

        <button type="submit" name="edituser-submit">Submit</button>
    </form>
	<form action="" method="POST">
   

        <button type="submit" name="deleteuser-submit">delete</button>
    </form>