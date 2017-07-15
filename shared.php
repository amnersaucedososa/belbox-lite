<?php 
    $active3="active";
    include "head.php"; 
    include "header.php"; 
    include "aside.php"; 
?>
    
    <div class="content-wrapper"><!-- Content Wrapper. Contains page content -->
        <section class="content-header"><!-- Content Header (Page header) -->
            <h1><i class="fa fa-globe"></i> Archivos Compartidos</h1>
            <ol class="breadcrumb">
                <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Compartidos conmigo</li>
            </ol>
        </section>
        <section class="content"><!-- Main content -->
            <div class="row"><!-- Small boxes (Stat box) -->
                <div class="col-xs-12">
                    <?php
                        $user=$_SESSION["user_id"];
                        $files = mysqli_query($con,"select * from permision where user_id=".$user);
                        $count = mysqli_num_rows($files);
                        while ($r=mysqli_fetch_array($files)) {
                            $id=$r['id'];
                        }
                    ?>
                    <div class="box">
                        <div class="box-header">
                        </div> <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                        <?php if($count>0):?>
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th>Archivo</th>
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                    </tr>
                                    <?php foreach($files as $fx):
                                        $fx = mysqli_query($con,"select * from permision where id=$id");
                                        while ($rows=mysqli_fetch_array($fx)) {
                                            $file_id=$rows['file_id'];
                                        }

                                        $file=mysqli_query($con,"select * from file where id=$file_id");
                                        while ($row=mysqli_fetch_array($file)) {
                                            $file_is_folder=$row['is_folder'];
                                            $file_filename=$row['filename'];
                                            $file_code=$row['code'];
                                            $file_description=$row['description'];
                                            $file_created_at=$row['created_at'];
                                       }
                                       // echo var_dump($file);
                                    ?>
                                    <tr>
                                        <td>    
                                            <?php if($file_is_folder):?>
                                            <a href="myfiles.php?folder=<?php echo $file_code;?>">
                                                <i class="fa fa-folder"></i>
                                            <?php else:?>
                                            <a href="file.php?code=<?php echo $file_code;?>">
                                                <i class="fa fa-file"></i>
                                            <?php endif; ?>
                                            <?php echo $file_filename; ?></a>
                                        </td>
                                        <td><?php echo $file_description; ?></td>
                                        <td><?php echo $file_created_at; ?></td>
                                    </tr>
                                    <?php  
                                     endforeach; ?>
                                </tbody>
                            </table>
                            <?php else:?>
                                <div class="col-md-6 col-md-offset-3">
                                <p class="alert alert-warning"> <i class="
                                fa fa-exclamation-triangle"></i> No se encontraron archivos en la carpeta actual</p>
                               </div>
                            <?php endif;?>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div><!-- /.row -->
        </section>
    </div><!-- /.content -->


<?php include "footer.php"; ?>