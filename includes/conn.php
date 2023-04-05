<?php

//######################################
####Create connection ##################
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "YeneChat";
$db = new mysqli($servername, $username, $password);

$create_db = "CREATE DATABASE IF NOT EXISTS ".$dbname;
$db->query($create_db);

$use_db = "USE ".$dbname;
$db->query($use_db);

$createUsersTable = "CREATE TABLE IF NOT EXISTS Users (
    ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    FirstName VARCHAR(20),
    LastName VARCHAR(20),
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(2555),
	Bio VARCHAR(300),
    RegisteredON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    LastseenON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
$db -> query($createUsersTable);

$createMessagesTable = "CREATE TABLE IF NOT EXISTS Messages (
    ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    Sender_ID INT NOT NULL,
    Reciver_ID INT NOT NULL,
    SentON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Message TEXT NOT NULL,

    FOREIGN KEY (Sender_ID) REFERENCES Users(ID),
    FOREIGN KEY (Reciver_ID) REFERENCES Users(ID)
    )";
$db -> query($createMessagesTable);

?>