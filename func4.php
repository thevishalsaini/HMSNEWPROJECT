<?php
// session_start();
$con=mysqli_connect("localhost","root","","myhmsdb1");
if(isset($_POST['recsub1'])){
	$username=$_POST['username2'];
	$password=$_POST['password1'];
	$query="select * from receptiontb where username='$username' and password='$password';";
	$result=mysqli_query($con,$query);
	if(mysqli_num_rows($result)==1)
	{
		header("Location:admin-panel.php");
	}
	else
		// header("Location:error2.php");
		echo("<script>alert('Invalid Username or Password. Try Again!');
          window.location.href = 'index.php';</script>");
}
