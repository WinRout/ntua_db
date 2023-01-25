<?php
include('header.php');
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<div class="welcome">
  <h2> Πωλήσεις </h2>
</div>


<body>
  <table class="simple-table">
    <thead>
      <caption><b>Πωλήσεις ανά Υπηρεσία</b></caption>
      <tr>
        <th> Περιγραφή Χρέωσης </th>
        <th> Συνολικές Πωλήσεις Υπηρεσίας </th>
      </tr>
    </thead>

    <?php
    $query="select * from sales_per_service;";
    $result=mysqli_query($conn, $query);
    ?>
    <?php
    while($rows_data = mysqli_fetch_assoc($result))
    {
      ?>
      <tr>
        <td><?php echo $rows_data['charge_description'] ?></td>
        <td><?php echo $rows_data['total_service_sales'] ?></td>
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
