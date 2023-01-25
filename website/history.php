<?php
include('header.php');
?>

<div class="welcome">
  <h2> Ιστορικό Επισκέψεων </h2>
</div>

<body>
  <div class="extra">
    <form action="" method="post">
      <br><p>Εάν δεν γνωρίζετε το NFC ID του πελάτη, συμπληρώστε τα στοιχεία του παρακάτω: <br></p>
      <label for="first_name">Όνομα</label>
      <input type = "text" id="first_name" name="first_name" value="">
      <label for="last_name">Eπίθετο</label>
      <input type = "text" id="last_name" name="last_name" value="">
      <label for="document_number">Αριθμός Εγγράφου</label>
      <input type = "text" id="document_number" name="document_number" value="">
      <label><input type="submit" name="submit_nfc" value="Βρες NFC ID"></label>
    </form>

    <?php
    if(isset($_POST['submit_nfc'])){
      $first_name=" first_name='".$_POST['first_name']."'";
      $last_name=" and last_name='".$_POST['last_name']."'";
      $document_number=" and document_number='".$_POST['document_number']."'";
      $query = "select nfc_id from customer where".$first_name.$last_name.$document_number.";";
      $result = mysqli_query($conn, $query);
      $rows_data = mysqli_fetch_assoc($result);
      $nfc_id = $rows_data['nfc_id']
      ?>
      <?php if($nfc_id) {
        ?>
        <p>To NFC του πελάτη "<?php echo $_POST['first_name']." "; echo $_POST['last_name']; ?>" είναι :<b>
          <?php echo $rows_data['nfc_id']; ?></b></p>
          <?php
        }
        else {
          echo $nfc_id;
          echo "Δεν αντιστοιχεί κάποιο bracelet στον πελάτη ";
          echo $_POST['first_name']." "; echo $_POST['last_name'];
          echo ". Βεβαιωθείτε πως έχετε συμπληρώσει όλα τα πεδία σωστά.";
        }
        ?>

        <?php
      }
      ?>

      <div class="inputs">
        <form action="" method="post">
          <br><br><h4>Στοιχεία Πελάτη:</h4>
          <p> Συμπληρώστε το NFC ID του πελάτη ώστε να εμφανιστούν όλες οι επισκέψεις του στο ξενοδοχείο.</p>
          <div class="text">
            <div class="text_input">
              <label for="nfc_id">NFC ID</label>
            </div>
            <input type="text" id="nfc_id" name="nfc_id" value="<?php echo $rows_data['nfc_id']; ?>">
            <label><input class="button" type="submit" name="submit" value="Καταχώρηση"></label>
          </div>
        </form>
      </div>
    </div>

    <?php
    if(isset($_POST['submit'])){
      if(!empty($_POST['nfc_id'])) {
        $customer_id = $_POST['nfc_id'];
        ?>
        <br><br>
        <h3 class="output"> Αποτελέσματα Επισκέψεων Πελάτη με NFC ID :
          <?php echo $customer_id; ?></h3>
          <table class="content-table">
          <thead>
            <caption style="font-weight: bold">Επισκέψεις</caption>
            <tr>
              <th> ID Χώρου </th>
              <th> Ονομασία Χώρου </th>
              <th> Αρίθμηση Χώρου </th>
              <th> Όροφος </th>
              <th> Διάδρομος </th>
              <th> Άφιξη </th>
              <th> Αναχώρηση </th>
            </tr>
          </thead>

          <?php
          $query = "
          select place.place_id, place_name,
          number, floor_number, corridor, entry_date_time, exit_date_time
          from visited join place on place.place_id = visited.place_id
          where customer_id=".$customer_id." order by entry_date_time;";
          $result = mysqli_query($conn, $query);
          while($rows_data = mysqli_fetch_assoc($result))
          {
            ?>
            <tr>
              <td><?php echo $rows_data['place_id'] ?></td>
              <td><?php echo $rows_data['place_name'] ?></td>
              <td><?php echo $rows_data['number'] ?></td>
              <td><?php echo $rows_data['floor_number'] ?></td>
              <td><?php echo $rows_data['corridor'] ?></td>
              <td><?php echo $rows_data['entry_date_time'] ?></td>
              <td><?php echo $rows_data['exit_date_time'] ?></td>
            </tr>
            <?php
          }
          ?>
        </table>

        <?php
      }
      else {
        echo 'Συμπλήρωσε το NFC ID του πελάτη.';
      }
    }
    ?>
    <?php
    include ("footer.php")
    ?>
