<?php
session_start();
ob_start();

include ("./config.php");
include (ROOT_PATH."/includes/conn.php");
include (ROOT_PATH."/includes/head-section.php");

if(!isset($_GET["other"]) OR !isset($_GET["self"])){
  header("location:./index.php");
}
$self_id = $_GET["self"];
$other_id = $_GET["other"];
$other = mysqli_query($db, "SELECT * FROM Users WHERE ID = '$other_id' LIMIT 1");
$otherInfo = mysqli_fetch_array($other);


$fname = $otherInfo["FirstName"];
$lname = $otherInfo["LastName"];
$other_username = $otherInfo["Username"];


$_SESSION["self-id"] = $self_id;
$_SESSION["other-id"] = $other_id;


$conversations =  mysqli_query($db, "SELECT * FROM Messages WHERE (Sender_ID = $self_id AND Reciver_ID = $other_id) OR (Sender_ID = $other_id AND Reciver_ID = $self_id)");
$db->query("UPDATE Messages SET Status='seen' WHERE Sender_ID = $other_id");
?>
<body class="bg-light position-relative">
  <nav class="navbar navbar position-fixed bg-dark shadow-sm" style="z-index: 10000;">
    <div class="d-flex text-muted pt-2 ms-4">
		<svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32"><rect width="100%" height="100%" fill="#6f42c1"/></svg>
      <p class="pb-2 mb-0 small lh-sm">
        <strong class="d-block text-white"><?php echo $fname." ".$lname;?> </strong>
        <span class="d-block text-white">@<?php echo $other_username;?></span>
      </p>
    </div>
  </nav>
<main class="container">
<br /><br /><br /><br />
<?php 
    $index = -1;
    while ($msg = $conversations->fetch_assoc()) : 
    $index++; 

    $time = $msg["SentON"];
    $message = $msg["Message"];
    $sender_id = $msg["Sender_ID"];
    ?>
    <?php if ($sender_id == $other_id ): ?>
      <p class="py-1 px-2 medium d-inline-block lh-sm  bg-body card rounded" style="margin-right: 10%;">
          <span class="d-block text-primary text-start"><?php echo $message; ?></span>
        <small class="text-grey-dark" style="float: right;"><i><?php echo $time; ?></i></small>
        </p><br />
      <?php endif; ?>
      <?php if($sender_id == $self_id): ?>

        <p class="py-1 px-2  medium lh-sm  bg-body rounded card" style="margin-left: 20%;">
          <span class="d-block text-success text-end"><?php echo $message; ?></span>
          <small class=" text-body text-end" ><i><?php echo $time; ?></i></small>
        </p><br />
      <?php endif; ?>
<?php endwhile; if ($index == -1){}?>
</main>
<br /><br /><br />
<div class="px-4 py-2 fixed-bottom bg-body " style="max-width: 540px; margin: auto;">
  <form method="POST" action="./send_msgs.php">
	  <div class="input-group mb-3">
          <textarea type="text" class="form-control" placeholder="Type Your Message here. " aria-describedby="basic-addon2" name="Message" required></textarea>
          <button class="input-group-text" id="basic-addon2" type="submit" name="send_msg">send</button>
      </div>
      </form>
</div>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
      <?php
        echo "var self_id = ".$self_id.";\n"; 
        echo "other_id = ".$other_id.";\n"; 
      ?>
      window.onload = function(){
        window.scrollTo(0, document.body.scrollHeight);
        setInterval(() => {
          var xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
              let new_msg = JSON.parse(this.responseText);
              if(new_msg["text"] != null){
                let msg_content = new_msg["text"];
                let msg_time = new_msg["time"];

                let new_message_tag = '\
                  <p class="py-1 px-2 medium d-inline-block lh-sm  bg-body card rounded" style="margin-right: 10%;">\
                    <span class="d-block text-primary text-start">'+msg_content+'</span>\
                    <small class="text-grey-dark" style="float: right;"><i>'+msg_time+'</i></small>\
                  </p><br />';
                document.querySelector("main.container").innerHTML += new_message_tag;
                window.scrollTo(0, document.body.scrollHeight);
              }
            }
          }
        xhttp.open("GET", "new-message.php?self="+self_id+"&other="+other_id, true);
        xhttp.send();
        }, 1000);
      };
    </script>
  </body>
</html>


