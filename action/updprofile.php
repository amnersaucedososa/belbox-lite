<?php
	session_start();

	if (!isset($_SESSION['user_id']) && $_SESSION['user_id']==null) {
		header("location: ../");
	}

	include "../config/config.php";

	$id = $_SESSION['user_id'];
	$fullname = $_POST['fullname'];
	$email = $_POST['email'];



		$update=mysqli_query($con,"UPDATE user set name=\"$fullname\", email=\"$email\" where id=$id");
		if ($update) {

            print "Datos Actualizados.";
            print "<script>window.location='../home.php';</script>";
	   	}

	   	// CHANGE PASSWORD
		if($_POST['password']!=""){

			$password = sha1(md5($_POST['password']));
			$new_password = sha1(md5($_POST['new_password']));
			$confirm_new_password = sha1(md5($_POST['confirm_new_password']));	

			if($_POST['new_password']==$_POST['confirm_new_password']){

				$sql = mysqli_query($con,"SELECT * from user where id=$id");
				while ($row = mysqli_fetch_array($sql)) {
			    	$p = $row['password'];
				}

				if ($p==$password){ //comprobamos que la contraseña sea igual ala anterior

					$update_passwd=mysqli_query($con,"UPDATE user set password=\"$password\" where id=$id");
					if ($update_passwd) {
						$success_pass=sha1(md5("contrasena actualizada"));
            			header("location: ../home.php?success_pass=$success_pass");
					}
				}else{
					$invalid=sha1(md5("la contrasena no coincide la contraseña con la anterior"));
            		header("location: ../home.php?invalid=$invalid");
				}
			}else{
				$error=sha1(md5("las nuevas  contraseñas no coinciden"));
            	header("location: ../home.php?error=$error");
			}
		}


?>