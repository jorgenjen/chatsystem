<?php
  include "connect.php";
  session_start();


  $chatnr = $_SESSION["chats"][0];


   //true og false lagres i db som, 0 for false og 1 for true kan sammenlignes med false og true med dobbel elik men trippel lik fungerer kun med 0 og 1 obv


  if (isset($_SESSION["userid"]) && isset($chatnr)) {


    // $mangeMld = $pdo->query("SELECT message, timeinfo FROM messages WHERE msgid > 26 ORDER BY msgid desc");
    //
    // $result = [];
    //
    // while($row = $mangeMld->fetch()){
    //     $result[] = $row;
    // }

    // echo json_encode($result);  // her danner jeg og sender json ovver til js

     // echo $result;

     // // IDEA: se fil for direktetildb for hvordan denne skal løses med push in i array!!
    // $uid = $_SESSION['userid'];
    $updateCheck = $pdo->prepare("SELECT userid, typingadmin, newadminmsg FROM chats WHERE cid = ?"); //AND cid = ? dette fungerer da tydligvis ikke så trenger en ekstra linje for some reason....
    $updateCheck->execute([$chatnr]); //, $chatnr
    $update = $updateCheck->fetchAll();
      if ($_SESSION['userid'] === $update[0]["userid"]) {

        // echo "dette er din rettmessige chatt å se ";
        if ($update[0]["newadminmsg"] == true) {
          // her har det kommet en ny melding fra admin så må¨hente inn den siste meldingen og få den printet
          // må fjerne at det er en ny melding;
          $updated = $pdo->prepare("UPDATE chats SET newadminmsg = ? WHERE cid = ?");
          $updated->execute([false, $chatnr]);

          // echo "new message her mate! ". $_SESSION["chats"][0];

          // IDEA her må vi hente ut de nye meldingene:

            // echo "lastMsgid er: " . $_SESSION["lastMsgid"];

          $newMsg = $pdo->prepare("SELECT msgid, message, admin, timeinfo FROM messages WHERE msgid > ? ORDER BY msgid ASC"); //ORDER BY msgid ASC //AND chatid = ?
          $newMsg->execute([$_SESSION["lastMsgid"]]);   //, $chatnr

          // $newMsgFound = $newMsg->fetchAll()[0]["message"];
          // $_SESSION["lastMsgid"] = $newMsgFound;
          // echo "<br><br><br><br><br>";

          // echo $newMsgFound;
          // echo "<br><br><br><br><br>";

          $result = [];

          while($row = $newMsg->fetch()){
              $result[] = $row;
          }

           echo json_encode($result);

          // IDEA denne printer nå kun melding som er ny for bruker dope!
          $lastIndex = count($result) - 1;
          $lastMsgid = $result[$lastIndex]["msgid"];
          // echo "<br><br> printer denne som er det session blir nå:" . $lastMsgid;
          $_SESSION["lastMsgid"] = $lastMsgid;

          // IDEA dette er for etterpå for å legge innrett admin navn osv
          // function array_push_assoc($array, $key, $value){
          // $array[$key] = $value;
          // return $array;
          // }
          //
          // $result[0] = array_push_assoc($result[1], 'name', 'Jensvold');



          // echo "<br><br><br><br><br><br><br>";
          // print_r($result);
          // echo "<br><br><br><br><br><br><br>";
          // echo $lastIndex;
        }
        if ($update[0]["typingadmin"] == true) {
          // her skirver admin så må få admin en animasjon/tekst som informerer om at han skriver
          echo "admin is typing a msg get ready!";
        }
                       // $getMsgs = $pdo->prepare("SELECT message FROM messages WHERE msgid > ? AND chatid = ?"); // FUNKER NÅ!
                       // $getMsgs->execute([$chatnr, $chatnr]); // funker nå! .... $_SESSION['userid'],
                       // $theMsg = ($getMsgs->fetchAll())[1]["message"];
                       //
                       // echo $theMsg;



        // her sjekker du om det er kommet en ny melding er det det må den hentes og sendes tilbake til ja ved ajax samt også sjekke om admin skriver om admin skrive skal en skrive animasjon komme opp
        // die();
      }
      else {
        // echo " dette er ikke din chat f off!! du er bruker nummer: " . $_SESSION['userid'];
      }

    // $findId = $pdo->prepare("SELECT id FROM users WHERE email = ?"); // kan muligens droppe dette steg og opprette en session hvor tilfeldig id er lagret i steden og i det epost oppdateres henter jeg ut id legger det i session unsetter session email for å så oppdatere email feltet
    // $findId->execute([$randId]);
    // $foundId = ($findId->fetchAll())[0]["id"];

    // echo $update[0]["typingadmin"];

    // echo "admin skriver: " . $update[0]["typingadmin"] . " ny admin melding: " . $update[0]["newadminmsg"];
      // her er info om det er komet en ny melding og om admin skriver

  }

?>
