<?php
include('header.php');
?>

<div class="welcome">
  <h2> Υπηρεσίες και Χρήσεις </h2>
  <form action="services.php">
    <input type="submit" class="button" value="Πίσω" />
  </form>
</div>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<?php
$service_description = $_GET['service_description'];
$query="
select place_name from
(
  service join offered_in on offered_in.service_id=service.service_id)
  join place on place.place_id=offered_in.place_id
  where service.service_description='";
  $query = $query.$service_description."';";
  $result = mysqli_query($conn, $query);
  $place_name = mysqli_fetch_assoc($result);
  $place_name = $place_name['place_name'];
  ?>
  <br><br>
  <div class="inputs">
    <h3> "<?php echo $service_description; ?>"</h3>
    <br>

    <form action="" method="post">

      <h4>Ημερομηνίες Ενδιαφέροντος:</h4>
      <div class="text">
        <div class="text_input">
          <label for="min_date">Από</label>
        </div>
        <input type="date" id="min_date" name="min_date" value="">
        <div class="text_input">
          <label for="max_date">Έως</label>
        </div>
        <input type="date" id="max_date" name="max_date" value="">
      </div>
      <label><input class="button" type="submit" name="submit" value="Καταχώρηση"></label>
    </form>
  </div>
  <br>

  <h3 class="output"> Αποτελέσματα : </h3>

  <?php
  $query_date = " where place_name = '".$place_name."'";
  if(isset($_POST['submit'])){
    $min_date = $_POST['min_date'];
    $max_date = $_POST['max_date'];
    if ($min_date) {
      $query_date = $query_date." and (timestampdiff(day, entry_date_time, '".$min_date."') <= 0)";

      ?>
      <p>Από:<br><?php echo $min_date; ?></p>
      <?php
    }
    if ($max_date) {
      $query_date = $query_date." and (timestampdiff(day, '".$max_date."', entry_date_time) <= 0)";
      ?>
      <p>Έως:<br> <?php echo $max_date; ?></p>
      <?php
    }
  }
  ?>
  <table class="content-table">
  <thead>
    <caption style="font-weight: bold">Χρήσεις υπηρεσίας "<?php echo $service_description; ?>"</caption>
    <tr>
      <th> NFC ID </th>
      <th> Όνομα </th>
      <th> Επίθετο </th>
      <th> Ημερομηνία και ώρα χρήσης </th>
    </tr>
  </thead>

  <?php
  $query = "
  select customer.nfc_id,
  customer.first_name,
  customer.last_name,
  visited.entry_date_time
  from
  (
    customer join visited
    on customer.nfc_id = visited.customer_id
    ) join place
    on visited.place_id = place.place_id";
    $query = $query.$query_date." order by visited.entry_date_time desc;";
    $result = mysqli_query($conn, $query);
    while($rows_data = mysqli_fetch_assoc($result))
    {
      ?>
      <tr>
        <td><?php echo $rows_data['nfc_id'] ?></td>
        <td><?php echo $rows_data['first_name'] ?></td>
        <td><?php echo $rows_data['last_name'] ?></td>
        <td><?php echo $rows_data['entry_date_time'] ?></td>
        <?php
      }
      ?>
    </table>
    <?php
    include ("footer.php")
    ?>
  </body>
  </html>
