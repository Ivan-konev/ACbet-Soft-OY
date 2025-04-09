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
<h2>create-user</h2>
<form action="" method="POST">
        <label for="uname">Username:</label>
        <input type="text" id="uname" name="uname" required>

        <label for="ufname">First Name:</label>
        <input type="text" id="ufname" name="ufname" required>

        <label for="ulname">Last Name:</label>
        <input type="text" id="ulname" name="ulname" required>

        <label for="umail">Email:</label>
        <input type="email" id="umail" name="umail" required>

        <label for="upass">Password:</label>
        <input type="password" id="upass" name="upass" required>

        <label for="upassrpt">Repeat Password:</label>
        <input type="password" id="upassrpt" name="upassrpt" required>

        <label for="urole">User Role:</label>
        <select id="urole" name="urole" required>
            <?php 
				foreach($allUserRoles as $role)
					echo "<option value ='{$role['r_id']}'>{$role['r_name']} </option>"
			?>
        </select>

        <button type="submit" name="register-submit">Submit</button>
    </form>