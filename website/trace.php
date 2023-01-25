<?php
include('header.php');
?>

<div class="welcome">
  <h2> Εργαλείο Εντοπισμού Κρουσμάτων </h2>
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
          <p> Συμπληρώστε το NFC ID του μολυσμένου πελάτη ώστε όλες οι πιθανές μεταδόσεις.</p>
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
        <h3 class="output"> Αποτελέσματα πιθανών μολυσμένων πελατών λόγω κρούσματος με NFC ID :
          <?php echo $customer_id; ?></h3>

          <table class="simple-table">
            <thead>
              <caption style="font-weight: bold">Πιθανά Κρούσματα</caption>
              <tr>
                <th> NFC ID </th>
                <th> Όνομα </th>
                <th> Επίθετο </th>
              </tr>
            </thead>

            <?php
            $query = "
            WITH
            infected_person_visits(
              customer_id,
              place_id,
              entry_date_time,
              exit_date_time
              ) AS(
                SELECT
                customer_id,
                place_id,
                entry_date_time,
                exit_date_time
                FROM
                visited
                WHERE
                visited.customer_id =".$customer_id."
              ),
              all_person_visits(
                customer_id,
                first_name,
                last_name,
                place_id,
                entry_date_time,
                exit_date_time
                ) AS(
                  SELECT
                  customer.nfc_id,
                  customer.first_name,
                  customer.last_name,
                  visited.place_id,
                  visited.entry_date_time,
                  visited.exit_date_time
                  FROM
                  customer
                  JOIN visited ON customer.nfc_id = visited.customer_id
                  )
                  SELECT DISTINCT
                  all_person_visits.customer_id,
                  all_person_visits.first_name,
                  all_person_visits.last_name
                  FROM
                  infected_person_visits
                  JOIN all_person_visits ON infected_person_visits.place_id = all_person_visits.place_id
                  WHERE
                  (
                    (
                      all_person_visits.entry_date_time BETWEEN infected_person_visits.entry_date_time AND DATE_ADD(
                        infected_person_visits.exit_date_time,
                        INTERVAL 1 HOUR
                        )
                        ) OR(
                          all_person_visits.exit_date_time BETWEEN infected_person_visits.entry_date_time AND DATE_ADD(
                            infected_person_visits.exit_date_time,
                            INTERVAL 1 HOUR
                            )
                            )
                            ) AND all_person_visits.customer_id <>".$customer_id;
                            $result = mysqli_query($conn, $query);
                            while($rows_data = mysqli_fetch_assoc($result))
                            {
                              ?>
                              <tr>
                                <td><?php echo $rows_data['customer_id'] ?></td>
                                <td><?php echo $rows_data['first_name'] ?></td>
                                <td><?php echo $rows_data['last_name'] ?></td>
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
