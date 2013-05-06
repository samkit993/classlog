<?php require_once("includes/header.php"); ?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>

<?php
  $messages = array();
  $errors = array();
  if(isset($_POST['submit'])){
    $username = mysql_prep($_POST['username']);
    $password = mysql_prep($_POST['password']);
    
    //sort of validation
    if(empty($username) || empty($password)){
      $errors[] = "Both fields are mandatory"."<br />";
    } else {
      if(strlen($username) > 10){
	$errors[] = "Too long username";
      }
      if(strlen($password) < 6){
	$errors[] = "Too short password";
      }
    }
    
    if(empty($errors)){
      $hashed_password = sha1($password);
      $query = "SELECT username FROM user 
		WHERE username=\"{$username}\" AND 
hashed_password=\"{$hashed_password}\" LIMIT 1;";
      $result = mysql_query($query, $connection);
      confirm_query($result);
      $found_user = mysql_fetch_array($result);
      
      if($found_user['username']==$username){
	$messages[] = "User was successfully logged in.";
      } else {
	$messages[] = "Combination of username and password is 
incorrect".mysql_error();
      }
    }
  } else {
    $username = "";
    $password = "";
  }
?>
<div class="container">
  <div class="login">
    <h1>Login
    </h1>
    <p><?php
      if(!empty($errors)){
	for($i=0; $i<sizeof($errors); $i++){
	  echo $errors[$i];
	}
      }
      if(!empty($messages)){
	for($i=0; $i<sizeof($messages); $i++){
	  echo $messages[$i];
	}
      }
    ?>
    <form method="post" action="login.php">
      <p><input type="text" name="username" 
      value="<?php if(!empty($username))echo htmlentities($username); ?>" 
      placeholder="Username 
or Email">
      </p>
      <p><input type="password" name="password" value="" 
	placeholder="Password">
      </p>
      <p class="remember_me">
	<label>
	<label>
	  <input type="checkbox" name="remember_me" id="remember_me"> Remember 
me on this computer
	</label>
	</label>
      </p>
      <p class="submit"><input class="button" type="submit" name="submit" 
      value="Login"></p>
    </form>
  </div>
  
  <div class="login-help">
    <p>Forgot your password? <a href="#">Click here</a>.</p>
  </div>
  <div class="create-account">
    <p>Dont' have an account ? <a href="register.php">Create Account</a></p>
  </div>
</div>
<?php require_once("includes/footer.php"); ?>	