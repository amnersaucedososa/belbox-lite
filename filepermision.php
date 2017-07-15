<?php 
    $active2="active"; 
    include "head.php"; 
    include "header.php"; 
    include "aside.php"; 
?>
<?php 

$id_code=$_GET["id"];
$file = mysqli_query($con,"select * from file where code=\"$id_code\"");
    while ($rows=mysqli_fetch_array($file)) {
        $file_id=$rows['id'];
        $file_filename=$rows['filename'];
        $file_code=$rows['code'];
    }
?>
    <div class="content-wrapper"><!-- Content Wrapper. Contains page content -->
        <section class="content-header"><!-- Content Header (Page header) -->
            <h1>Permisos <small><?php echo $file_filename;?> </small> </h1>
            <ol class="breadcrumb">
                <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="myfiles.php"><i class="fa fa-archive"></i> Mis Archivos</a></li>
                <li class="active"><i class="fa fa-lock"></i> Permisos</li>
            </ol>
        </section>
        <section class="content"><!-- Main content -->
            <div class="row"><!-- Small boxes (Stat box) -->
                <div class="col-md-6 col-md-offset-3">
                    <div class="box box-primary"><!-- general form elements -->
                        <div class="box-header with-border">
                          <h3 class="box-title"><i class="fa fa-unlock-alt"> Permisos: </i><a href="file.php?code=<?php echo $file_code; ?>"> <?php echo $file_filename; ?></a></h3>
                        </div><!-- /.box-header -->
                        <form method="post" action="action/addfilepermision.php" role="form"><!-- form start -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Permisos:</label>
                                    <select name="p_id" class="form-control select2" style="width: 100%;">
                                      <option value="1">Ver, Dercargar y Comentar</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name_folder">Correo Electrónico</label>
                                    <input type="email" name="email" required class="form-control" id="name_folder" placeholder="Correo del amigo, familiar, compañero...">
                                    <p class="text-muted text-right">Escribe un email para otorgarle permisos</p>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="file_id" value="<?php echo $file_id;?>">
                                <button type="submit" class="btn btn-warning">Agregar</button>
                            </div>
                        </form>
                    </div><!-- /.box -->
                </div>
            </div><!-- /.row -->
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                <div class="box">
                        <div class="box-header">

                        </div><!-- /.box-header -->
                        <?php 
                        $permisions = mysqli_query($con,"select * from permision where file_id=$file_id");
                        $count=mysqli_num_rows($permisions);
                        ?>
                        <div class="box-body table-responsive no-padding">
                        <?php if($count>0):?>
                            <table class="table table-hover">
                                <tbody><tr>
                                    <th>Nombre</th>
                                    <th>Permisos</th>
                                    <th>Fecha</th>
                                    <th></th>
                                </tr>
                                <?php foreach($permisions as $p):?>

                                <?php 
                                    
                                    $permission_id=$p['user_id'];

                                        $permi=mysqli_query($con,"select * from permision where user_id=$permission_id");
                                        while ($usi=mysqli_fetch_array($permi)) {
                                            $userd=$usi['user_id'];

                                        }
                                        $userss=mysqli_query($con,"select * from user where id=$userd");
                                            while($com2=mysqli_fetch_array($userss)){
                                                $fullname=$com2['fullname'];
                                            }
                                   

                                ?> 
                                <tr>
                                    <td><?php echo $fullname;?></td>
                                    <td><?php if($p['p_id']==1){ echo "Ver, Descargar y Comentar"; }?></td>
                                    <td><span class="label label-info"><?php echo $p['created_at'];?></span></td>
                                    <td style="width:30px;">
                                      <a href="action/delfilepermision.php?id=<?php echo $p['id']; ?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                                <?php else:?>
                                  <div class="col-md-6 col-md-offset-3">
                                <p class="alert alert-warning"> <i class="
                                fa fa-exclamation-triangle"></i> No se encontraron permisos para este archivo</p>
                               </div>
                                <?php endif;?>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>
        </section>
    </div><!-- /.content -->


<?php include "footer.php"; ?>