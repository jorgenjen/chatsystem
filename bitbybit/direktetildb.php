<?php
  session_start();
  include "connect.php";

  $mangeMld = $pdo->query("SELECT message, timeinfo FROM messages WHERE msgid > 75 ORDER BY msgid desc");

  $result = [];

  while($row = $mangeMld->fetch()){
      $result[] = $row;
      // print_r($row);
  }
  print_r($result);
  echo "<br><br><br><br><br>";

  print_r($result[0]);
  echo "<br><br><br><br><br>";

  array_push($result[0], 'Jorgen');  // her Pusher man inn i array men key blir her 0 isteden for et navn dermed er metoden under bedre
  print_r($result[0]);
  echo "<br><br><br><br><br>";

  function array_push_assoc($array, $key, $value){
  $array[$key] = $value;
  return $array;
  }

  $result[0] = array_push_assoc($result[0], 'name', 'Jensvold');  // slik pusher man inn i et assosiative array men key trenger den funksjonen over for åp gjøre dette
  print_r($result);



// dette brukes mens jeg danner array som skal bli til jason slik at jeg kan pushe inn verdier fra andre db som admin navn og user navn for den andre siden Lurt og bruke den key metoden nederst der

  // $result->name = "Jorgen";

   // echo json_encode($result);  // her danner jeg og sender json ovver til js

   // echo $result[0];


 ?>
