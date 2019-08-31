<?php
include "connect.php";

// $_POST["aname"]
// $_POST["password"]

try{
  if (isset($_POST["password"]) && isset($_POST["ident"])) {
    $findPass = $pdo->prepare("SELECT adminid, apassword FROM admins WHERE ident = ?"); #  adminid, apassword
    $findPass->execute([$_POST["ident"]]);
    $foundPass = $findPass->fetchAll();

    // $stmt = $pdo->prepare('SELECT * FROM admins WHERE email = ? AND status=?');
    // $stmt->execute([$email, $status]);


      if (password_verify($_POST["password"], $foundPass[0]["apassword"])) {
        session_start();
        $_SESSION["adminid"] = $foundPass[0]["adminid"];
        // echo "You are logged inn your adminid is: " . $_SESSION["adminid"];
        header("Location: admin_chat.php");
        die();
      }
      else {
        echo "feil brukernavn eller passord";
      }
  }
  else {
    echo "fyll ut alle felt lat sabb!";
  }
}
catch (Exception $e) {
  echo "error: " . $e->getMessage();
}

?>
