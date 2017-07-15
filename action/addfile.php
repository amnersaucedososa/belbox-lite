<?php
	session_start();

	include "../config/config.php";
	include "class.upload.php";

if(!empty($_POST) && isset($_SESSION["user_id"])){

	$alphabeth ="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYZ1234567890_-";
	$code = "";
	for($i=0;$i<12;$i++){
	    $code .= $alphabeth[rand(0,strlen($alphabeth)-1)];
	}

	$code= $code;
	$is_public = isset($_POST["is_public"])?1:0;
	$folder_id = $_POST["folder_id"]!="" ? $_POST["folder_id"]:"NULL";
	$folder_id;

	$user_id=$_SESSION["user_id"];
	$description = $_POST["description"];
	$created_at = "NOW()";


	$handle = new Upload($_FILES['filename']);
	if ($handle->uploaded) {
		$url="../storage/data/".$_SESSION["user_id"];
		$handle->Process($url);
		if($handle->processed){
	    $filename = $handle->file_dst_name;
		$sql = "INSERT INTO file (code, filename, description, is_public, user_id, is_folder, folder_id, created_at) VALUES (\"$code\",\"$filename\",\"$description\", $is_public, $user_id, 0, $folder_id, NOW());";

		$query=mysqli_query($con, $sql);
		if ($query) {
			//echo "archivo agregado con exito";
			print "<script>alert(\"archivo agregado con exito\")</script>";
			print "<script>window.location=\"../myfiles.php\"</script>";
		}else{
			//echo "no se pudo, subir hubo un error".mysqli_error($con)."<br>.".mysqli_errno($con);
			print "<script>alert(\"no se pudo, subir hubo un error\")</script>";
			print "<script>window.location=\"../myfiles.php\"</script>";
		}
		}else{
			//echo "el archivo no se subio por peso maximo";
			print "<script>alert(\"el archivo no se subio por peso maximo\")</script>";
			print "<script>window.location=\"../newfile.php\"</script>";
		}

	}else{
			//echo "el archivo no se subio por peso maximoxdxdxd";
			print "<script>alert(\"el archivo no se subio por peso maximo\")</script>";
			print "<script>window.location=\"../newfile.php\"</script>";
		}

}


?>