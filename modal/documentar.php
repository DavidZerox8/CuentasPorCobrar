	<?php
		if (isset($con))
		{
	?>
	<!-- Modal -->
	<div class="modal fade" id="documentar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Documentar Facturas</h4>
	            </div>
	            <div class="modal-body">
	                <form class="form-horizontal" method="post" id="documentar_facturas" name="documentar_facturas" onsubmit="documentar_n()">
	                    <div id="resultados_ajax"></div>

	                    <div class="form-group">
	                        <label for="nombre" class="col-sm-3 control-label">Cliente</label>
	                        <div class="col-sm-8">

	                            <?php 
                                $query = "SELECT id_cliente as ID, nombre_cliente as nombre FROM clientes ORDER BY id_cliente ASC;";
                                $result = mysqli_query($con, $query);
                                ?>

	                            <select class="form-control" name="cliente" id="cliente" required>
	                                <option value="">-- Seleccione un cliente disponible --</option>
	                                <?php  foreach ($result as $r): ?>

	                                <option value="<?php echo $r['ID']; ?>"> <?php echo $r['ID'] ," ", $r['nombre']; ?> </option>
	                                <?php endforeach; ?>

	                            </select>

	                        </div>
	                    </div>
	                    
	                    <div class="form-group">
	                        <label for="estado" class="col-sm-3 control-label">ID</label>
	                        <div class="col-sm-8" id="f">
                                
                            <input type="text" class="form-control" id="factura" name="factura" required>    
	                           
	                        </div>
	                    </div>


	                    <div class="form-group">
	                        <label for="estado" class="col-sm-3 control-label">Facturas</label>
	                        <div class="col-sm-8" id="facturas">

	                           
	                        </div>
	                    </div>	 	                    
	                    
	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Fecha aplicaci&oacute;n</label>
	                        <div class="col-sm-8">
	                            <input type="date" class="form-control" id="fecha_ap2" name="fecha_ap2" onchange="guardar_f()"  required>
	                        </div>
	                    </div>
	                    
	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Fecha vencimiento</label>
	                        <div class="col-sm-8">
	                            <input type="text" class="form-control" id="fecha_ven2" name="fecha_ven2" readonly>
	                        </div>
	                    </div>	             
	                    
                        <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Observaciones</label>
	                        <div class="col-sm-8">
	                            <textarea class="form-control" name="obs2" id="obs2" cols="30" rows="10"  required>  
	                            </textarea>
	                        </div>
	                    </div>                   

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	                <input type="reset" value="Limpiar formulario" class="btn btn-warning">
	                <button type="submit" class="btn btn-primary" id="guardar_datosf">Guardar datos</button>
	            </div>
	            </form>
	        </div>
	    </div>
	</div>
	<?php
		}
	?>
