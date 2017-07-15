<?php
	session_start();

	include "../config/config.php";


if(!empty($_POST) && isset($_SESSION["user_id"])){

	$alphabeth ="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYZ1234567890_-";
	$code = "";
	for($i=0;$i<12;$i++){
	    $code .= $alphabeth[rand(0,strlen($alphabeth)-1)];
	}


	$code= $code;
	$is_public = isset($_POST["is_public"])?1:0;
	$user_id=$_SESSION["user_id"];
	$description = $_POST["description"];
    $filename = $_POST["filename"];

    $sql = "insert into file (code,filename,description,is_folder,is_public,user_id,created_at) ";
	$sql .= "value (\"$code\",\"$filename\",\"$description\",1,$is_public,$user_id,NOW())";

	$query = mysqli_query($con, $sql);
	if ($query) {
		print "<script>alert(\"Carpeta creada con exito\")</script>";
			print "<script>window.location=\"../newfolder.php\"</script>";
	} else {
		print "<script>alert(\"Hubo un error al crear la carpeta\")</script>";
			print "<script>window.location=\"../newfolder.php\"</script>";
	}
	

}

?>