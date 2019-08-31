<?php


  include "connect.php";
  session_start();


  $email = $_POST["email"];

  if (isset($_SESSION["userid"])) {
     $emailUpdate = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
     $emailUpdate->execute([$email, $_SESSION["userid"]]);

     echo $email;
  }

 ?>
