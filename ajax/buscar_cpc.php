<?php
    
	
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if (isset($_GET['id'])){
		$id_cliente=intval($_GET['id']);
		$query=mysqli_query($con, "select * from cuentas_pc where id_cpc='$id_cliente'");
		$count=mysqli_num_rows($query);                
        
        $factura = "select n_factura, id_cliente, monto, estatus, n_concepto from cuentas_pc where id_cpc = '$id_cliente'";        
        $result = mysqli_query($con, $factura);

        foreach ($result as $r):
            $fac = $r['n_factura'];
            $user = $r['id_cliente'];
            $total = $r['monto'];
            $estatus = $r['estatus'];
            $concepto = $r['n_concepto'];
        
        endforeach;

        $borrar_pagos = "delete from abonos where factura = '$fac'";        
        $pagos = mysqli_query($con, $borrar_pagos);
        
        $mod_deuda = "select deuda_c as deuda from clientes where id_cliente = '$user'";
        $consulta = mysqli_query($con, $mod_deuda);
        
        foreach ($consulta as $c):
            $t_deuda = $c['deuda'];        
        endforeach;
        
        $nueva_deuda = $t_deuda - $total;
        $update_cliente = "update clientes set deuda_c = '$nueva_deuda' where id_cliente = '$user'";
        $ex = mysqli_query($con,$update_cliente);

		if ($count==1 or $count==0){

            
			if ($estatus != 'Documentada' and $estatus != 'Pagado' and $delete1=mysqli_query($con,"DELETE FROM cuentas_pc WHERE id_cpc='$id_cliente'")){
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
			  <strong>Error!</strong> Las facturas documentadas o pagadas no se pueden eliminar. 
			</div>
			<?php
			
		}
			
		} else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> <?php echo $count; ?> No se pudo eliminar éste  cliente. Existen facturas vinculadas a éste producto. 
			</div>
			<?php 
		}
		
	}
	if($action == 'ajax'){
        
         $condicion = " where cuentas_pc.id_cliente = clientes.id_cliente AND cuentas_pc.n_concepto = conceptos.id_concepto order by id_cpc asc";
         $valor = "";
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $aColumns = array('cuentas_pc.id_cliente');//Columnas de busqueda
		 $sTable = "cuentas_pc";
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
			$sWhere .= '';
            $valor = "AND cuentas_pc.n_concepto = conceptos.id_concepto AND cuentas_pc.id_cliente = clientes.id_cliente order by id_cpc";
		}
		$sWhere.=" ";
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './cpc.php';
		//main query to fetch the data
		$sql="SELECT *, clientes.nombre_cliente as cliente, conceptos.desc_concepto as nombrec, conceptos.tipo_concepto as tipoc FROM  $sTable, clientes, conceptos $condicion $sWhere $valor";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			
			?>
			
			
			<div class="table-responsive">
			  <table class="table">
				<tr  class="info">
				    <th>#</th>					
					<th>ID cliente</th>
					<th>Concepto</th>
					<th>Factura</th>
					<th>Fecha de aplic.</th>
					<th>Fecha venc.</th>	
					<th>Monto</th>
					
					<th>Días restantes</th>
					<th>Estado</th>	
					<th class='text-right'>Acciones</th>
					
				</tr>
				<?php
				while ($row=mysqli_fetch_array($query)){
						
                        $id_cpc = $row['id_cpc'];
                        $id_cliente = $row['id_cliente'];
                        $cliente = $row['cliente'];
                        $concepto = $row['n_concepto']; 
                        $nconcepto = $row['nombrec'];
                        $factura = $row['n_factura'];
                        $fecha_aplic = $row['fecha_aplicacion'];
                        $fecha_ven = $row['fecha_vencimiento'];
                        $monto = $row['monto'];
                        $observaciones = $row['observaciones']; 
                        $status = $row['estatus'];
                        $tipo = $row['tipoc'];

					?>					
					
					<input type="hidden" value="<?php echo $id_cpc; ?>" id="cpc<?php echo $id_cpc; ?>">
					<input type="hidden" value="<?php echo $id_cliente; ?>" id="id_cliente<?php echo $id_cpc; ?>">
					<input type="hidden" value="<?php echo $cliente; ?>" id="nombre_cliente<?php echo $id_cpc; ?>">
					<input type="hidden" value="<?php echo $concepto; ?>" id="concepto<?php echo $id_cpc; ?>">
					<input type="hidden" value="<?php echo $factura; ?>" id="factura<?php echo $id_cpc; ?>">
					<input type="hidden" value="<?php echo $fecha_aplic; ?>" id="fecha_aplic<?php echo $id_cpc; ?>">
					<input type="hidden" value="<?php echo $fecha_ven; ?>" id="fecha_ven<?php echo $id_cpc; ?>">
					<input type="hidden" value="<?php echo $monto; ?>" id="monto<?php echo $id_cpc; ?>">
					<input type="hidden" value="<?php echo $observaciones; ?>" id="obs<?php echo $id_cpc; ?>">
					
					
					<tr>
						
						<td><?php echo $id_cpc; ?></td>
						<td><?php echo $id_cliente; ?></td>
						<td><?php echo $nconcepto." - ".$tipo; ?></td>
						<td><?php echo $factura; ?></td>
						<td><?php echo $fecha_aplic; ?></td>
						<td><?php echo $fecha_ven; ?></td>
						<td><?php echo "$".$monto; ?></td>
					
						
                        <?php

                            $f1 = new DateTime(date("Y-m-d"));
                            $f2 = new DateTime($fecha_ven);
                        
                        ?>
                        
						<td><?php                     
                    
                            $diff = $f1->diff($f2);
                            $dias = $diff->days; 
                            
                            if($f1 >= $f2 and $status != 'Pagado' and $status != 'Documentada' and $status != 'Devolucion'){
                                $dias = "0";
                                
                                $status = "Expirado";
                                
                                $query_1 = "update cuentas_pc set estatus = '$status' where n_factura = '$factura'";
                                $res = mysqli_query($con, $query_1);
                            }
                    
                            elseif($status == 'Pagado' or $status == 'Documentada' or $status == 'Devolucion')
                            {
                                $dias = "0";
                            }
                    
                            else
                            {
                                /*if($status != 'Pagado' ){
                                   
                                    $query_1 = "update cuentas_pc set estatus = 'Pendiente' where n_factura = '$factura'";
                                    $res = mysqli_query($con, $query_1); 
                                }  */                              
                            }
                            
                            echo $dias; 
                    
                            ?></td>
																								
						<td><?php echo $status; ?></td>
						
					<td ><span class="pull-right">
					<a href="#" class='btn btn-default' title='Consultar CPC' onclick="obtener_datos('<?php echo $id_cpc;?>');" data-toggle="modal" data-target="#myModal2"><i class="glyphicon glyphicon-edit"></i></a> 
					<a href="#" class='btn btn-default' title='Baja CPC' onclick="eliminar('<?php echo $id_cpc; ?>')"><i class="glyphicon glyphicon-trash"></i> </a>
					
					<a target="_blank" href="reporte.php?id=<?php echo $id_cliente; ?>&fact=<?php echo $factura; ?>" class='btn btn-default' title='Reporte Cobranza'><i class="glyphicon glyphicon-file"></i> </a>
					
				<!--	<a href="#" id="documentar" class='btn btn-default' title='Documentar' onclick="documentar('<?php //echo $id_cpc; ?>')"><i class="glyphicon glyphicon-folder-open"></i> </a> -->
					
					</span></td>
						
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=7><span class="pull-right"><?
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>