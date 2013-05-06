<?php require_once("includes/header.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php require_once("includes/sidepanel.php"); ?>

<?php
  if(isset($_POST['submit'])){
    $enroll = mysql_prep($_POST['enroll']);
    $message = show_tt($enroll);
    
  }
?>
<div id="contenttext">
  <span class="title_blue">Welcome to ClassLog!</span><br />
  <span class="subtitle_gray">Your logs belong to here</span><br />
  <br />
  <br />
  <div class="enroll_box">
    <form method="post" action="index.php">
      <p><input  type="text" name="enroll" 
      value="<?php if(isset($enroll)){echo $enroll;}    ?>" 
	      placeholder="Enter ur enrollment no.">
      <input class="button" type="submit" name="submit">
    <form>
  </div>
  <div class="display-area">		
    <p class="body_text" align="justify">
      <?php 
	if(isset($message))
	  echo $message; 
      ?>
    </p>
  </div>
</div>
<?php require_once("includes/footer.php"); ?>
