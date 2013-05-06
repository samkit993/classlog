<?php require_once("header.php");?>
<?php
  //function for confirming query
  function confirm_query($result_set){
      if(!$result_set){
		die("Database query failed: ".mysql_error());
	}
  }
  //function for storing appropirately form variable in php variables
  function mysql_prep($value){
    $magic_quotes_active = get_magic_quotes_gpc();
    $new_enough_php = function_exists("mysql_real_estate_string");
    
    if($new_enough_php){
      //undo any magic quote effects so mysql_real_escape_string can do the work
      if($magic_quote_acitve){
	$value = stripslashes($value);
      }
    } else {
      //if magic quotes aren't already on then add slashes manually
      if(!$magic_quotes_active){
	$value = addslashes($value);
      }
    }
    return $value;
  }
  
  function update_enroll($a){
    //extract batch, college id, branch id and class id details from enroll_no  
    $batch = intval("" ."20" . $a[0] . $a[1]);
    if((date('m') / 6) > 1){
      $sem = 2 * (date("Y") - $batch) - 1;
    } else {
      $sem = 2 * (date("Y") - $batch);
    }
 
    $colg_code = intval("" . $a[2] . $a[3] . $a[4]);
    
    $branch_code = intval("" . $a[7] . $a[8]); 
    
    add_to_student($a,NULL, NULL, NULL, $colg_code, $branch_code);
    add_to_sem_class($a, $sem, NULL, NULL);
    }
    
    //update student table
    function add_to_student($enroll, $f_name, $m_name, $l_name, 
$colg_id, $branch_id){
   
      $query = "INSERT INTO student ";
      $query .= "VALUES ($enroll, ";
      if($f_name == NULL){
	$query .= "\"\", ";
      } else {
	$query .= $f_name.", ";
      }
	
      if($m_name == NULL){
	$query .= "\"\", ";
      } else {
	$query .= $m_name.", ";
      }
      
      if($l_name == NULL){
	$query .= "\"\", ";
      } else {
	$query .= $l_name.", ";
      }
      
      $query .= "$colg_id, $branch_id );";
      $result = mysql_query($query);
      if(mysql_affected_rows() == 1){
	//echo "looks like updation successful <br />";
      } else {
	echo mysql_error();
      }
    }
    
    function add_to_sem_class($enroll, $sem, $class_id, $roll_no){
      $query = "INSERT INTO sem_class(enrollment_no, sem, class_id, roll_no) "; 
      $query .= "VALUES ( $enroll, $sem, ";
      if($class_id == NULL){
	$query .= "\"A\", ";
      } else {
	$query .= $class_id.", ";
      }
      if($roll_no == NULL){
	$query .= "\"\" ";
      } else {
	$query .= $roll_no." ";
      }
      $query .= ");";
      $result = mysql_query($query);
      if(mysql_affected_rows() == 1){
	//echo "looks like updation successful <br />";
      } else {
	echo mysql_error();
      }
    }
    
    function update_sem_class($a){
      $batch = intval("" ."20" . $a[0] . $a[1]);
      if((date('m') / 6) > 1){
	$sem = 2 * (date("Y") - $batch) - 1;
      } else {
	$sem = 2 * (date("Y") - $batch);
      }
      $query = "UPDATE sem_class ";
      $query .= "SET sem = {$sem} ";
      $query .= "WHERE enrollment_no = {$a}";
      $result = mysql_query($query);
    }
    
    function show_tt($enroll){
      //update info of enrollment no in student database
      $exist_query = "SELECT EXISTS (SELECT * FROM student WHERE 
enroll_no = {$enroll} )";
      $exist_result = mysql_query($exist_query);
      $exist_row = mysql_fetch_array($exist_result);
      if($exist_row[0]==1){
	update_sem_class($enroll);
      } else {
	update_enroll($enroll);
      }
      
      $query = "SELECT * FROM student ";
      $query .= "INNER JOIN college ON student.colg_id = college.gtu_id ";
      $query .= "INNER JOIN branch ON student.branch_id = branch.branch_id ";
      $query .= "INNER JOIN sem_class ON student.enroll_no = enrollment_no ";
      $query .= "WHERE student.enroll_no ={$enroll} LIMIT 0 , 30;";
      $result_set = mysql_query($query);
      
      confirm_query($result_set);
      $row = mysql_fetch_array($result_set);
      $message = "Welcome ";
      $message .= "to ClassLog";
      if(!empty($row['f_name'])){
	$message .= ", ".$row['f_name'];
      }
      $message .= ".<br />";
      $message .= "College Name : ".$row['name']."<br />";
      $message .= "Branch Name : ".$row['branch_name']."<br />";
      $message .= "Semester No. : ".$row['sem']."<br />";
      $message .= "Class : ".$row['class_id']."<br /><br />";
      
      $today = date('D');
      $query_tt = "SELECT start_time, end_time, subject,place ";
      $query_tt .= "FROM schedule ";
      $query_tt .= "WHERE colg_id = {$row['colg_id']} ";
      $query_tt .= "AND branch_id = {$row['branch_id']} ";
      $query_tt .= "AND sem_no={$row['sem']} ";
      $query_tt .= "AND class_id=\"{$row['class_id']}\" ";
      $query_tt .= "AND day=\"$today\"";
      
      $result_tt = mysql_query($query_tt);
      confirm_query($result_tt);
      if(mysql_num_rows($result_tt) >= 1 ){
	$message .= "<table border=1><tr> <th>Time</th> <th>Subject</th> 
<th>Location</th>";
	while($row_tt = mysql_fetch_array($result_tt,MYSQL_ASSOC)){
	  $message .= "<tr>";
	  foreach($row_tt as $key=>$value){
	    if($key=="start_time"){
	      $message .= "<td> $value to ";
	    } else if($key == "end_time"){
	      $message .= "$value </td>";
	    } else {
	      $message .= "<td> $value </td>";
	    }
	  }
	  $message .= "</tr>";
	}
	$message .= "</table>";
      }
      
      return $message;
    }
  
?>

