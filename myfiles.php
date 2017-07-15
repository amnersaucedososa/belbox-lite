<?php 
    $active2="active"; 
    include "head.php"; 
    include "header.php"; 
    include "aside.php"; 
?>
<?php 
    $alphabeth ="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYZ1234567890_-";
    $token = "";
    for($i=0;$i<6;$i++){
        $token .= $alphabeth[rand(0,strlen($alphabeth)-1)];
    }
    $_SESSION["tkn"]=$token;
    $folder=null;
    if(isset($_GET["folder"]) && $_GET["folder"]!=""){
        
        $id_folder=$_GET["folder"];
        $folder = mysqli_query($con,"select * from file where code=\"$id_folder\"");

        while ($row=mysqli_fetch_array($folder)) {
            $file_id_folder=$row['id']; 
            $file_folder_id=$row['folder_id']; 
            $file_folder_filename=$row['filename'];
            $file_folder_code=$row['code'];
        }
    }

?>


    <div class="content-wrapper"><!-- Content Wrapper. Contains page content -->
        <section class="content-header"><!-- Content Header (Page header) -->
            <h1>Mis Archivos</h1>
            <ol class="breadcrumb">
                <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="myfiles.php"><i class="fa fa-archive"></i> Mis Archivos</a></li>
                <?php
                    if($folder!=null){
                        echo '<li class="active"><a href="myfiles.php?folder='.@$file_folder_code.'"><i class="fa fa-folder-open"></i> '.@$file_folder_filename.'</a></li>';
                    }
                ?>
            </ol>
        </section>

        <section class="content"><!-- Main content -->
            <div class="row"><!-- Small boxes (Stat box) -->
                <div class="col-xs-12">

                <?php
                    $files = null;
                    if($folder==null){
                        if(isset($_GET["q"]) && $_GET["q"]!=""){
                            $q=$_GET["q"];
                            $files = mysqli_query($con,"select * from file where user_id=$my_user_id and folder_id is NULL and (filename like '%$q%' or description like '%$q%') order by is_folder desc, filename asc");

                           
                    }else{
                        $files = mysqli_query($con,"select * from file where user_id=$my_user_id and folder_id is NULL order by is_folder desc, filename asc");
                    }


                    }else{
                        // search
                        if(isset($_GET["q"]) && $_GET["q"]!=""){
                            $q=$_GET["q"];
                            $files=mysqli_query($con,"select * from files where folder_id=$file_id_folder and  (filename like '%$q%' or description like '%$q%') order by created_at desc");
                        }else{
                            // folder/folder/file.php
                            @$files=mysqli_query($con,"select * from file where folder_id=$file_id_folder order by created_at desc");

                        }
                    }
                ?>


               <?php if(isset($_GET["folder"]) && $_GET["folder"]!="" && $folder==null):?>
                    <p class="alert alert-danger">Estas intentando acceder a una carpeta que no existe!</p>
                <?php endif; ?>

                <?php if(@mysqli_num_rows($files)>0):?>

                    <?php if(isset($_GET["q"]) && $_GET["q"]!=""):?>
                        <p class="alert alert-info">Resultado de la busqueda: <?php echo $_GET["q"];?></p>
                    <?php endif; ?>

                    <div class="box">
                        <div class="box-header">
                            <?php if($folder==null):?>
                            <h3>Mis Archivos</h3>
                            <?php else:?>
                            <h3><?php echo $file_folder_filename;?></h3>
                            <?php endif;?>
                            <div class="box-tools">
                            <form role="search" method="get">
                            <input type="hidden" name="view" value="home">
                                <?php if(isset($_GET["folder"]) && $_GET["folder"]!=""):?>
                                <input type="hidden" name="folder" value="<?php echo $_GET["folder"];?>">
                                <?php endif; ?>
                                <div class="input-group input-group-sm" style="width: 150px;">
                                  <input type="text" name="q" class="form-control pull-right" placeholder="Buscar ...">
                                  <div class="input-group-btn">
                                    <button type="submit" id="search-btn" class="btn btn-default"><i class="fa fa-search"></i></button>
                                  </div>
                                </div>
                               </form> 
                            </div>
                        </div><!-- /.box-header -->

                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th>Archivo</th>
                                        <th>Descripción</th>
                                        <th>Tamaño</th>
                                        <th>Fecha</th>
                                        <th></th>
                                    </tr>
                                    <?php foreach($files as $file):?>
                                    <tr>
                                        <td>
                                            <?php if($file['is_folder']):?>
                                                <a href="myfiles.php?folder=<?php echo $file['code'];?>">
                                                    <i class="fa fa-folder"></i>
                                            <?php else:?>       
                                                <a href="file.php?code=<?php echo $file['code'];?>">
                                                <i class="fa fa-file"></i>
                                            <?php endif; ?>
                                            <?php echo $file['filename']; ?></a>
                                        </td>
                                        <td><?php echo $file['description']; ?></td>
                                        <td>
                                            <?php 
                                                $url = "storage/data/".$file['user_id']."/".$file['filename'];
                                                if(file_exists($url)){
                                                    $fsize = filesize($url);
                                                    if($file['filename']!=""){
                                                        if(!$file['is_folder']){
                                                            if($fsize>1000*1000*1000){
                                                                echo ($fsize/1000*1000*1000)."Gb";
                                                            }
                                                            else if($fsize>1000*1000){
                                                                echo ($fsize/1000*1000)."Mb";
                                                            }
                                                            else if($fsize>1000){
                                                                echo ($fsize/1000)."Kb";
                                                            }
                                                            else if($fsize>0){
                                                                echo $fsize."B";
                                                            }
                                                        }
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $file['created_at']; ?></td>
                                        <td style="width:223px;">
                                            <a href="filepermision.php?id=<?php echo $file['code']; ?>" class="btn btn-xs btn-default"><i class="fa fa-lock"></i> Permisos</a>
                                            <a href="editfile.php?id=<?php echo $file['code'];?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Editar</a>
                                            <a href="action/delfile.php?id=<?php echo $file['code']; ?>&tkn=<?php echo $_SESSION["tkn"]?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body --><br>
                    </div><!-- /.box -->
                            <?php else:?>
                               <div class="col-md-6 col-md-offset-3">
                                <p class="alert alert-warning"> <i class="
                                fa fa-exclamation-triangle"></i> No se encontraron archivos en la carpeta actual</p>
                               </div>
                            <?php endif;?>
                </div>
            </div><!-- /.row -->
        </section>
    </div><!-- /.content -->

<?php include "footer.php"; ?>