<?php include "admin_authenticate.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>view</title>
 
  <style>
    body {
      background-color: #1690a7;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      flex-wrap: wrap;
    }

    .alb {
      width: 200px;
      height: 200px;
      padding: 5px;
    }

    .alb img {
      width: 100%;
      height: 100%;
    }

    a {
      float: right;
      background: #555;
      padding: 10px 15px;
      color: #fff;
      border-radius: 5px;
      margin-right: 10px;
      border: none;
      text-decoration: none;
    }

    a:hover {
      opacity: 0.7;
    }

    .image-container {
      text-align: center;
    }

    .image-container img {
      display: block;
      margin: 0 auto;
    }

    .logout-btn {
      float: none;
      display: block;
      background: #555;
      padding: 10px 15px;
      color: #fff;
      border-radius: 5px;
      margin-top: 50px;
      margin-right: 400px;
      margin-left: 400px;
      text-decoration: none;
    }

    .logout-btn:hover {
      opacity: 0.7;
    }
  </style>
</head>

<body>
  <a href="Gallery.php">&#8592;</a>
  <?php
  $sql = "SELECT * FROM image_upload ORDER BY id DESC";
  $res = mysqli_query($con, $sql);

  if (mysqli_num_rows($res) > 0) {
    while ($image_upload = mysqli_fetch_assoc($res)) { ?>
      <div class="alb">
        <img src="media/<?= trim($image_upload['image']) ?>?<?= uniqid() ?>">
      </div>
  <?php }
  }
  ?>
  <div class="image-container">

    <a href="admin_logout.php" class="logout-btn">Logout</a>
  </div>

</body>

</html>