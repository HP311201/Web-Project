<?php
session_start();

include 'includes/config.php';

$stmt_count = $conn->prepare("SELECT COUNT(*) as total_records FROM vehicle");
$stmt_count->execute();
$total_records = $stmt_count->fetchColumn();

$records_per_page = 3;

$total_pages = ceil($total_records / $records_per_page);

$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

$offset = ($current_page - 1) * $records_per_page;

if (isset($_POST['submit'])) {
	$cabtype = $_POST['cabtype'];
} else {
	$cabtype = "All";
}

if ($cabtype == "All") {
	$stmt1 = $conn->prepare("SELECT * FROM vehicle LIMIT $records_per_page OFFSET $offset");
} else {
	$cabtype = "%" . $cabtype . "%";
	$stmt1 = $conn->prepare("SELECT * FROM vehicle WHERE type LIKE :cabtype LIMIT $records_per_page OFFSET $offset");
	$stmt1->bindParam(':cabtype', $cabtype);
}
$stmt1->execute();
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
						Search Cab By Type
					</h4>
				</div>

				<div class="col-md-12">
					<form action="search.php" method="post">
						<div class="row">
							<div class="col-md-10">
								<select name="cabtype" class="form-control">
									<option value="All">All</option>
									<?php
									$stmt = $conn->prepare("SELECT distinct type FROM vehicle");

									$stmt->execute();

									$type = $stmt->fetchAll(PDO::FETCH_ASSOC);
									foreach ($type as $types) {
										?>
										?>
										<option value="<?php echo $types['type'] ?>"><?php echo $types['type'] ?></option>
										<?php
									}
									?>
								</select>
							</div>

							<div class="col-md-2">
								<button type="submit" name="submit" class="btn btn-dark">Search</button>
							</div>
						</div>
					</form>
				</div>

			</div>
		</div>
	</section>

	<section class="mt-5">
		<div class="container">
			<div class="row">

				<?php

				if ($stmt1->rowCount() > 0) {
					while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
						?>
						<div class="col-md-4 mb-5">
							<div class="card bg-light">
								<img class="card-img-top" src="assets/images/<?php echo $row['vehicleregno']; ?>.jpg"
									alt="Card image">
								<div class="card-body">
									<h4 class="card-title"><?php echo $row['type']; ?></h4>
									<p class="card-text">Seat Capacity: <b><?php echo $row['noofseats']; ?></b></p>
									<a href="view.php?id=<?php echo base64_encode($row['vehicleregno']); ?>"
										class="btn btn-dark">View
										Details</a>
								</div>
							</div>
						</div>
						<?php

					}
				}

				?>

			</div>


			<div class="col-md-12 text-center">
				<nav aria-label="Pagination">
					<ul class="pagination">
						<li class="page-item <?php echo ($current_page == 1) ? 'disabled' : ''; ?>">
							<a class="page-link" href="?page=<?php echo ($current_page - 1); ?>" aria-label="Previous">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<?php for ($i = 1; $i <= $total_pages; $i++): ?>
							<li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
								<a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
							</li>
						<?php endfor; ?>
						<li class="page-item <?php echo ($current_page == $total_pages) ? 'disabled' : ''; ?>">
							<a class="page-link" href="?page=<?php echo ($current_page + 1); ?>" aria-label="Next">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>

		</div>
	</section>

	<?php include 'includes/footer.php'; ?>
</body>

</html>