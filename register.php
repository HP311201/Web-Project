<?php
session_start();
include 'includes/config.php';
?>

<?php
$info = "";
if (isset($_POST['submit'])) {

	$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$password = md5($_POST['password']);
	$non_hashed = $_POST['password'];
	$role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);
	$mobile = filter_var($_POST['mobile'], FILTER_SANITIZE_STRING);
	$licno = filter_var($_POST['licno'], FILTER_SANITIZE_STRING);
	$address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);

	if ($role == "Customer") {

		$stmt = $conn->prepare("insert into customer(name,address,mobile,email,password,non_hashed) VALUES (:name, :address, :mobile, :email, :password, :non_hashed)");
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':non_hashed', $non_hashed);
		$stmt->bindParam(':mobile', $mobile);
		$stmt->bindParam(':address', $address);
	}

	if ($role == "Driver") {

		$stmt = $conn->prepare("insert into driver(name,address,mobile,email,password,non_hashed,driverlicno) VALUES (:name, :address, :mobile, :email, :password, :non_hashed, :licno)");
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':non_hashed', $non_hashed);
		$stmt->bindParam(':mobile', $mobile);
		$stmt->bindParam(':address', $address);
		$stmt->bindParam(':licno', $licno);
	}

	$stmt->execute();
	echo "<script>alert('Thanks for registration'); window.location='login.php';</script>";
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
	<section>
		<div class="container">
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-5">

					<form action="" method="post" class="bg-light p-5 border">
						<h6>Register your account</h6>
						<br>
						<div class="form-group">
							<label>Name:</label>
							<input type="text" class="form-control" name="name" id="name" required>
						</div>
						<div class="form-group">
							<label>Email:</label>
							<input type="text" class="form-control" name="email" id="email" required>
						</div>
						<div class="form-group">
							<label>Mobile:</label>
							<input type="number" class="form-control" name="mobile" id="mobile" required>
						</div>
						<div class="form-group">
							<label>Driver Licence No:</label>
							<input type="text" class="form-control" name="licno" id="licno" required>
						</div>
						<div class="form-group">
							<label>Password:</label>
							<input type="password" class="form-control" name="password" id="pwd" required>
						</div>
						<div class="form-group">
							<label>Address:</label>
							<textarea class="form-control" name="address" id="address" required> </textarea>
						</div>
						<div class="checkbox">
							<div class="form-group">
								<label for="">Your Role:</label>
							</div>

							<label><input type="radio" value="Customer" name="role" checked> Customer</label>
							<label><input type="radio" value="Driver" name="role"> Driver</label>
						</div>
						<?php echo $info; ?>
						<button type="submit" class="btn btn-dark" name="submit">Register</button>
					</form>
				</div>
			</div>
		</div>
	</section>
	<?php include 'includes/footer.php'; ?>
</body>

</html>