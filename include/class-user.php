<?php

class User {
	
		public $username;
		public $role_level;
		public $pdo;
		
			public function __construct ($pdo) {
				
				$this->pdo = $pdo;
			}
			
			public function login($uNameMail, $password) {
			try {
				$stmt = $this->pdo->prepare("
					SELECT u_id, u_name, u_email, u_password, r_level, r_name
					FROM users 
					INNER JOIN roles
					ON users.u_role_fk = roles.r_id
					WHERE u_name = :uname OR u_email = :uemail
					LIMIT 1
				");
				$stmt->execute([
					'uname' => $uNameMail,
					'uemail' => $uNameMail
				]);
				$user = $stmt->fetch();

				if ($user && password_verify($password, $user['u_password'])) {
					// Start session if not already started
					if (session_status() === PHP_SESSION_NONE) {
						session_start();
					}

					// Store user data in session
					$_SESSION['user'] = [
						'id' => $user['u_id'],
						'name' => $user['u_name'],
						'email' => $user['u_email'],
						'role' => $user['r_level'],
						'role_name' => $user['r_name']  // <== added
					];

					return ['success' => true];
				}

				return ['success' => false, 'error' => 'Invalid username/email or password'];

			} catch (PDOException $e) {
				return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
			}
}

			public function checkLoginStatus($userId = null){
				if($userId !== null){
					return true;
				} else{
					return false;
				}
			}
			
			public function logout(){
				session_unset();
				session_destroy();
			}
			
		public function checkUserRole($userRole, $allowedRoles) {
			// Make sure $allowedRoles is an array (in case a single role is passed as a number)
			$allowedRoles = (array) $allowedRoles;

			// Check if the user's role is in the list of allowed roles
			return in_array($userRole, $allowedRoles);

			}
			
			public function checkUserRegisterInfo($uname, $umail, $upass, $upassrpt, $condition){
						$errors = [];
						
						// Database placeholder query to check if username exists
						 if ($condition === "create") {
						    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE LOWER (u_name) = LOWER (?) OR LOWER (u_email) = LOWER (?)");
							$stmt->execute([strtolower($uname) , strtolower($umail)]);
							if ($stmt->rowCount() > 0) {
								return ['success' => false, 'error' => 'Username or email already exists.'];
							}
						 }
							// 2. Check if email is valid
						 
							if (!filter_var($umail, FILTER_VALIDATE_EMAIL)) {
								return ['success' => false, 'error' => 'Invalid email format.'];
							}
						 
						 if($condition !== "edit" || $upass !==""){
							// 3. Check if passwords match
							if ($upass !== $upassrpt) {
								return ['success' => false, 'error' => 'Passwords do not match.'];
							}

							// 4. Validate password strength
							if (strlen($upass) < 6) {
								return ['success' => false, 'error' => 'Password must be at least 6 characters long.'];
							}
							if (!preg_match('/[A-Z]/', $upass)) {
								return ['success' => false, 'error' => 'Password must contain at least one uppercase letter.'];
							}
							if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $upass)) {
								return ['success' => false, 'error' => 'Password must contain at least one special character.'];
							}
						 }
							// ✅ All checks passed
							return ['success' => true];

							}
			
			public function createUser($uname, $fname, $lname, $umail, $upass, $urole){
				try {
						// Start a transaction
						$this->pdo->beginTransaction();

						// Hash the password before storing it
						$hashed_pass = password_hash($upass, PASSWORD_BCRYPT);

						// Prepare SQL query
						$stmt = $this->pdo->prepare("INSERT INTO users (u_name, u_fname, u_lname, u_email, u_password, u_isactive, u_role_fk) 
								VALUES (?, ?, ?, ?, ?, ?, ?)");
								
						// Execute the statement
						if ($stmt->execute([$uname, $fname, $lname, $umail, $hashed_pass, 1, $urole])) {
							// Commit the transaction on success
							$this->pdo->commit();
							return ['success' =>true]; // Success
						} else {
							// Rollback transaction if execution fails
							$this->pdo->rollBack();
							return false; // Insertion failed
						}
					} catch (PDOException $e) {
						// Rollback in case of an error
						$this->pdo->rollBack();
						error_log("Error: " . $e->getMessage());
						return ['success' => false, 'error' => 'database error:' . $e->getMessage()]; // Error occurred
					}
			}
			
						public function editUser($userId, $uname, $ufname, $ulname, $umail, $upass, $urole) {
							try {
								// Begin transaction
								$this->pdo->beginTransaction();

								// Prepare the base SQL query to update user info (excluding username)
								$query = "UPDATE users SET u_fname = ?, u_lname = ?, u_email = ?, u_role_fk = ?";

								// If password is provided (i.e., not empty), hash and update it
								if (!empty($upass)) {
									$hashedPassword = password_hash($upass, PASSWORD_DEFAULT);
									$query .= ", u_password = ?";
									$stmt = $this->pdo->prepare($query . " WHERE u_id = ?");
									$stmt->execute([$ufname, $ulname, $umail, $urole, $hashedPassword, $userId]);
								} else {
									// If no password change, exclude the password from the query
									$stmt = $this->pdo->prepare($query . " WHERE u_id = ?");
									$stmt->execute([$ufname, $ulname, $umail, $urole, $userId]);
								}

								// Commit transaction
								$this->pdo->commit();

								return ['success' => true];
							} catch (Exception $e) {
								// Rollback if something goes wrong
								$this->pdo->rollBack();
								return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
							}
						}
			
			
			public function selectUserInfo($userId){
				   try {
						// Prepare SQL query to fetch user information
						$stmt = $this->pdo->prepare("SELECT u_id,  u_name, u_fname, u_lname, u_email, u_role_fk FROM users WHERE u_id = ?");			
						// Execute the statement
						$stmt->execute([$userId]);
						
						$user = $stmt->fetch(PDO::FETCH_ASSOC);
						
						if ($user){
							return ['success' => true, 'data' => $user];
						} else {
							return ['success' => false, 'error' => $user];
						}
						
					} catch (Exception $e) {
						// Log the error and return false
						return ['success' => false, 'error' =>'database error:' . $e->getMessage()];
					}
				}
				
				public function searchUsers($userName){
					try {
						// Prepare SQL query to fetch user information
						$stmt = $this->pdo->prepare("SELECT u_id, u_name, u_fname, u_lname, u_email, r_name  
						FROM users 
						INNER JOIN roles 
						ON users.u_role_fk = roles.r_id 
						WHERE u_name 	LIKE	?");	
						
						// Execute the statement
						$stmt->execute(["%" . $userName . "%"]);
						
						$userList = $stmt->fetchAll(PDO::FETCH_ASSOC);
						
						if ($userList){
							return ['success' => true, 'data' => $userList];
						} else {
							return ['success' => false, 'error' => 'user not found.'];
						}
						
					} catch (Exception $e) {
						// Log the error and return false
						return ['success' => false, 'error' =>'database error:' . $e->getMessage()];
					}
					}
				
				
				
				
				public function deleteUser($userId): array{
					try{
						// Prepare SQL query to fetch user information
						$stmt = $this->pdo->prepare("DELETE FROM users WHERE u_id = ?");	
						
						// Execute the statement
						$stmt->execute([$userId]);
						
						
						if ($stmt->rowCount() > 0){
							return ['success' => true, 'message' => 'delete successful.'];
						} else {
							return ['success' => false, 'message' => 'delete unsuccessful.'];
						}
						
					} catch (Exception $e) {
						// Log the error and return false
						return ['success' => false, 'error' =>'database error:' , 'error' => $e->getMessage()];
					}
				}
}
?>
