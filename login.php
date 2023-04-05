<?php
session_start();
ob_start();

include ("./config.php");
include(ROOT_PATH . "/includes/conn.php");
include (ROOT_PATH."/includes/head-section.php");
?>
  <body class="text-center bg-light">
    
<main class="form-signin container mt-5">
<br /><br />
  <form  class="p-4" method="post" action="./server.php">
  <?php
    if(isset($_SESSION["error"])){echo '<h3 class="text-danger"><i>'.$_SESSION["error"].'</i></h3>';unset($_SESSION["error"]);} 
    else{echo "<h1>Login</h1>";}
  ?>
<br />
    <div class="form-floating mt-4">
      <input type="text" class="form-control" id="floatingInput" placeholder="username" name="username"  required>
      <label for="floatingInput">Username </label>
    </div>
    <div class="form-floating mt-4">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password"  required>
      <label for="floatingPassword">Password</label>
    </div>

    <br />
    <button type="submit" class="w-100 btn btn-lg btn-primary mt-4" name="login">Sign in</button>
    <br /><br />
    <div class="d-flex justify-content-end  mt-4">
            <a href="./signup.php">Create New Account</a>
          </div>
  </form>
</main>


    
  </body>
</html>
