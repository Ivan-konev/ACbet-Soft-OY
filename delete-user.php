<?php
include_once "include/header.php";


if(isset($_GET['uid'])){
	$userId = $_GET['uid'];
	$currentUserInfo = $user_obj->selectUserInfo($userId);
	//print_r($currentUserInfo);
}

if(isset($_POST['confirm']) && $_POST['confirm'] === "delete"){
	$result = $user_obj->deleteUser($userId);
	
	if($result['success']){
		header("Location: user-managment.php?deleteduser=1");
	} else{
		$userFeedback = $result['message'];
	}
}

if(isset($_POST['confirm']) && $_POST['confirm'] === "back"){
	header("Location: edit-user.php?uid={$userId}");
}


?>
<div class="container mt-5">
	<div class="row justify-content-center">
			<div class="col-md-6">
			<?php
				if(!isset($userFeedback) && !empty($currentUserInfo['data'] ['u_id'])):
				
			?>
			<h2>Are you want to delete <?=$currentUserInfo['data'] ['u_name'];?></h2>
				<form action="" method="POST">
			   
					<button type="submit" class="btn btn-danger" name="confirm" value="delete">delete</button>
					<button type="submit" class="btn btn-primary" name="confirm" value="back">Go back</button>
					 
				</form>
				<?php else:
				if(!isset($userFeedback)){
					$userFeedback = "this user doesn´t exist";
				}
				?>
				
					<h2> ERROR ERROR ERROR</h2>
					<p><?= $userFeedback ?></p>
				
				<?php endif; ?>
		</div>
	</div>
</div>