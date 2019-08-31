<?php
// cerates admins
include "connect.php";

$aname = $_POST["aname"];
$_POST["password"];
$passwordverify = $_POST["passwordverify"];


if ((isset($_POST["aname"]) && isset($_POST["passwordverify"]) && isset($_POST["password"])) && ($_POST["passwordverify"] === $_POST["password"])) {


  $createAdmin = $pdo->prepare("INSERT INTO admins(name, apassword) VALUES(?, ?)");
  $createAdmin->execute([$aname, password_hash($passwordverify, PASSWORD_BCRYPT)]);
}
else {
  echo 'you messed up go back <a href="chat_admin_respondersite.html">here!</a>';
}



?>
