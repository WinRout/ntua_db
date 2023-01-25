<?php
include('header.php');
?>

<?php

function query_a($time, $age_low, $age_high){
  $query_a = "
  select place.place_name, place.number, count(*) as visit_count
  from (customer join visited on customer.nfc_id = visited.customer_id)
  join place on place.place_id = visited.place_id
  where (
    (timestampdiff(year, customer.date_of_birth, curdate())
    between ".$age_low." and ".$age_high.")
    and
    (timestampdiff(month, visited.entry_date_time, curdate()) <= ".$time.")
    )
    group by visited.place_id
    order by visit_count desc, place.place_name
    limit 10;";
    return $query_a;
  }

function query_b($time, $age_low, $age_high){
  $query_b = "
  select service_charge.charge_description, count(*) as use_count
  from service_charge join customer
  on service_charge.customer_id = customer.nfc_id
  where
  (service_charge.charge_description <> 'Room') and
  (timestampdiff(year, customer.date_of_birth, curdate())
  between ".$age_low." and ".$age_high.") and
  (timestampdiff(month, service_charge.charge_date_time, curdate()) <=".$time.")
  group by service_id
  order by use_count desc";
  return $query_b;
}

function query_c($time, $age_low, $age_high){
  $query_c = "
  with aux as (
    select distinct service_charge.customer_id, service_charge.charge_description
    from customer join service_charge on service_charge.customer_id = customer.nfc_id
    where(
    (service_charge.charge_description <> 'Room') and
    (timestampdiff(year, customer.date_of_birth, curdate())
    between ".$age_low." and ".$age_high.") and
    (timestampdiff(month, service_charge.charge_date_time, curdate()) <= ".$time."))
  )
  select charge_description, count(*) as use_count
    from aux
    group by aux.charge_description
    order by use_count desc;";
  return $query_c;
}

  ?>

  <div class="welcome">
    <h2> Στατιστικά Χρήσης Υπηρεσιών </h2>
  </div>

  <body class="stat-body">



    <h3> Τελευταίος Μήνας </h3>
    <?php $time="1"; ?>

    <h4>  Οι πιο πολυσύχναστοι χώροι: </h4>

<div class="stat-div">
    <table class="stat-table">
      <?php
      $age_low = "20";
      $age_high = "40";
      ?>
      <thead>
        <caption><b>Ηλικίες 20-40</b></caption>
        <tr>
          <th> Μέρος </th>
          <th> Αρίθμηση </th>
          <th> Σύνολο Επισκέψεων </th>
        </tr>
      </thead>

      <?php

      $query = query_a($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['place_name'] ?></td>
          <td><?php echo $rows_data['number'] ?></td>
          <td><?php echo $rows_data['visit_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>

<div class="stat-div">
    <table class="stat-table">
      <?php
      $age_low = "41";
      $age_high = "60";
      ?>
      <thead>
        <caption><b>Ηλικίες 41-60</b></caption>
        <tr>
          <th> Μέρος </th>
          <th> Αρίθμηση </th>
          <th> Σύνολο Επισκέψεων </th>
        </tr>
      </thead>

      <?php

      $query = query_a($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['place_name'] ?></td>
          <td><?php echo $rows_data['number'] ?></td>
          <td><?php echo $rows_data['visit_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>

<div class='stat-div'>
    <table class="stat-table">
      <?php
      $age_low = "61";
      $age_high = "200";
      ?>
      <thead>
        <caption><b>Ηλικίες 61+</b></caption>
        <tr>
          <th> Μέρος </th>
          <th> Αρίθμηση </th>
          <th> Σύνολο Επισκέψεων </th>
        </tr>
      </thead>

      <?php

      $query = query_a($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['place_name'] ?></td>
          <td><?php echo $rows_data['number'] ?></td>
          <td><?php echo $rows_data['visit_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>



    <h4>  Οι συχνότερα χρησιμοποιούμενες υπηρεσίες: </h4>

<div class="stat-div">
    <table class="stat-table">
      <?php
      $age_low = "20";
      $age_high = "40";
      ?>
      <thead>
        <caption><b>Ηλικίες 20-40</b></caption>
        <tr>
          <th> Υπηρεσία </th>
          <th> Συνολικές Χρήσεις </th>
        </tr>
      </thead>

      <?php

      $query = query_b($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['charge_description'] ?></td>
          <td><?php echo $rows_data['use_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>

<div class="stat-div">
    <table class="stat-table">
      <?php
      $age_low = "41";
      $age_high = "60";
      ?>
      <thead>
        <caption><b>Ηλικίες 41-60</b></caption>
        <tr>
          <th> Υπηρεσία </th>
          <th> Συνολικές Χρήσεις </th>
        </tr>
      </thead>

      <?php

      $query = query_b($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['charge_description'] ?></td>
          <td><?php echo $rows_data['use_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>

<div class="stat-div">
    <table class="stat-table">
      <?php
      $age_low = "61";
      $age_high = "200";
      ?>
      <thead>
        <caption><b>Ηλικίες 61+</b></caption>
        <tr>
          <th> Υπηρεσία </th>
          <th> Συνολικές Χρήσεις </th>
        </tr>
      </thead>

      <?php

      $query = query_b($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['charge_description'] ?></td>
          <td><?php echo $rows_data['use_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>






  <h4>  Οι υπηρεσίες που χρησιμοποιούνται από τους περισσότερους πελάτες: </h4>

  <div class="stat-div">
  <table class="stat-table">
    <?php
    $age_low = "20";
    $age_high = "40";
    ?>
    <thead>
      <caption><b>Ηλικίες 20-40</b></caption>
      <tr>
        <th> Υπηρεσία </th>
        <th> Συνολικοί Χρήστες </th>
      </tr>
    </thead>

    <?php

    $query = query_c($time, $age_low, $age_high);
    $result=mysqli_query($conn, $query);
    ?>
    <?php
    while($rows_data = mysqli_fetch_assoc($result))
    {
      ?>
      <tr>
        <td><?php echo $rows_data['charge_description'] ?></td>
        <td><?php echo $rows_data['use_count'] ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
  </div>

  <div class="stat-div">
  <table class="stat-table">
    <?php
    $age_low = "41";
    $age_high = "60";
    ?>
    <thead>
      <caption><b>Ηλικίες 41-60</b></caption>
      <tr>
        <th> Υπηρεσία </th>
        <th> Συνολικοί Χρήστες </th>
      </tr>
    </thead>

    <?php

    $query = query_c($time, $age_low, $age_high);
    $result=mysqli_query($conn, $query);
    ?>
    <?php
    while($rows_data = mysqli_fetch_assoc($result))
    {
      ?>
      <tr>
        <td><?php echo $rows_data['charge_description'] ?></td>
        <td><?php echo $rows_data['use_count'] ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
  </div>

  <div class="stat-div">
  <table class="stat-table">
    <?php
    $age_low = "61";
    $age_high = "200";
    ?>
    <thead>
      <caption><b>Ηλικίες 61+</b></caption>
      <tr>
        <th> Υπηρεσία </th>
        <th> Συνολικοί Χρήστες </th>
      </tr>
    </thead>

    <?php

    $query = query_c($time, $age_low, $age_high);
    $result=mysqli_query($conn, $query);
    ?>
    <?php
    while($rows_data = mysqli_fetch_assoc($result))
    {
      ?>
      <tr>
        <td><?php echo $rows_data['charge_description'] ?></td>
        <td><?php echo $rows_data['use_count'] ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
  </div>




    <h3> Τελευταίος Χρόνος </h3>
    <?php $time="12"; ?>

    <h4>  Οι πιο πολυσύχναστοι χώροι: </h4>

<div class="stat-div">
    <table class="stat-table">
      <?php
      $age_low = "20";
      $age_high = "40";
      ?>
      <thead>
        <caption><b>Ηλικίες 20-40</b></caption>
        <tr>
          <th> Μέρος </th>
          <th> Αρίθμηση </th>
          <th> Σύνολο Επισκέψεων </th>
        </tr>
      </thead>

      <?php

      $query = query_a($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['place_name'] ?></td>
          <td><?php echo $rows_data['number'] ?></td>
          <td><?php echo $rows_data['visit_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>

<div class="stat-div">
    <table class="stat-table">
      <?php
      $age_low = "41";
      $age_high = "60";
      ?>
      <thead>
        <caption><b>Ηλικίες 41-60</b></caption>
        <tr>
          <th> Μέρος </th>
          <th> Αρίθμηση </th>
          <th> Σύνολο Επισκέψεων </th>
        </tr>
      </thead>

      <?php

      $query = query_a($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['place_name'] ?></td>
          <td><?php echo $rows_data['number'] ?></td>
          <td><?php echo $rows_data['visit_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>

<div class="stat-div">
    <table class="stat-table">
      <?php
      $age_low = "61";
      $age_high = "200";
      ?>
      <thead>
        <caption><b>Ηλικίες 61+</b></caption>
        <tr>
          <th> Μέρος </th>
          <th> Αρίθμηση </th>
          <th> Σύνολο Επισκέψεων </th>
        </tr>
      </thead>

      <?php

      $query = query_a($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['place_name'] ?></td>
          <td><?php echo $rows_data['number'] ?></td>
          <td><?php echo $rows_data['visit_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>





    <h4>Οι συχνότερα χρησιμοποιούμενες υπηρεσίες: </h4>

<div class="stat-div">
    <table class="stat-table">
      <?php
      $age_low = "20";
      $age_high = "40";
      ?>
      <thead>
        <caption><b>Ηλικίες 20-40</b></caption>
        <tr>
          <th> Υπηρεσία </th>
          <th> Συνολικές Χρήσεις </th>
        </tr>
      </thead>

      <?php

      $query = query_b($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['charge_description'] ?></td>
          <td><?php echo $rows_data['use_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>

<div class="stat-div">
    <table class="stat-table">
      <?php
      $age_low = "41";
      $age_high = "60";
      ?>
      <thead>
        <caption><b>Ηλικίες 41-60</b></caption>
        <tr>
          <th> Υπηρεσία </th>
          <th> Συνολικές Χρήσεις </th>
        </tr>
      </thead>

      <?php

      $query = query_b($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['charge_description'] ?></td>
          <td><?php echo $rows_data['use_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>

<div class="stat-div">
    <table class="stat-table">
      <?php
      $age_low = "61";
      $age_high = "200";
      ?>
      <thead>
        <caption><b>Ηλικίες 61+</b></caption>
        <tr>
          <th> Υπηρεσία </th>
          <th> Συνολικές Χρήσεις </th>
        </tr>
      </thead>
      <?php

      $query = query_b($time, $age_low, $age_high);
      $result=mysqli_query($conn, $query);
      ?>
      <?php
      while($rows_data = mysqli_fetch_assoc($result))
      {
        ?>
        <tr>
          <td><?php echo $rows_data['charge_description'] ?></td>
          <td><?php echo $rows_data['use_count'] ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>

  <h4>Οι υπηρεσίες που χρησιμοποιούνται από τους περισσότερους πελάτες: </h4>

  <div class="stat-div">
  <table class="stat-table">
    <?php
    $age_low = "20";
    $age_high = "40";
    ?>
    <thead>
      <caption><b>Ηλικίες 20-40</b></caption>
      <tr>
        <th> Υπηρεσία </th>
        <th> Συνολικοί Χρήστες </th>
      </tr>
    </thead>

    <?php

    $query = query_c($time, $age_low, $age_high);
    $result=mysqli_query($conn, $query);
    ?>
    <?php
    while($rows_data = mysqli_fetch_assoc($result))
    {
      ?>
      <tr>
        <td><?php echo $rows_data['charge_description'] ?></td>
        <td><?php echo $rows_data['use_count'] ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
  </div>

  <div class="stat-div">
  <table class="stat-table">
    <?php
    $age_low = "41";
    $age_high = "60";
    ?>
    <thead>
      <caption><b>Ηλικίες 41-60</b></caption>
      <tr>
        <th> Υπηρεσία </th>
        <th> Συνολικοί Χρήστες </th>
      </tr>
    </thead>

    <?php

    $query = query_c($time, $age_low, $age_high);
    $result=mysqli_query($conn, $query);
    ?>
    <?php
    while($rows_data = mysqli_fetch_assoc($result))
    {
      ?>
      <tr>
        <td><?php echo $rows_data['charge_description'] ?></td>
        <td><?php echo $rows_data['use_count'] ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
  </div>

  <div class="stat-div">
  <table class="stat-table">
    <?php
    $age_low = "61";
    $age_high = "200";
    ?>
    <thead>
      <caption><b>Ηλικίες 61+</b></caption>
      <tr>
        <th> Υπηρεσία </th>
        <th> Συνολικοί Χρήστες </th>
      </tr>
    </thead>

    <?php

    $query = query_c($time, $age_low, $age_high);
    $result=mysqli_query($conn, $query);
    ?>
    <?php
    while($rows_data = mysqli_fetch_assoc($result))
    {
      ?>
      <tr>
        <td><?php echo $rows_data['charge_description'] ?></td>
        <td><?php echo $rows_data['use_count'] ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
  </div>


    <?php
    include ("footer.php")
    ?>
