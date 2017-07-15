<?php 
    $active2="active";
    include "head.php"; 
    include "header.php"; 
    include "aside.php"; 
?>
<?php 
$file = null;
if(isset($_GET["code"]) && $_GET["code"]!=""){

    $id=$_GET["code"];
    $file=mysqli_query($con, "select * from file where code=\"$id\"");

    while ($row=mysqli_fetch_array($file)) {
        $file_id=$row['id'];
        $is_public=$row['is_public'];
        $user_id=$row['user_id'];
        $code=$row['code'];
        $filename=$row['filename'];
        $description=$row['description'];
        $created_at=$row['created_at'];
        $folder_id=$row['folder_id'];


    }
}

$is_public = false;
$is_logged = false;
$is_owner = false;
$go = false;


if($is_public){ $is_public=true; }
if(isset($_SESSION["user_id"])){ $is_logged= true;}

if($is_logged && $_SESSION["user_id"]==$user_id){ $is_owner = true; }

if($is_public){
    $go=true;
}
if(!$is_logged){
    print "<script>alert(\"Acceso Denegado uno!\")</script>";
    print "<script>window.location='./';</script>";
}
else if ($go==false && !$is_owner){
    

    $ps=mysqli_query($con, "select * from permision where file_id=".$file_id);
    $found=false;
    foreach ($ps as $p) {

        if($p['user_id']==$_SESSION["user_id"]){
            $found = true;
        }
    }

    if($found==true){
        $go=true;
    }else{
        print "<script>alert(\"Acceso Denegado!\")</script>";
        print "<script>window.location='shared.php';</script>";
    }


}

?>

<?php if($go||$is_owner):?>
    <div class="content-wrapper"><!-- Content Wrapper. Contains page content -->
        <section class="content-header"><!-- Content Header (Page header) -->
            <h1>Mis Archivos <small><?php echo $filename;?></small> </h1>
            <?php if(isset($_SESSION["user_id"])):?>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="myfiles.php"><i class="fa fa-archive"></i> Mis Archivos</a></li>

                <?php
                    if($folder_id!=null){
                        $f = mysqli_query($con,"select * from file where id=$folder_id");

                            while ($g=mysqli_fetch_array($f)) {
                                $f_code=$g['code'];
                                $f_filename=$g['filename'];
                            }

                            echo '<li class="active"><a href="myfiles.php?folder='.$f_code.'"><i class="fa fa-folder-open"></i> '.$f_filename.'</a></li>';
                    }
                ?>
            </ol>
            <?php endif; ?>
        </section>
        <section class="content"><!-- Main content -->
            <div class="row"><!-- Small boxes (Stat box) -->
                <div class="col-lg-6 col-xs-6 col-md-offset-3">
                    <div class="btn-group  pull-right"> 
                        <a href="action/dwnfl.php?code=<?php echo $code;?>" class="btn btn-default"><i class="fa fa-download"></i> Descargar</a>
                    </div>

                    <script>
                        function copiarAlPortapapeles(id_elemento) {
                        var aux = document.createElement("input");
                        aux.setAttribute("value", document.getElementById(id_elemento).innerHTML);
                        document.body.appendChild(aux);
                        aux.select();
                        document.execCommand("copy");
                        document.body.removeChild(aux);
                        }

                    </script>
                    <?php
                       $url=$_SERVER["HTTP_HOST"]."/belbox/file.php?code=".$_GET['code'];

                    ?>
                    <div style="padding-right:6px;" class="btn-group  pull-right">
                        <!-- <input type="hidden" value="<?php echo $url?>" id="copy"> -->
                        <p style="display: none;" id="copy"><?php echo $url?></p>
                        <a onclick="copiarAlPortapapeles('copy')" class="btn btn-default"><i class="fa fa-link"></i> Copiar enlace</a>
                    </div>
                        <br>
                    <?php if($file==null):?>
                    <h1>404</h1>
                    <?php else:?>
                        <br>
                    <h3><?php echo $filename;?></h3>
                    <?php endif;?>
                   <br><br><p><?php echo $description; ?></p><br>
                   <p class="text-muted text-right"><i class="fa fa-clock-o"></i> <?php echo $created_at;?></p>
                    <br><br>
                    <?php 

                        $comments = mysqli_query($con, "select * from comment where file_id=".$file_id);
                        $count=mysqli_num_rows($comments);
                        ?>
                        
                    <div class="box box-success"><!-- small box -->
                        <div class="box-header">
                            <i class="fa fa-comments-o"></i>
                            <h3 class="box-title">Comentarios (<?php echo $count?>)</h3>
                        </div>
                        <?php if($count>0):?>
                        <div class="box-body chat" id="chat-box">
                            <div class="item"><!-- chat item -->
                            <?php foreach($comments as $com): ?>
                                <?php

                                $com_user_id=$com['user_id'];
                                $commm=mysqli_query($con,"select * from comment where user_id=$com_user_id");
                                while ($usi=mysqli_fetch_array($commm)) {
                                    $userd=$usi['user_id'];

                                }
                                $userss=mysqli_query($con,"select * from user where id=$userd");
                                    while($com2=mysqli_fetch_array($userss)){
                                        $profile_pic=$com2['image'];
                                        $fullname=$com2['fullname'];
                                    }
                                ?>
                                <img src="images/profiles/<?php echo $profile_pic; ?>" alt="user image" class="offline">
                                <p class="message">
                                        <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <!-- 5:15 --><?php echo $com['created_at'];?></small>
                                    <a href="#" class="name">
                                        <?php echo $fullname;  ?>
                                    </a>
                                    <?php echo $com['comment'];?>
                                </p>
                               <?php endforeach; ?> 
                            </div><!-- /.item -->
                        </div><!-- /.chat -->
                       <?php endif;?> 
                        <div class="box-footer">
                        <form method="post" action="action/addfilecomment.php">
                            <div class="input-group">
                                <input type="hidden" value="<?php echo $file_id?>" name="id">
                                <input name="comment" required class="form-control" placeholder="Escribe un comentario...">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-comments"></i></button>
                                </div>
                            </div>
                           </form> 
                        </div>
                    </div>

                <?php if($file!=null):?>

                <?php else:?>
                    <div class="jumbotron">
                    <h2>No hay archivos</h2>
                    <p>No se encontraron archivos en la carpeta actual.</p>
                    </div>
                <?php endif;?>


                    
                </div><!-- ./col -->
            </div><!-- /.row -->
        </section>
    </div><!-- /.content -->
<?php endif;?>

<?php include "footer.php"; ?>