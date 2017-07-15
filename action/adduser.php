<?php

// registro de usuario
// @author Abisoft

	include "../config/config.php";

	if(!empty($_POST)){

		$fullname=$_POST["fullname"];
		$password=sha1(md5($_POST["password"]));
		$email=$_POST["email"];
		$created_at = "NOW()";
		$is_admin=0;
		$default_profile="default.png";

		$sql = "insert into user (fullname,email,is_admin,password,image,created_at) ";
		$sql .= "value (\"$fullname\",\"$email\",\"$is_admin\",\"$password\",\"$default_profile\",$created_at)";

		$query=mysqli_query($con,$sql);
		if ($query) {
			//echo "usario registrado ";
			print "<script>alert(\"Usuario registrado\")</script>";
			print "<script>window.location=\"../index.php\"</script>";

		}else{
			print "<script>alert(\"Hubo un error\")</script>";
			print "<script>window.location=\"../register.php\"</script>";
		}

	}

?>