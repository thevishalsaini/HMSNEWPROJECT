<?php
// session_start();
$con=mysqli_connect("localhost","root","","myhmsdb1");

if(isset($_POST['update_data']))
{
 $contact=$_POST['contact'];
 $status=$_POST['status'];
 $query="update appointmenttb set payment='$status' where contact='$contact';";
 $result=mysqli_query($con,$query);
 if($result)
  header("Location:updated.php");
}


function display_depts() {
  global $con;
  $query="select distinct(dept) from doctb";
  $result=mysqli_query($con,$query);
  while($row=mysqli_fetch_array($result))
  {
    $dept=$row['dept'];
    echo '<option data-value="'.$dept.'">'.$dept.'</option>';
  }
}

function display_docs()
{
 global $con;
 $query = "select * from doctb";
 $result = mysqli_query($con,$query);
 while( $row = mysqli_fetch_array($result) )
 {
  $username = $row['username'];
  $price = $row['docFees'];
  $dept = $row['dept'];
  echo '<option value="' .$username. '" data-value="'.$price.'" data-dept="'.$dept.'">'.$username.'</option>';
 }
}

if(isset($_POST['doc_sub']))
{
 $username=$_POST['username'];
 $query="insert into doctb(username)values('$username')";
 $result=mysqli_query($con,$query);
 if($result)
  header("Location:adddoc.php");
}

?>