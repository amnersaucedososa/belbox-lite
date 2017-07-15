<?php
	session_start();

	include "../config/config.php";

	if(!empty($_POST)){
		$nick=$_POST["email"];
		$user = mysqli_query($con, "select * from user where email=\"$nick\"");

		while ($rowu=mysqli_fetch_array($user)) {
				$user_id=$rowu['id'];
		}

		$id=$_POST["file_id"];
		$file = mysqli_query($con, "select * from file where id=$id");

		while ($rowf=mysqli_fetch_array($file)) {
				$file_id=$rowf['id'];
				$file_code=$rowf['code'];
		}


		if($user_id!=null){	
			if($user_id!=$_SESSION["user_id"]){

				$user_id= $user_id;
				$file_id = $file_id;
				$p_id= $_POST["p_id"];
				$created_at = "NOW()";

				$sql = "insert into permision (p_id,file_id,user_id,created_at)";
				$sql .= "value ($p_id,\"$file_id\",$user_id,$created_at)";

				$query=mysqli_query($con, $sql);
				if ($query) {
					//echo "Agregado exitosamente!";
					print "<script>alert(\"Agregado exitosamente!\")</script>";
					print "<script>window.location=\"../myfiles.php\"</script>";
				} else {
					//echo "Hubo un error al dar los permisos!";
					print "<script>alert(\"Hubo un error al dar los permisos!\")</script>";
					print "<script>window.location=\"../myfiles.php\"</script>";
				}
				

			}else{
				//echo "No puedes agregarte ati mismo!";
				print "<script>alert(\"No puedes agregarte ati mismo!\")</script>";
				print "<script>window.location=\"../myfiles.php\"</script>";
			}

		}else{
			//echo "El usuario no existe!";
			print "<script>alert(\"El usuario no existe!\")</script>";
			print "<script>window.location=\"../myfiles.php\"</script>";
		}

	}

?>