<?php
session_start();

include 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'includes/header.php';
?>

<body>

	<?php include 'includes/nav.php'; ?>
 
	<section>
		<div class="container p-0">
			<div class="row">
				<div class="col-md-12">
					<img src="assets/images/cab-banner.jpg" alt=""
						style="width:100%;">
				</div>
			</div>
		</div>
	</section>

	<section class="mt-5">
		<div class="container">
			<div class="row">
				<div class="col-md-12 mb-5">
					<h1>
						Available for rent
					</h1>
				</div>
				<?php
				$sql = "SELECT * FROM vehicle";
				$result = $conn->query($sql);

				if ($result->rowCount() > 0) {
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
						?>
						<div class="col-md-4 mb-5">
							<div class="card bg-light">
								<img class="card-img-top" src="assets/images/<?php echo $row['vehicleregno']; ?>.jpg" alt="Card image">
								<div class="card-body">
									<h4 class="card-title"><?php echo $row['type']; ?></h4>
									<p class="card-text">Seat Capacity: <b><?php echo $row['noofseats']; ?></b></p>
									<a href="view.php?id=<?php echo base64_encode($row['vehicleregno']); ?>" class="btn btn-dark">View Details</a>
								</div>
							</div>
						</div>
						<?php

					}
				}
				?>
			</div>
		</div>
	</section>

	<?php include 'includes/footer.php'; ?>
</body>

</html>