<?php
include "connect.php";
session_start([
    'cookie_lifetime' => 2592000,
]);
  $sEmail = $_POST["semail"];
  $msg = $_POST["msg"];

  if ($sEmail == "true") {
     if (isset($_SESSION["userid"])) {
        //dette kjører om personen har en aktiv session men har gått over to timer siden han sendte sin første melding
       echo "du har vert her før du for over to timer siden. din id er " . $_SESSION["userid"] . "<br>";
       if (isset($_SESSION["chats"])) {
         //dobbelt sjekker at han har session med chat skal ikke gå an å komme hit uten men jaja
         echo "her jeg er her kun for å teste om denne if faktisk ble rett men det skal den da vere";
         sendMsg();
       }
       else {
         //om denne kjører er det egentlig noe galt som skjer da bruker skal ha chat variabel om han har bruker variabel som sjekket over men skader jo ikke bortsett fra runtime men who cares
         createChat();
         sendMsg();
       }
     }
     else {
       // denne kjører på helt nye brukere som ikke har sendt innen de siste to timene og ikke har en session fra tidligere
       randIdentify(); // her opprettes bruker og session som lagrer id
       createChat();
       sendMsg();
       }
     }
  else {
    // denne kjører om personen har sendt en melding innen de siste to timene og da har han også en session gående (husk å sett session til å vare lengre en kun browser vinduet)
    sendMsg();
  }

  function sendMsg(){
    GLOBAL $pdo, $msg;
    date_default_timezone_set("Europe/Oslo");
    $date = date("Y-m-d H:i:s");

    $newMsg = $pdo->prepare("INSERT INTO messages(message, chatid, admin, timeinfo) VALUES(?, ?, ?, ?)");
    $newMsg->execute([$msg, $_SESSION["chats"][0], false, $date]);

    if (isset($_SESSION["lastMsgid"])) {
      echo "before: " . $_SESSION["lastMsgid"];
      $findMsgid = $pdo->prepare("SELECT msgid FROM messages WHERE chatid = ? AND msgid > ? ORDER BY msgid DESC");
      $findMsgid->execute([$_SESSION["chats"][0], $_SESSION["lastMsgid"]]);
      $findMsgidResult = $findMsgid->fetchAll();

      $_SESSION["lastMsgid"] = $findMsgidResult[0]["msgid"];

      echo "after: " . $_SESSION["lastMsgid"];
    }
    else{
      $findMsgid = $pdo->prepare("SELECT msgid FROM messages WHERE chatid = ? ORDER BY msgid DESC");
      $findMsgid->execute([$_SESSION["chats"][0]]);
      $findMsgidResult = $findMsgid->fetchAll();

      $_SESSION["lastMsgid"] = $findMsgidResult[0]["msgid"];
      echo "lastMsgid: " . $_SESSION["lastMsgid"];
    }

    $newMsgNoti = $pdo->prepare("UPDATE chats SET newusermsg = ? WHERE cid = ?");
    $newMsgNoti->execute([true, $_SESSION["chats"][0]]);
    echo "du sendte en meld!";
  }

  function createChat(){
    GLOBAL $pdo;
    $newTest = $pdo->prepare("INSERT INTO chats(userid, typingadmin, typinguser, adminid) VALUES (?, ?, ?, ?)");
    $newTest->execute([$_SESSION["userid"], false, false, 0]);

    $findChatId = $pdo->prepare("SELECT cid FROM chats WHERE userid = ?");
    $findChatId->execute([$_SESSION["userid"]]);
    $foundChatId = ($findChatId->fetchAll())[0]["cid"];

    $_SESSION["chats"] = array($foundChatId);
    // $newTest = $pdo->query("INSERT INTO simpletest(admintyping, userid) VALUES (0, 9)"); // denne fungerer

  }

  function randIdentify(){
    GLOBAL $pdo, $msg;
    $chars = array_merge(range('a','z'), range(0,9), range('A','Z'));
    $randId;
    $randId = '';
    $lengde = rand(6, 8);
    for ($i=0; $i < $lengde; $i++) {
      $randNum = rand(0, (count($chars)-1));
      $randId .= $chars[$randNum];

    }
    $existence = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $existence->execute([$randId]);
    if ($existence->fetch()) {
      // kjører om tilfeldig id er gitt til noen andre før
      $randId = '';
      randIdentify();
    }
    else {
      $newUser = $pdo->prepare("INSERT INTO users(email, password) VALUES (?, NULL)"); //denne fungerer men trenger en fiskjonel id som jeg setter i epost feltet for å skaffe
      $newUser->execute([$randId]);

      $findId = $pdo->prepare("SELECT id FROM users WHERE email = ?"); // kan muligens droppe dette steg og opprette en session hvor tilfeldig id er lagret i steden og i det epost oppdateres henter jeg ut id legger det i session unsetter session email for å så oppdatere email feltet
      $findId->execute([$randId]);
      $foundId = ($findId->fetchAll())[0]["id"];

      $_SESSION["userid"] = $foundId;

      // echo "Rundt denne parantesen er din egen helt spessiele ID (" . $_SESSION["userid"] . ") og her er meldingen du sendte" . $msg;
      // kall en send mail function da jeg må sende mail i to tilfeller til meg selv....  her kommer epost scriptet inn som sender meg meldingen.. bruk php mailer men ikke html mail send den som ren text mail
    }
  }


 ?>
