<?php
session_start();
ob_start();
include("./config.php");
include(ROOT_PATH . "/includes/conn.php");
include(ROOT_PATH . "/includes/head-section.php");

if(!isset($_SESSION["username"])){
  if(!isset($_SESSION["error"])){ $_SESSION["error"]= "You must login First";}
  header('location:./login.php');
}

$username = $_SESSION["username"];

$user = mysqli_query($db, "SELECT * FROM Users WHERE Username = '$username' LIMIT 1");
$userInfo = mysqli_fetch_array($user);
$user_id = $_SESSION["id"];
$First_Name = $_SESSION["fname"];
$Last_Name = $_SESSION["lname"];

$msgs = mysqli_query($db, "SELECT * FROM Messages WHERE Sender_ID = '$username' OR Reciver_ID = '$username'");
$conversations = mysqli_fetch_array($msgs);

$unique_recivers = mysqli_query($db, "SELECT DISTINCT Reciver_ID FROM Messages WHERE Sender_ID = '$user_id' OR Reciver_ID = '$user_id' ORDER BY SentON DESC");

$users = mysqli_query($db, "SELECT * FROM Users WHERE NOT ID=$user_id");



?>

<body class="bg-light">

  <div class="navbar navbar-dark bg-dark shadow-sm absolute-top">
    <div class="container">
      <a href="#" class="navbar-brand d-flex align-items-center">
      <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32"><rect width="100%" height="100%" fill="#6f42c1"/></svg>
        <strong><?php echo $First_Name." ".$Last_Name[0]."."; ?></strong>
        <a href="./logout.php" class="btn btn-success">Sign out</a>
        <strong id="current-user">| <?php echo $First_Name." ". $Last_Name; ?><a href="./logout.php" class="btn btn-success">Sign out</a></strong>
      </a>
    </div>
  </div>
  <main class="container">
    <br /><br /><br />
    <form class="d-flex" method="post" action="server.php">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>

    <div class="my-3 p-3 bg-body rounded shadow-sm">
      <h6 class="border-bottom pb-2 mb-0">Chats </h6>
      <?php $index = -1; 
            while ($friend = $unique_recivers->fetch_assoc()) : 
            $index++; 
      ?>
        <a href="<?php 
                      $other_id = $friend["Reciver_ID"];
                      $last_conversation = mysqli_query($db, "SELECT Message FROM Messages WHERE (Sender_ID = '$user_id' AND Reciver_ID = '$other_id') OR (Sender_ID = '$other_id' AND Reciver_ID = '$user_id') ORDER BY SentON DESC");
                      $last_msg = mysqli_fetch_array($last_conversation)["Message"];
                      echo './chat.php?self=' . $user_id . '&&other=' . $other_id; ?>
                " class="btn text-start p-0 d-block">
           
            <?php
                  $other_user = mysqli_query($db, "SELECT * FROM Users WHERE (ID = '$other_id') and NOT(ID = $user_id) LIMIT 1");
                  $other_user_info = mysqli_fetch_array($other_user);
                  if(!$other_user_info){continue;}
                  $other_fname = $other_user_info["FirstName"];
                  $other_lname = $other_user_info["LastName"];
            ?>
          <div class="d-flex text-muted pt-3">
            <svg class="bd-placeholder-img flex-shrink-0 ms-3 me-3 rounded" width="32" height="32">
              <rect width="100%" height="100%" fill="#6f42c1" />
            </svg>
            <p class="pb-3 mb-0 small lh-sm">
              <strong class="d-block text-gray-dark"><?php echo $other_fname." ".$other_lname; ?></strong>
              <?php echo "&ensp;".$last_msg; ?>
            </p>
          </div>
        </a>
        <h6 class="border-bottom ms-3 me-3 mb-0"></h6>
      <?php 
          endwhile;
          if ($index == -1){
            echo'<h1 style="color: white;margin-top: 15%; text-align: center;"><i>No Conversation Yet.</i></h1>';
            }
      ?>
    </div>
    
    <div class="my-3 p-3 bg-body rounded shadow-sm">
      <h5 class="border-bottom pb-2 mb-0">Suggestions</h5>
      <?php $index = -1; while ($user = $users->fetch_assoc()) : $index++; ?>
        <div class="d-flex text-muted pt-3">
          <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32">
            <rect width="100%" height="100%" fill="#e83e8c" />
          </svg>
          <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
            <div class="d-flex justify-content-between">
              <?php
                  $fname = $user["FirstName"];
                  $lname = $user["LastName"];
                  $usersname = $user["Username"];
                  $id = $user["ID"];
              ?>
              <strong class="text-gray-dark"><?php echo $fname." ".$lname; ?></strong>
              <a href="<?php echo './chat.php?self=' . $user_id . '&&other=' . $id; ?>">Start Chat</a>
            </div>
            <span class="d-block">@<?php echo $usersname; ?></span>
          </div>
          <small class="d-block text-end mt-3">
          <a href="#"></a>
          </small>
        </div>
        <?php endwhile; if ($index == -1){echo'<h1 style="color: white;margin-top: 15%; text-align: center;"><i>No Suggesion found</i></h1>';}?>
      </div>
  </main>


  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>