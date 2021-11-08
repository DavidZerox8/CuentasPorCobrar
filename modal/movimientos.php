	<?php
		if (isset($con))
		{
	?>
	<!-- Modal -->
	<div class="modal fade" id="movimientos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-zoom-in'></i> Reporte de movimientos </h4>
	            </div>
	            <div class="modal-body">
                <!-- Inicio del formulario -->
	                <form action="./reporte_m.php" class="form-horizontal" method="post" id="documentar_facturas" name="documentar_facturas" target="_blank">

	                    <div class="form-group">
	                        <label for="nombre" class="col-sm-3 control-label">Cliente</label>
	                        <div class="col-sm-8">

	                            <?php 
                                $query = "SELECT id_cliente as ID, nombre_cliente as nombre FROM clientes ORDER BY id_cliente ASC;";
                                $result = mysqli_query($con, $query);
                                ?>

	                            <select class="form-control" name="cliente" id="cliente" required>
	                                <option value="">-- Seleccione un cliente disponible --</option>
	                                <option value="*">- Todos los clientes - </option>
	                                <?php  foreach ($result as $r): ?>

	                                <option value="<?php echo $r['ID']; ?>"> <?php echo $r['ID'] ," ", $r['nombre']; ?> </option>
	                                <?php endforeach; ?>

	                            </select>

	                        </div>
	                    </div>
                        
                        <!-- Inputs de fechas en caso de que se necesiten
                        
	                    <div class="form-group">
	                        <center><label for="estado" class="col-sm-3 control-label">Periodo: </label></center>
	                        <div class="col-sm-8">
	                            <label for="estado" class="col-sm-3 control-label">(Opcional)</label>
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Del: </label>
	                        <div class="col-sm-8">
	                            <input type="date" class="form-control" id="fecha_1" name="fecha_1">
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Al: </label>
	                        <div class="col-sm-8">
	                            <input type="date" class="form-control" id="fecha_2" name="fecha_2">
	                        </div>
	                    </div>
	                    
	                    -->
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	                <input type="reset" value="Limpiar formulario" class="btn btn-warning">
	                <button type="submit" class="btn btn-primary" id="guardar_datosf">Generar reporte</button>
	            </div>
	            </form>
	        </div>
	    </div>
	</div>
	<?php
		}
	?>
