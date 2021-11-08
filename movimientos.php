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
    $active_movimientos="active";
    $active_cpc="";
	$title="Movimientos | Copacabana Textil, S.A. De C.V.";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include("head.php");?>
    
    <style>
    
        td,th{
            text-align:center;
        }
        
        th{
            font-size: 18px;
        }
        
        td{
            font-size: 16px;
        }
        
        .izq{
            text-align:left;
        }
    
    </style>
    
</head>

<body>
    <?php
	include("navbar.php");
	?>

    <!--   <div class="text-center">
        <br>
        <a href="#Modal1" class="btn btn-primary" data-toggle="modal">Click para desplegar opciones</a>
    </div>

    
    <div id="Modal1" class="modal fade">
        <div class="modal-dialog modal-login">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Movimientos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="redirec.php" target="_blank" method="post">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon glyphicon glyphicon-log-in"><i class="fa fa-user"></i></span>
                                <select class="form-control" name="operacion" id="operacion" required>
                                    
                                    <option value="">-- Seleccione una operación --</option>
                                    <option value="1">Saldos</option>
                                    <option value="2">Antiguedad de Saldos</option>
                                    <option value="3">Resumen de movimientos</option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon glyphicon glyphicon-user"><i class="fa fa-lock"></i></span>
                                <?php 
                                $query = "SELECT id_cliente as ID, nombre_cliente as nombre FROM clientes ORDER BY id_cliente ASC;";
                                $result = mysqli_query($con, $query);
                                ?>

	                            <select class="form-control" name="cliente_m" id="cliente_m" required>
	                                <option value="">-- Seleccione un cliente disponible --</option>
	                                <?php  foreach ($result as $r): ?>

	                                <option value="<?php echo $r['ID']; ?>"> <?php echo $r['ID'] ," ", $r['nombre']; ?> </option>
	                                <?php endforeach; ?>

	                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Aceptar</button>
                        </div>
                       
                    </form>
                </div>
                <div class="modal-footer"><small>*Favor de llenar los campos</small></div>
            </div>
        </div>
    </div> -->
    
                <?php
                    include("modal/saldos.php");
                    include("modal/movimientos.php");
                    include("modal/saldo.php");
			    ?>

    <div class="container">
      <div class="panel panel-info">
       <div class="panel-heading">
                
                <h4><i class='glyphicon glyphicon-menu-hamburger'></i> Men&uacute; principal</h4>
            </div>
          
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="">Opci&oacute;n</th>
                    <th>Acci&oacute;n</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="izq"> <span class="glyphicon glyphicon-list"></span> Movimientos</td>
                    <td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#movimientos"><span class="glyphicon glyphicon-file"></span> Generar Reporte</button></td>
                    

                </tr>
                
                <tr>
                    <td class="izq"><span class="glyphicon glyphicon-calendar"></span> Antig&uuml;edad de saldos</td>
                    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#saldos"><span class="glyphicon glyphicon-file"></span> Generar Reporte</button></td>
                    
                </tr>
                
                <tr>
                    <td class="izq"><span class="glyphicon glyphicon-usd"></span> Saldos</td>
                    <td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#saldo"><span class="glyphicon glyphicon-file"></span> Generar Reporte</button></td>
                </tr>

            </tbody>
        </table>
        
        </div>  
        
    </div>

    <?php
	include("footer.php");
	?>
</body>

</html>
