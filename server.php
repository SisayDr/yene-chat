<?php
session_start();
ob_start();

include("config.php");
include(ROOT_PATH . "/includes/conn.php");

unset($_SESSION["username"]);
if (isset($_POST["login"])) {
// To Avoid SQL injection
    $username = mysqli_real_escape_string($db, stripslashes($_POST["username"]));
    $password = md5(mysqli_real_escape_string($db, stripslashes($_POST["password"])));
    
// Get user from the db if exists
    $user = mysqli_query($db, "SELECT * FROM Users WHERE Username = '$username' AND Password='$password' LIMIT 1");
    $userInfo = mysqli_fetch_array($user);

    if ($userInfo) {
        $_SESSION["username"] = $username;
        $_SESSION["id"] = $userInfo["ID"];
        $_SESSION["fname"] = $userInfo["FirstName"];
        $_SESSION["lname"] = $userInfo["LastName"];

        header("location: ./index.php");

    } else {
        $_SESSION["error"] = 'Invalid Username / Password ';
        header("location: ./login.php");
    }
}
else if(isset($_POST["signup"])) {
    $username = mysqli_real_escape_string($db, stripslashes($_POST["username"]));;
    $fname = $_POST["first-name"];
    $lname = $_POST["last-name"];
    $pwd = md5(mysqli_real_escape_string($db, stripslashes($_POST["password"])));
    mysqli_query($db, "INSERT INTO Users (FirstName, LastName, Username, Password) VALUES ('$fname', '$lname', '$username', '$pwd')");

    $user = mysqli_query($db, "SELECT * FROM Users WHERE Username = '$username' LIMIT 1");
    $userInfo = mysqli_fetch_array($user);
    $_SESSION["username"] = $username;
    $_SESSION["id"] = $userInfo["ID"];
    $_SESSION["fname"] = $userInfo["FirstName"];
    $_SESSION["lname"] = $userInfo["LastName"];

    header("Refresh:0; url=index.php");
}



?>