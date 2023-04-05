<?php
session_start();
ob_start();

include("config.php");
include(ROOT_PATH . "/includes/conn.php");


if (isset($_POST["send_msg"])) {


    $msg = $_POST["Message"];
    $sender_id = $_SESSION["self-id"];
    $reciver_id = $_SESSION["other-id"];

    mysqli_query($db, "INSERT INTO Messages (Sender_ID, Reciver_ID, Message) VALUES ('$sender_id', '$reciver_id', '$msg')");
    header("Refresh:0; url=./chat.php?self=$sender_id&&other=$reciver_id");

}



?>