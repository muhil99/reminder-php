
<?php
$db=mysqli_connect('database-1.cfn2upb3ncuy.ap-south-1.rds.amazonaws.com','admin',"muhilanr","student_details");
if(isset($_POST['reg_user']))
{
	
	$full_name = mysqli_real_escape_string($db, $_POST['full_name']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$mobileno = mysqli_real_escape_string($db, $_POST['mobileno']);
	$list = mysqli_real_escape_string($db, $_POST['list']);
	$message = mysqli_real_escape_string($db, $_POST['message']);
	
			$query = "INSERT INTO student_details(full_name,email,mobileno,list,message) 
					  VALUES('$full_name','$email','$mobileno','$list','$message')";
			$row=mysqli_query($db,$query);
			if ($row) {
				header('location: thanks.html');
				# code...
			}
			else
			{
				echo "Invalid Details";
			}
		

}

?>






