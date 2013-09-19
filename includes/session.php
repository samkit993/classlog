<?php 
  session_start();
?>
<?php require_once("includes/functions.php"); ?>

<?php
  function logged_in(){
    return isset($_SESSION['user_id']);
  }
  function confirm_logged_in(){
    if(!logged_in()){
      redirect_to("index.php");
    }
  }
?>

