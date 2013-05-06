<?php require_once("includes/header.php"); ?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>

<?php
  $errors = array();
  if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $enrol = $_POST['enrol'];
    $password = $_POST['password'];
    $retyped_password = $_POST['retyped_password'];
    $colg_name = $_POST['colg_name'];
    $branch_name = $_POST['branch_name'];
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
    
    if($password != $retyped_password){
      $errors[] = "Password doesn't match";
    }
    if(empty($errors)){
      $hashed_password = sha1($password);
      $query = "INSERT INTO user 
		(username, hashed_password
		) VALUES (
		  '{$username}', '{$hashed_password}'
		  )";
      $result = mysql_query($query, $connection);
      echo $result;
      if($result){
	echo "User was successfully created.";
      } else {
	echo "Failed".mysql_error();
      }
    } else {
      for($i = 0;$i < count($errors); $i++){
	echo $errors[$i]."<br \>";
      }
    }
  } else {
    $username = "";
    $password = "";
    $enrol = "";
    $colg_name = "";
    $branch_name = "";
  }
?>

<div class="container">
<form action="register.php" method="post">
  <table>
    <tr>
      <td>Username:</td>
      <td><input type="text" name="username" maxlength="30" 
		 value="<?php echo htmlentities($username); ?>"></td>
    </tr>
    <tr>
      <td>Your Enrollment No:
      <td><input type="text" name="enrol" maxlength="30" 
		 value="<?php echo htmlentities($enrol); ?>"></td>
    </tr>
    
    <tr>
      <td>College Name:
      <td><input type="text" name="colg_name" maxlength="30" 
		 value="<?php echo htmlentities($colg_name); ?>"></td>
    </tr>
    
    <tr>
      <td>Branch Name:
      <td><input type="text" name="branch_name" maxlength="30" 
		 value="<?php echo htmlentities($branch_name); ?>"></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td><input type="password" name="password" maxlength="30" 
	      value=""/></td>
    </tr>
    <tr>
      <td>Confirm Password:</td>
      <td><input type="password" name="retyped_password" maxlength="30" 
	      value=""/></td>
    </tr>
    <tr>
      <td> <input class="button" type="reset" name="reset" value="Reset"/></td>
      <td><input class="button" type="submit" name="submit" 
value="Register"/></td>
    </tr>
  </table>
</form>
<?php require_once("includes/footer.php"); ?>
