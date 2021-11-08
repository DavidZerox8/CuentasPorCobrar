<?php

	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if (isset($_GET['id'])){
		$id_cliente=intval($_GET['id']);
		
		$count=mysqli_num_rows($query);
		if ($count==0){
			if ($delete1=mysqli_query($con,"DELETE FROM clientes WHERE id_cliente='".$id_cliente."'")){
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
    <strong>Error!</strong> No se pudo eliminar éste cliente. Existen facturas vinculadas a éste producto.
</div>
<?php
		}
				
		
	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $aColumns = array('nombre_cliente');//Columnas de busqueda
		 $sTable = "clientes";
		 $sWhere = "";
		if ( $_GET['q'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
        
		$sWhere.=" order by id_cliente asc";
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
		$reload = './clientes.php';
		//main query to fetch the data
		$sql="SELECT * FROM  $sTable $sWhere";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
            	
			?>

<div class="table-responsive">
    <table class="table">
        <tr class="info">

            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>

            <th>Dirección</th>
            <th>RFC</th>
            <th>D&iacute;as de cr&eacute;dito</th>
            <th>Contacto</th>
            <th>Agregado</th>
            <th>Deuda total</th>
            <th>Abonos realizados</th>
            <th style="text-align: center;">Acciones</th>

        </tr>
        <?php
				while ($row=mysqli_fetch_array($query)){
						$id_cliente=$row['id_cliente'];
						$nombre_cliente=$row['nombre_cliente'];
						$telefono_cliente=$row['telefono_cliente'];
						$email_cliente=$row['email_cliente'];
						$direccion_cliente=$row['direccion_cliente'];
                        
                        $rfc = $row['rfc'];
					    $contacto = $row['contacto'];	
						$date_added= date('d/m/Y', strtotime($row['date_added']));
                        $suma = $row['deuda_c'];
                        $credito = $row['credito'];
                    
                        $abonos = "select SUM(monto) as suma FROM abonos cpc WHERE id_cliente = '$id_cliente'";
            
                        $res = mysqli_query($con, $abonos);
                        $rows = $res->fetch_assoc();
            
                        if($rows){
                            $abonos_t = $rows["suma"];
                            
                            $findme   = '.';
                            $pos = strpos($abonos_t, $findme);
                            if ($pos === false) {
        
                                $abonos_t = number_format($abonos_t, 2, '.', ''); 
                            }
                        }	
					?>

        <input type="hidden" value="<?php echo $nombre_cliente;?>" id="nombre_cliente<?php echo $id_cliente;?>">
        <input type="hidden" value="<?php echo $telefono_cliente;?>" id="telefono_cliente<?php echo $id_cliente;?>">
        <input type="hidden" value="<?php echo $email_cliente;?>" id="email_cliente<?php echo $id_cliente;?>">
        <input type="hidden" value="<?php echo $direccion_cliente;?>" id="direccion_cliente<?php echo $id_cliente;?>">

        <input type="hidden" value="<?php echo $rfc;?>" id="rfc<?php echo $id_cliente;?>">

        <input type="hidden" value="<?php echo $contacto;?>" id="contacto<?php echo $id_cliente;?>">

        <input type="hidden" value="<?php echo $credito;?>" id="credito<?php echo $id_cliente;?>">




        <tr>
            <td><?php echo $id_cliente; ?></td>
            <td><?php echo $nombre_cliente; ?></td>
            <td><?php echo $telefono_cliente; ?></td>

            <td><?php echo $direccion_cliente;?></td>
            <td><?php echo $rfc;?></td>
            <td><?php echo $credito;?></td>
            <td><?php echo $contacto;?></td>
            <td><?php echo $date_added;?></td>

            <td><?php echo "$".$suma;?></td>
            <td><?php echo "$".$abonos_t; ?></td>

            <td><span class="pull-right">
                   
                    <a href="#" class='btn btn-default' title='Consultar cliente' onclick="obtener_datos('<?php echo $id_cliente;?>');" data-toggle="modal" data-target="#myModal2"><i class="glyphicon glyphicon-edit"></i></a>

                    <a href="#" class='btn btn-default' title='Baja cliente' onclick="eliminar('<?php echo $id_cliente; ?>')"><i class="glyphicon glyphicon-trash"></i> </a>

                    <a href="./reporte_c.php?id=<?php echo $id_cliente; ?>" target="_blank" class='btn btn-default' title='Reporte General'><i class="glyphicon glyphicon-list-alt"></i> </a>

                </span>
            </td>
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
