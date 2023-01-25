
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Hotel Database</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <a href="https://localhost/phpmyadmin" target="_blank">
      <img class="logo" src="images/logo.png" alt="logo" width="50" height="50">
    </a>
    <h1 class="logo"> Hotel Database </h1>

    <?php
    include('connect.php');
    ?>

    <nav class="navbar">
      <ul class="main_menu">
        <li><a href="index.php"><img src="images/home.ico" height=20px width=20px></a></li>
        <li><a href="customers.php">Πελατολόγιο</a></li>
        <li><a href="sales.php">Πωλήσεις</a></li>
        <li><a href="services.php">Υπηρεσίες και Χρήσεις</a></li>
        <li><a href="history.php">Ιστορικό Επισκέψεων</a></li>
        <li><a href="trace.php">Εργαλείο Εντοπισμού Κρουσμάτων</a></li>
        <li><a href="statistics.php">Στατιστικά</a></li>
        <li><a href="about.php">Σχετικά</a></li>
      </nav>
    </header>
  </body>
  </html>
