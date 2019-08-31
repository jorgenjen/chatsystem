<?php
  include "connect.php";
  session_start();

  // print_r($_SESSION["chats"]);

  function array_push_assoc($array, $key, $value){
    // echo "string er en int bare avansert!";
  $array[$key] = $value;
  return $array;
  }


    if (isset($_SESSION["chats"])) {
      // henter kun en chat og det er den 0 indexserte chatten
      $getMsg = $pdo->prepare("SELECT message, admin FROM messages WHERE chatid = ? order by msgid ASC LIMIT 100");
      $getMsg->execute([$_SESSION["chats"][0]]);

      $result = [];
      $int = 0;



      while($row = $getMsg->fetch()){
          $result[] = $row;
          // echo "her er row: ";
          // print_r($row["admin"]);
      }

      for ($i=0; $i < count($result); $i++) {
        // print_r($result[$i]["message"]);
        // echo "<br><br>";

        if ($result[$i]["admin"] == 0) {
           // trenger ikke sette denne til noe $result[$i] = array_push_assoc($result[$i], 'name', "user");
           // print_r($result);
           // echo "<br><br>";
        }
        else {
          $findAdmin = $pdo->prepare("SELECT name FROM admins WHERE adminid = ?");
          $findAdmin->execute([$result[$i]["admin"]]);
          $admin = $findAdmin->fetchAll()[0]["name"];
          // echo "<br>Dette er min admin: " . $admin;
          // echo "<br><br><br>dette er resultat fra func ";
          // print_r(array_push_assoc($result[$i], 'name', $admin));
            // echo "Rundenr: " . $i . " av ". count($result) . " dette er en admin runmde: <br>";
           $result[$i] = array_push_assoc($result[$i], 'name', $admin);  // slik pusher man inn i et assosiative array men key trenger den funksjonen over for åp gjøre dette

            // print_r($result);
            // echo "<br><br>";
        }

      }

      //
      // $int = $int + 1;
      //
      // print_r($result);

      echo json_encode($result);



    }

 ?>
