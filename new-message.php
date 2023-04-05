<?php
    include "./includes/conn.php";

    $self_id = $_GET["self"];
    $other_id = $_GET["other"];
    $unseen_messages = mysqli_query($db, "SELECT * FROM Messages WHERE (Sender_ID = $other_id AND Reciver_ID = $self_id) AND Status = 'delivered' LIMIT 1");
    if($unseen_messages->num_rows > 0){  
        $unseen_message = $unseen_messages->fetch_assoc();        
        $msg_id = $unseen_message["ID"];
        $new_message["text"] = $unseen_message["Message"];
        $new_message["time"] = $unseen_message["SentON"];

        echo json_encode($new_message);
        $db->query("UPDATE Messages SET Status='seen' WHERE ID=$msg_id");
    }
    else echo json_encode(array());
?>