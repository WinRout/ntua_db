<?php
include('header.php');
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<div class="welcome">
  <h2> Πελατολόγιο </h2>
</div>


<body>
  <table class="content-table">
    <thead>
      <caption><b>Καταχωρήσεις Πελατών</b></caption>
      <tr>
        <th> NFC ID </th>
        <th> Όνομα </th>
        <th> Επίθετο </th>
        <th> Αριθμός Τηλεφώνου </th>
        <th> Email </th>
        <th> Ημερομηνία Γεννήσεως </th>
        <th> Αριθμός Εγγράφου </th>
        <th> Τύπος </th>
        <th> Αρχή Έκδοσης</th>
      </tr>
    </thead>

    <?php
    $query = "
    SELECT nfc_id, first_name, last_name,
    date_of_birth, document_number, document_type, document_authority
    FROM customer ORDER BY nfc_id;";
    $result = mysqli_query($conn, $query);
    ?>
    <?php
    while($rows_data = mysqli_fetch_assoc($result))
    {
      ?>
      <tr>
        <td><?php echo $rows_data['nfc_id'] ?></td>
        <td><?php echo $rows_data['first_name'] ?></td>
        <td><?php echo $rows_data['last_name'] ?></td>
        <td>
          <?php
          $query_phone = "select phone_number from customer_phone where customer_id =".$rows_data['nfc_id'];
          $result_phone = mysqli_query($conn, $query_phone);
          ?>
            <?php
            while ($rows_phone = mysqli_fetch_assoc($result_phone)){
              ?>
              <?php echo $rows_phone['phone_number'];
              ?>
              <br>
              <?php
            }
            ?>
        </td>
        <td>
          <?php
          $query_email = "select email from customer_email where customer_id =".$rows_data['nfc_id'];
          $result_email = mysqli_query($conn, $query_email);
          ?>
            <?php
            while ($rows_email = mysqli_fetch_assoc($result_email)){
              ?>
              <?php echo $rows_email['email'];
              ?>
              <br>
              <?php
            }
            ?>
        </td>
        <td><?php echo $rows_data['date_of_birth'] ?></td>
        <td><?php echo $rows_data['document_number'] ?></td>
        <td><?php echo $rows_data['document_type'] ?></td>
        <td><?php echo $rows_data['document_authority'] ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
</body>

</html>

<?php
include ("footer.php")
?>
