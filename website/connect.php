<!DOCTYPE html>

<html>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database_name = "db-ntua";
// Create connection
$conn = new mysqli($servername, $username, $password, $database_name) or die("Unable to connect");

//Check connection
if ($conn->connect_error) {
  die("Η σύνδεση με την βάση δεδομένων απέτυχε: " . $conn->connect_error);
}
else {
  ?>
  <img src="images/green_dot.png" height=20px width=20px>
  <?php echo '<n style="color:white;font-size:16px;">
      online </n> ';?>
  <?php
}
?>
