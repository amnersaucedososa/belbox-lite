<?php
	session_start();
	include "../config/config.php";

if(!empty($_POST)){
	$id=$_POST["id"];
	$file=mysqli_query($con, "select * from file where id=$id");
	while ($rowc=mysqli_fetch_array($file)) {
		$code=$rowc['code'];
	}

	$user_id= $_SESSION["user_id"];
	$file_id = $_POST["id"];
	$comment= $_POST["comment"];
	$created_at = "NOW()";

	$sql = "insert into comment (comment,file_id,user_id,created_at) ";
	$sql .= "value (\"$comment\",\"$file_id\",$user_id,$created_at)";

	$query=mysqli_query($con, $sql);
	if ($query) {
		header("location: ../file.php?code=$code");
	} else {
		print "<script>alert(\"Hubo un error al agregar tu comentario\")</script>";
		print "<script>window.location=\"myfiles.php\"</script>";
	}
	
}

?>