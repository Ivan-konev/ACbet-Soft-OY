<?php
	
	function cleanInput($stringToClean){
	$semiCleanString = htmlspecialchars($stringToClean);
	$cleanString = strip_tags($semiCleanString);
	return $cleanString;
}
/*	
	function selectReciptInfo($pdo, $currentReciptId){
		$stmt = $pdo ->prepare (" SELECT purch_id, purch_timestamp, cust_fname, cust_lname, purchase_quantity, prod_name, prod_price FROM purchases
														 INNER JOIN customers
														 ON purchases.purch_customer_fk = customers.cust_id
														 INNER JOIN products_in_purchase AS pip
														 ON purchases.purch_id = pip.purchase_fk
														 INNER JOIN products AS pr
														 ON pip.product_fk = pr.product_id
														 WHERE purchases.purch_id = :currentReciptId");
													
		$stmt->bindParam(':currentReciptId', $currentReciptId, PDO :: PARAM_INT);
		$stmt->execute();
		return $stmt ->fetchAll();
	};
	

	
	function renderFormCards($recipt){
	
	$reciptCard ="<div class='card' style='width: 18rem;'>
	 <div class='card-body'>
	 
			<h5 class='card-title'>{$recipt[0]['cust_fname'] }</h5>
			<h6 class='card-subtitle mb-2 text-body-secondary'> {$recipt[0]['cust_lname']}</h6>
			<p class='card-text'>{$recipt[0]['purch_timestamp']}</p>";
			
			foreach($recipt as $row){
				$reciptCard .="<p class='card-text'>{$row['prod_name']} - {$row['prod_price']}</p>";
			}
			
	$reciptCard .="<a href='#' class='btn'>print</a>
							   <a href='#' class='btn'>uppdate</a>
		</div>
		</div>";
		
		return $reciptCard;
};

/*



	function selectForm($pdo){
	$stmt = $pdo ->query("SELECT * FROM form1 WHERE date >= CURDATE()");
	return $stmt;
}
	

function addCar($pdo, $firstName, $lastName, $tel, $Email, $date, $Time){
	
	$firstName = cleanInput($firstName);
	$lastName = cleanInput($lastName);
	$tel = cleanInput($tel);
	$Email = cleanInput($Email);
	$date = cleanInput($date);
	$Time = cleanInput($Time);
	
	$stmt = $pdo ->prepare("INSERT INTO  form1 (first_name, last_name, tel, Email, date, Time) VALUES (:firstName, :lastName, :tel, :Email, :date, :Time)");
	
	$stmt->bindParam(':firstName', $firstName, PDO :: PARAM_STR);
	$stmt->bindParam(':lastName', $lastName, PDO :: PARAM_STR);
	$stmt->bindParam(':tel', $tel, PDO :: PARAM_STR);
	$stmt->bindParam(':Email', $Email, PDO :: PARAM_STR);
	$stmt->bindParam(':date', $date, PDO :: PARAM_STR);
	$stmt->bindParam(':Time', $Time, PDO :: PARAM_STR);
	
	
	$stmt->execute();
}
	
	function deleteBooking($pdo, $id){
		$date = cleanInput($id);
	
	$stmt = $pdo ->prepare("DELETE FROM form1 WHERE form_id = :id");
	
	$stmt->bindParam(':id', $id, PDO :: PARAM_INT);
	
	
	
	$stmt->execute();
	
	header("location: index.php");
	}
	
	
	function getSingleBooking($pdo, $id){
	
	$stmt = $pdo ->prepare("SELECT * FROM form1 WHERE form_id = :id");
	
	$stmt->bindParam(':id', $id, PDO :: PARAM_INT);
	
	
	
	$stmt->execute();
	
	return $stmt->fetch();
	}
	
	
	
	function popInput($currentBooking, $arrindex){
		if(!empty($currentBooking)){
			return $currentBooking[$arrindex];
		}
	}
	
	
	function updateBooking($pdo, $id, $firstName, $lastName, $tel, $email, $date, $time){
	
	$firstName = cleanInput($firstName);
	$lastName = cleanInput($lastName);
	$tel = cleanInput($tel);
	$Email = cleanInput($email);
	$date = cleanInput($date);
	$time = cleanInput($time);
	
	$stmt = $pdo ->prepare(" UPDATE form1
													SET first_name = :firstName, last_name = :lastName, tel = :tel, Email = :email, date = :date, Time = :time
													WHERE form_id = :id;");
	
	$stmt->bindParam(':firstName', $firstName, PDO :: PARAM_STR);
	$stmt->bindParam(':lastName', $lastName, PDO :: PARAM_STR);
	$stmt->bindParam(':tel', $tel, PDO :: PARAM_STR);
	$stmt->bindParam(':email', $email, PDO :: PARAM_STR);
	$stmt->bindParam(':date', $date, PDO :: PARAM_STR);
	$stmt->bindParam(':time', $time, PDO :: PARAM_STR);
	$stmt->bindParam(':id', $id, PDO :: PARAM_INT);
	
	
	$stmt->execute();
	}
	
	*/
?>