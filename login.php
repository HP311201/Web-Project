<?php
session_start();
include 'includes/config.php';
?>

<?php
$info = "";
if (isset($_POST['submit'])) {

	$username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$password = md5($_POST['password']);
	$role = filter_var($_POST['role'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	if ($role == "Admin") {
		$stmt = $conn->prepare("SELECT * FROM users WHERE email = :username and password= :password");
	}
	if ($role == "Customer") {
		$stmt = $conn->prepare("SELECT * FROM customer WHERE email = :username and password= :password");
	}
	if ($role == "Driver") {
		$stmt = $conn->prepare("SELECT * FROM driver WHERE email = :username and password= :password");
	}
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':password', $password);
	$stmt->execute();
	$stmt->rowCount();

	if ($stmt->rowCount() > 0) {
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($role == "Admin") {
			$stmt = $conn->prepare("SELECT * FROM users WHERE email = :username and password= :password");
			$_SESSION['id'] = $user['id'];
			$_SESSION['username'] = $user["email"];
			$_SESSION['email'] = $user["email"];
			$_SESSION['role'] = $role;
			echo "<script>alert('Logged In'); window.location='admin/index.php'; </script>";
		}
		if ($role == "Customer") {
			$stmt = $conn->prepare("SELECT * FROM customer WHERE email = :username and password= :password");
			$_SESSION['id'] = $user['custid'];
			$_SESSION['username'] = $user["email"];
			$_SESSION['email'] = $user["email"];
			$_SESSION['role'] = $role;
			echo "<script>alert('Logged In'); window.location='customer/index.php'; </script>";
		}
		if ($role == "Driver") {
			$stmt = $conn->prepare("SELECT * FROM driver WHERE email = :username and password= :password");
			$_SESSION['id'] = $user['driverid'];
			$_SESSION['username'] = $user["email"];
			$_SESSION['email'] = $user["email"];
			$_SESSION['role'] = $role;
			echo "<script>alert('Logged In'); window.location='driver/index.php'; </script>";
		}

		

		
	} else {
		$info = "<div class='alert alert-danger'>Invalid User</div>";
	}

}
?>

<!DOCTYPE html>
<html lang="en">
<?php
include 'includes/header.php';
?>

<body>

	<?php include 'includes/nav.php'; ?>
	<br>
	<div class="container">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-5">

				<form action="" method="post" class="bg-light p-5 border">
					<h6>Login your account</h6>
					<br>
					<div class="form-group">
						<label for="username">Username:</label>
						<input type="text" class="form-control" name="username" id="username" required>
					</div>
					<div class="form-group">
						<label for="pwd">Password:</label>
						<input type="password" class="form-control" name="password" id="pwd" required>
					</div>
					<div class="checkbox">
						<div class="form-group">
							<label for="">Role:</label>
						</div>
						<label><input type="radio" value="Admin" name="role" checked> Admin</label>
						<label><input type="radio" value="Driver" name="role"> Driver</label>
						<label><input type="radio" value="Customer" name="role"> Customer</label>
					</div>
					<?php echo $info; ?>
					<button type="submit" class="btn btn-dark" name="submit">Login</button>
				</form>
			</div>
		</div>
	</div>
</body>

</html>