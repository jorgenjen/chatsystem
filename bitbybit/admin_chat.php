<?php
session_start();
  if (!isset($_SESSION["adminid"])) {
    header("Location: chat.html");
    die();
  }



 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Chat</title>
   </head>
   <body>
     <input type="text" id="msg" placeholder="Message">
     <input type="number" id="chatnumber" placeholder="To chatnumber">
     <button type="button" id="send-btn" onclick="sendVerify('btn')">Send</button>


     <div id="sendingsbekreftelse">

     </div>

     <script type="text/javascript">

     var msg = document.getElementById("msg");
     var chatNumber = document.getElementById("chatnumber");

     msg.addEventListener("keyup", sendVerify);
     chatNumber.addEventListener("keyup", sendVerify);

     function sendVerify(e){
       if (e.keyCode === 13) {
          sendMsg();
       }
       else if (e === "btn") {
         sendMsg();
       }
     }

     function sendMsg(){
        var xmlSend = new XMLHttpRequest();
        xmlSend.open("POST", "adminmsgsender.php", true);
        var params = "msg=" + msg.value + "&chatnr=" + chatNumber.value; // her setter jeg navn og value på objektet som jeg sender over og henter med $ _POST[""] i php
        xmlSend.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlSend.send(params);

        xmlSend.onreadystatechange = function () {
          if(this.readyState == 4 && this.status == 200){
            document.getElementById("sendingsbekreftelse").innerHTML = this.responseText;
          }
        }
     }
     </script>

   </body>
 </html>
