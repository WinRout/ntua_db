<?php
include('header.php');
?>

<div class="welcome">
  <h2> Υπηρεσίες και Χρήσεις </h2>
</div>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<body>

	<div class="inputs">
		<form action="" method="post">
				<h4>Τύπος Υπηρεσίας:</h4>
				<label class="radio"><input type="radio" name="service_type" value="service" checked>όλες</label>
				<label class="radio"><input type="radio" name="service_type" value="service_without_subscription">χωρίς εγγραφή</label>
				<label class="radio"><input type="radio" name="service_type" value="service_with_subscription">με εγγραφή</label>
				<br><br><h4>Κόστος:</h4>
			<div class="text">
				<div class="text_input">
					<label for="min_cost">ελάχιστο</label>
				</div>
				<input type="number" id="min_cost" name="min_cost" value="">
				<div class="text_input">
					<label for="max_cost">μέγιστο</label>
				</div>
				<input type="number" id="max_cost" name="max_cost" value="">
			</div>
			<label><input class="button" type="submit" name="submit" value="Καταχώρηση"></label>
		</form>
	</div>
	<?php
	if(isset($_POST['submit'])){
		if(!empty($_POST['service_type'])) {
			$type = $_POST['service_type'];
			$min_cost = $_POST['min_cost'];
			$max_cost = $_POST['max_cost'];
			$query_cost = " where service_description!='Room'";
			if($min_cost) {
				$query_cost = $query_cost." and service_cost >= ".$min_cost;
			}
			if($max_cost){
				$query_cost = $query_cost." and service_cost <= ".$max_cost;
			}
			?>
			<div class="output">
			<h3> Αποτελέσματα Υπηρεσιών: </h3>
			<p>Κάντε κλικ πάνω σε μία υπηρεσία του πίνακα για να δείτε τις χρήσεις.</p>
			</div>
			<table class="simple-table">
			<thead>
				<caption style="font-weight: bold">Υπηρεσίες</caption>
				<tr>
					<th> Περιγραφή </th>
					<th> Κόστος </th>
				</tr>
			</thead>

			<?php
			$query = "
			select service_description, service_cost
			from ".$type.$query_cost.";";
			$result = mysqli_query($conn, $query);
			while($rows_data = mysqli_fetch_assoc($result))
			{
				?>
				<tr>
					<td><a href="visits.php
						?service_description=<?php echo $rows_data['service_description'] ?>">
						<?php echo $rows_data['service_description'] ?></a></td>
						<td><?php echo $rows_data['service_cost'] ?></td>
						<?php
					}
					?>
				</table>

				<?php
			}
			else {
				echo 'Επιλέξτε τύπο υπηρεσίας';
			}
		}
		?>

	</body>
	</html>

  <?php
  include ("footer.php")
  ?>
