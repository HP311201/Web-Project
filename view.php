<?php
session_start();

include 'includes/config.php';

if (isset($_GET['id'])) {
	$encode_id = $_GET['id'];
	$id = base64_decode($_GET['id']);
	$stmt = $conn->prepare("SELECT * FROM vehicle WHERE vehicleregno = :id");
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$rec = $stmt->fetch(PDO::FETCH_ASSOC);
}


$msg = '';
$showCaptcha = true;
function generate_Captcha()
{
	$num1 = rand(1, 10);
	$num2 = rand(1, 10);
	$operators = ['+', '-', '*'];
	$operator = $operators[array_rand($operators)];
	$expression = "$num1 $operator $num2";
	$result = eval ("return $expression;");
	return array('expression' => $expression, 'result' => $result);
}

function generate_Captcha_Image($captcha_text)
{
	$image = imagecreatetruecolor(200, 100);
	$background_color = imagecolorallocate($image, 255, 255, 255);
	$text_color = imagecolorallocate($image, 0, 0, 0);
	imagefilledrectangle($image, 0, 0, 199, 99, $background_color);
	imagettftext($image, 30, 0, 40, 60, $text_color, 'monofont.ttf', $captcha_text);
	ob_start();
	imagepng($image);
	$image_data = ob_get_clean();
	imagedestroy($image);
	return 'data:image/png;base64,' . base64_encode($image_data);
}



//insert comment
if (isset($_POST['commentbtn'])) {
	if (isset($_POST['captcha']) && isset($_SESSION['captcha_result']) && $_POST['captcha'] == $_SESSION['captcha_result']) {
		$email = $_POST['email'];
		$comment = $_POST['comment'];
		$stmt = $conn->prepare("INSERT INTO comments (customeremail, comment, vehicleregno) VALUES (:email, :comment, :vehregno)");
		// Bind parameters
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':comment', $comment);
		$stmt->bindParam(':vehregno', $rec['vehicleregno']);
		// Execute the query
		$stmt->execute();
		echo "<script>alert('Thanks for your  comment'); window.location='view.php?id=$encode_id';</script>";
	} else {
		$msg = '<div class="alert alert-danger">Incorrect CAPTCHA</div>';
	}

}


// Generating CAPTCHA
$captcha = generate_Captcha();
$_SESSION['captcha_result'] = $captcha['result'];

// Generate CAPTCHA image URL
$captcha_image_url = generate_Captcha_Image($captcha['expression']);
?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'includes/header.php';
?>

<body>

	<?php include 'includes/nav.php'; ?>


	<section class="mt-5">
		<div class="container">
			<div class="row">
				<div class="col-md-12 mb-5">
					<h4>
						Details
					</h4>
				</div>

				<div class="col-md-6 border">
					<img src="assets/images/<?php echo $rec['vehicleregno']; ?>.jpg" alt="">
				</div>

				<div class="col-md-6">
					<h1>
						<?php echo $rec['type']; ?>
					</h1>
					<br>
					<h6>
						Seat Capacity: <?php echo $rec['noofseats']; ?>
					</h6>
					<h6>
						Vehicle Reg No: <?php echo $rec['vehicleregno']; ?>
					</h6>
					<h6>
						Rent Per KM: <?php echo $rec['rentperkm']; ?>
					</h6>
					<p><?php echo $rec['description']; ?></p>
				</div>

			</div>
		</div>
	</section>

	<section class="mt-5">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1>
						Customer Reviews
					</h1>
				</div>
			</div>
			<?php
			//fetching reviews
			
			$stmt = $conn->prepare("SELECT * FROM comments WHERE vehicleregno = :vid and view= :view order by dated desc");
			$stmt->bindParam(':vid', $id);
			$view = "Y";
			$stmt->bindParam(':view', $view);

			$stmt->execute();

			$comment = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($comment as $comments) {
				?>
				<div class="row">
					<div class="col-md-12">
						<div class="bg-light border m-3 pl-3 pt-3">
							<h6><?php echo $comments['customeremail']; ?> - <i><?php echo $comments['dated']; ?></i></h6>
							<p><?php echo $comments['comment']; ?></p>
						</div>
					</div>
				</div>
				<?php
			}
			?>

			<form action="" method="post">
				<div class="row">
					<div class="col-md-12 mb-3">
						<input type="email" name="email" placeholder="Your Email" class="form-control" required>
					</div>
					<div class="col-md-12 mb-3">
						<textarea name="comment" id="" placeholder="Your Comment" class="form-control"
							required></textarea>
					</div>
					<div class="col-md-12">

						<?php if ($showCaptcha): ?>
							<div class="form-group">
								<br>
								<img src="<?php echo $captcha_image_url; ?>" alt="CAPTCHA Image" style="border: 1px solid;">
								<br>
								<div style="width:20%">
									<input type="text" id="captcha" name="captcha" required placeholder="Enter Captcha Here"
										width="100%">
								</div>
							</div>
						<?php endif; ?>
						<br>
						<label for="">
							<?php echo $msg; ?>
						</label>

						<button type="submit" name="commentbtn" class="btn btn-dark">Post</button>
					</div>
				</div>
			</form>
		</div>
	</section>

	<?php include 'includes/footer.php'; ?>
</body>

</html>