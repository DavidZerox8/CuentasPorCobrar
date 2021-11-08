<?php
    
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

    $d="";
    $b="";
    $tipo = "";
    $monto = "";
    $documento_cpc = "";
    $n_monto = "";
    $monto_o = "";
    $fecha = "";
    $cantidad="";


	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

	if (isset($_GET['id'])){
		$id_cliente=intval($_GET['id']);
        $upd = "select monto, tipo, documento from pagos where id_pago = '$id_cliente'";
        $sql= "select documento, id_cliente from pagos where id_pago = '$id_cliente'"; 
		$query=mysqli_query($con, "select * from pagos where id_pago='".$id_cliente."'");
		$count=mysqli_num_rows($query);
        
                   
        $res= mysqli_query($con, $sql);
                if($row = mysqli_fetch_array($res)) {
                    $d= $row[0];
                    $b= $row[1];
                }
                          
                $updatef = "UPDATE `cuentas_por_cobrar` SET `fecha_pago` = 'Pendiente' WHERE `cuentas_por_cobrar`.`documento` = '$d'";
                $updatecpc = "UPDATE `facturas` SET `estado_factura` = '2' WHERE `facturas`.`numero_factura` = '$d'";
                    
                    
                $insert1 = mysqli_query($con, $updatef);                   
                $insert2 = mysqli_query($con, $updatecpc);        
        
		if ($count==0 or $count==1){
            
            $upd2 = "select Monto, id_cliente, documento from pagos where id_pago = '$id_cliente'";
            $res2 = mysqli_query($con, $upd2);
                if($row = mysqli_fetch_array($res2)) {
                    $cantidad= $row[0];
                    $d = $row['id_cliente'];
                    $fac = $row['documento'];
                   
                }           
            
            $deuda = "select deuda_c from clientes where id_cliente = '$d'";
            $resd = mysqli_query($con, $deuda);
                if($row = mysqli_fetch_array($resd)) {
                    $monto= $row[0];
                   
                }            
            $nueva_deuda = $monto + $cantidad;

            $cambio = "update clientes set deuda_c = '$nueva_deuda' where id_cliente = '$d'";
            $cambio_estatus = "update cuentas_pc set estatus = 'Pendiente' where n_factura = '$fac'";
            $n_d = mysqli_query($con, $cambio);
            $n_u = mysqli_query($con, $cambio_estatus);
            
			if ($delete1=mysqli_query($con,"DELETE FROM pagos WHERE id_pago='".$id_cliente."'")){
			    		    
			?>
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Aviso!</strong> Datos eliminados exitosamente.
</div>
<?php 
   
		}else {
			?>
<div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Error!</strong> Lo siento algo ha salido mal intenta nuevamente.
</div>
<?php
			
		}
			
		} else {
			?>
<div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Error!</strong> <?php echo $count; ?> No se pudo eliminar éste pago. Existen facturas vinculadas a éste producto.
</div>
<?php
		}
		
	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $condicion = "where pagos.id_cliente = clientes.id_cliente AND pagos.p_concepto = conceptos.id_concepto";
         $valor = "";
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $aColumns = array('pagos.id_cliente');//Columnas de busqueda
		 $sTable = "pagos";
		 $sWhere = "";
		if ( $_GET['q'] != "" )
		{
            $condicion ="";
			$sWhere = "WHERE ";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ' ';
            $valor = "AND pagos.p_concepto = conceptos.id_concepto and pagos.id_cliente = clientes.id_cliente order by id_pago";
		}
        
		$sWhere.=" ";
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
        
        
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrowsz FROM $sTable $sWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrowsz'];
        
        
		$total_pages = ceil($numrows/$per_page);
		$reload = './pagos.php';
		//main query to fetch the data
		$sql="SELECT pagos.*, clientes.nombre_cliente as cliente, conceptos.desc_concepto AS concepto, conceptos.tipo_concepto as tipoc FROM  $sTable,  clientes, conceptos $condicion $sWhere $valor";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			
			?>
			
			
			
    <div class="table-responsive">
    <table class="table">
        <tr class="info">
            <th>#</th>
            <th>ID cliente</th>
            <th>Monto</th>
            <th>Tipo</th>
            <th>Concepto</th>
            <th>Factura</th>
            <th>Fecha de pago</th>
            <th>Fecha de registro</th>
            <th class='text-right'>Acciones</th>

        </tr>
        <?php
				while ($row=mysqli_fetch_array($query)){
                    
						$id_cliente=$row['id_pago'];
                        $nombrec = $row['cliente'];
						$nombre_cliente=$row['id_cliente'];
						$telefono_cliente=$row['Monto'];
						$email_cliente=$row['Tipo'];
                        $documento = $row['Documento'];
						$direccion_cliente=$row['Fecha_pago'];
                        $fecha=$row['Fecha_reg'];    												
						$concepto = $row['p_concepto'];
                        $con = $row['concepto'];
                        $tipo = $row['tipoc'];
					?>

        <input type="hidden" value="<?php echo $id_cliente;?>" id="nombre_cliente<?php echo $id_cliente;?>">
        <input type="hidden" value="<?php echo $nombre_cliente;?>" id="telefono_cliente<?php echo $id_cliente;?>">
        <input type="hidden" value="<?php echo $telefono_cliente;?>" id="email_cliente<?php echo $id_cliente;?>">
        <input type="hidden" value="<?php echo $email_cliente;?>" id="direccion_cliente<?php echo $id_cliente;?>">
        <input type="hidden" value="<?php echo $direccion_cliente;?>" id="status_cliente<?php echo $id_cliente;?>">
        <input type="hidden" value="<?php echo $fecha?>" id="fecha_reg<?php echo $id_cliente;?>">
        <input type="hidden" value="<?php echo $documento?>" id="documento<?php echo $id_cliente;?>">

        <tr>

            <td><?php echo $id_cliente; ?></td>
            <td><?php echo $nombre_cliente; ?></td>
            <td>$<?php echo $telefono_cliente; ?></td>
            <td><?php echo $email_cliente;?></td>
            <td><?php echo $con." - ".$tipo; ?></td>
            <td><?php echo $documento;?></td>
            <td><?php echo $direccion_cliente;?></td>
            <td><?php echo $fecha?></td>


            <td><span class="pull-right">
                    <a href="#" class='btn btn-default' title='Editar pago' onclick="obtener_datos('<?php echo $id_cliente;?>');" data-toggle="modal" data-target="#myModal2"><i class="glyphicon glyphicon-edit"></i></a>
                    <a href="#" class='btn btn-default' title='Borrar pago' onclick="eliminar('<?php echo $id_cliente; ?>')"><i class="glyphicon glyphicon-trash"></i> </a></span></td>

        </tr>
        <?php
				}
				?>
        <tr>
            <td colspan=7><span class="pull-right">
                    <?
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?>
                </span></td>
        </tr>
    </table>
</div>
<?php
		}
	}
?>
