<?php

	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }
	
	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$active_facturas="";
	$active_productos="";
	$active_clientes="";
	$active_usuarios="";
    $active_cpc="active"; 
    $active_movimientos="";

	$title="Cuentas por Cobrar | Copacabana Textil, S.A. De C.V.";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("head.php");?>
</head>

<body>
    <?php
	include("navbar.php");
	?>

    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="btn-group pull-right">
                    <button type='button' class="btn btn-info" data-toggle="modal" data-target="#nuevoCPC"><span class="glyphicon glyphicon-plus"></span> Nueva Cuenta por Cobrar</button>


                </div>
                <button style="font-size:20px;" type="button" class="btn btn-success pull-left"> Total <span style="font-size:20px;">

                        <?php 
                                    $sql = "select sum(monto) as deuda from cuentas_pc where estatus = 'Pendiente' or estatus = 'Expirado'";                                                                           
                                    $res2 = mysqli_query($con, $sql);
                                    if($row = mysqli_fetch_array($res2)) {
                                        $cantidad= $row[0];
                                    }     
                                    
                                    echo "$".$cantidad; 
                                ?>

                    </span></button>
                    &nbsp; <br> <br>
            </div>
            <div class="panel-body">

                <?php
				    include("modal/registro_cpc.php");
				    include("modal/editar_cpc.php");
                    include("modal/documentar.php");
                    include("modal/saldos.php");
                    include("modal/facturas.php");
			    ?>

                <form class="form-horizontal" role="form" id="datos_cotizacion">

                    <div class="form-group row">
                        <label for="q" class="col-md-2 control-label">Escriba el ID del cliente o elija una opci&oacute;n:</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="q" placeholder="ID del clente" onkeyup='loads(1);'>
                                                           
                            <?php 
                                $query = "SELECT id_cliente as ID, nombre_cliente as nombre FROM clientes ORDER BY id_cliente ASC";
                                $result = mysqli_query($con, $query);
                            ?>
                                
	                            <select class="form-control" id="qq" name="qq" required onchange='load(1);'>
                                    <option value="">-- Seleccione un cliente --</option>
	                                <?php  foreach ($result as $r): ?>

	                                <option value="<?php echo $r['ID']; ?>"> <?php echo $r['ID'] ." ". $r['nombre']; ?> </option>
	                                <?php endforeach; ?>

	                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-default" onclick='load(1);'>
                                <span class="glyphicon glyphicon-search"></span> Buscar</button>

                            <span id="loader"></span>                                        

                        </div>
                        <br><br><br>
                        <div class="col-gd-4 pull-right">
                            
                            <button type='button' class="btn btn-warning" data-toggle="modal" data-target="#documentar"><span class="glyphicon glyphicon-file"></span>Documentar</button> &nbsp;

                            <a href="reporte_cpc.php?id=1" target="_blank"><button type="button" class="btn btn-success pull-right"> Reporte General</button></a>

                        </div>

                    </div>
                </form>

                <div id="resultados"></div><!-- Carga los datos ajax -->
                <div class='outer_div'></div><!-- Carga los datos ajax -->

            </div>
        </div>

    </div>
    <hr>
    <?php
	include("footer.php");
	?>
    <script type="text/javascript" src="js/cpc.js"></script>
</body>

</html>
