	<?php
		if (isset($con))
		{
	?>
	<!-- Modal -->
	<div class="modal fade" id="nuevoCPC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="reset()"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nueva Cuenta por Cobrar</h4>
	            </div>
	            <div class="modal-body">
	                <form class="form-horizontal" method="post" id="guardar_cliente" name="guardar_cliente">
	                    <div id="resultados_ajax"></div>

	                    <div class="form-group">
	                        <label for="nombre" class="col-sm-3 control-label">ID</label>
	                        <div class="col-sm-8">
	                            <?php 
            
                                    $query = "SELECT id_cliente as ID, nombre_cliente as nombre FROM clientes ORDER BY id_cliente ASC";
                                    $result = mysqli_query($con, $query);
            
                                ?>
	                           <select class="form-control" id="cliente_cc" name="cliente_cc">
                                    <option value="">-- Seleccione un cliente --</option>
	                                <?php foreach ($result as $r): ?>

	                                <option value="<?php echo $r['ID']; ?>"> <?php echo $r['ID'] ." ". $r['nombre']; ?> </option>
	                                <?php endforeach; ?>

	                            </select>
	                            
	                            <center><strong>O</strong></center> 
	                            
	                            <input class="form-control" type="text" id="cliente_c" name="cliente_c" placeholder="Escriba la clave del cliente">
	                            
	                        </div>
	                    </div>
	                    
	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">N° de Concepto</label>
	                        <div class="col-sm-8">
	                            <!-- <input type="text" class="form-control" id="concepto" name="concepto"> -->
	                                                
	                            <?php 
                                $query = "select id_concepto as ID, desc_concepto as concepto, tipo_concepto as tipo from conceptos order by ID asc";
                                $result = mysqli_query($con, $query);
                                ?>

	                            <select class="form-control" name="concepto" id="concepto" required>
                                    <option value="">-- Seleccione un concepto --</option>
	                                <?php  foreach ($result as $r): ?>

	                                <option value="<?php echo $r['ID']; ?>"> (<?php echo $r['ID'] ,") ", $r['concepto']; ?> - <?php echo $r['tipo']; ?>  </option>
	                                <?php endforeach; ?>

	                            </select>
	                        </div>
	                    </div>	               
	                    
	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Factura(s)</label>
	                        <div class="col-sm-8">
	                            <input type="text" class="form-control" id="factura" name="factura"  required>
	                            <button style="display:none;" id="buscar_f" name="buscar_f" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Buscar factura</button>
	                        </div>
	                    </div>	                    
	                    
	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Fecha aplicaci&oacute;n</label>
	                        <div class="col-sm-8">
	                            <input type="date" class="form-control" id="fecha_ap" name="fecha_ap" onchange="guardar()"  required>
	                        </div>
	                    </div>
	                    
	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Fecha vencimiento</label>
	                        <div class="col-sm-8">
	                            <input type="text" class="form-control" id="fecha_ven" name="fecha_ven" readonly>
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Monto</label>
	                        <div class="col-sm-8">
	                            <input type="text" class="form-control" id="monto" name="monto"  required>
	                        </div>
	                    </div>	 
	                    
                      <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Observaciones</label>
	                        <div class="col-sm-8">
	                            <textarea class="form-control" name="obs" id="obs" cols="30" rows="10"  required>  
	                            </textarea>
	                        </div>
	                    </div>                

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="reset()">Cerrar</button>
	                <input type="reset" value="Limpiar formulario" class="btn btn-warning">
	                <button type="submit" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
	            </div>
	            </form>
	        </div>
	    </div>
	</div>
	<?php
		}
	?>
