<?php
  session_start();
    if (!isset($_SESSION["adminid"])) {
      header("Location: chat.html");
      die();
    }
    include "connect.php";

    echo $_POST["msg"] . $_POST["chatnr"];

    if (isset($_POST["msg"]) && isset($_POST["chatnr"])) {
      $getAminId = $pdo->prepare("SELECT adminid FROM chats WHERE cid = ?");
      $getAminId->execute([$_POST["chatnr"]]);
      $adminId = ($getAminId->fetchAll())[0]["adminid"];
        if ($adminId === 0) {
          // denne sender  melding og oppdaterer admin id i chatr table
          echo "gotta update that admin id mate!";
          $updateAdminId = $pdo->prepare("UPDATE chats SET adminid = ? WHERE cid = ?");
          $updateAdminId->execute([$_SESSION["adminid"], $_POST["chatnr"]]);

          sendMessage();
        }
        else if ($adminId === $_SESSION["adminid"]) {
          // her er admin id den samme som den som har logget inn så trenger dermed ikke å oppdatere
          echo "send meld uten å oppdatere admin id";
          sendMessage();
        }
        else {
          // muligens oppdater denne slik at en annen admin kan komme inn og sende melding men da blir
          // jo meningen med admin id bort i chats mulig jeg bare kan slette den og hente
          // admins navn ut av hver eneeste meld slik at hvilken som helst admnin kan svare på alle chats ... IDK
          // her har noen andre startet å svare på chatten så denne adminen trenger ikke å skrive ergo får ikke tilatelse slik systemet står nå
          echo "noen andre fikser denne chatten ta en kopp og sip den opp!";
        }

    }

    function sendMessage(){
      GLOBAL $pdo;
      date_default_timezone_set("Europe/Oslo");
      $date = date("Y-m-d H:i:s");

      $newMsg = $pdo->prepare("INSERT INTO messages(message, chatid, admin, timeinfo) VALUES(?, ?, ?, ?)");
      $newMsg->execute([$_POST["msg"], $_POST["chatnr"], $_SESSION["adminid"], $date]);

      // legger inn i chat at det er kommet en ny mld
      $updateChat = $pdo->prepare("UPDATE chats SET newadminmsg = ? WHERE cid = ?");
      $updateChat->execute([true, $_POST["chatnr"]]);
    }

 ?>
