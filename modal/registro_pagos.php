	<?php
		if (isset($con))
		{
	?>
	<!-- Modal -->
	<div class="modal fade" id="nuevoCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nuevo pago</h4>
	            </div>
	            <div class="modal-body">

	                <form class="form-horizontal" method="post" id="guardar_cliente" name="guardar_cliente">
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
	                        <label for="estado" class="col-sm-3 control-label">N° de factura</label>
	                        <div class="col-sm-8">

	                            <?php 
                                $query = "SELECT id_cpc as ID, n_factura as factura FROM cuentas_pc where estatus = 'Pendiente' or estatus = 'Expirado' ORDER BY id_cpc ASC";
                                $result = mysqli_query($con, $query);
                                ?>

	                            <select class="form-control" id="documento" name="documento" required>
	                                

	                            </select>
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="email" class="col-sm-3 control-label">Tipo de pago</label>
	                        <div class="col-sm-8">

	                            <select class="form-control" id="tipo" name="tipo" required onchange="obtener()">

	                                <option value="">-- Selecciones el tipo de pago --</option>
	                                <option value="Liquidacion">Liquidaci&oacute;n</option>
	                                <option value="Adelanto">Adelanto</option>

	                            </select>

	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">N° de Concepto</label>
	                        <div class="col-sm-8">
	                            <!-- <input type="text" class="form-control" id="concepto" name="concepto"> -->

	                            <?php 
                                $query = "select id_concepto as ID, desc_concepto as concepto from conceptos where tipo_concepto = 'Abono' order by ID asc";
                                $result = mysqli_query($con, $query);
                                ?>

	                            <select class="form-control" name="concepto" id="concepto" required>
	                                <option value="">-- Seleccione un concepto --</option>
	                                <?php  foreach ($result as $r): ?>

	                                <option value="<?php echo $r['ID']; ?>"> (<?php echo $r['ID'] ,") ", utf8_decode($r['concepto']); ?> </option>
	                                <?php endforeach; ?>

	                            </select>
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Monto a pagar</label>
	                        <div class="col-sm-8">
	                            <input type="text" step=any class="form-control" id="monto" name="monto" required>
	                        </div>
	                    </div>



	                    <div class="form-group">
	                        <label for="direccion" class="col-sm-3 control-label">Fecha</label>
	                        <div class="col-sm-8">
	                            <input type="date" class="form-control" id="fecha1" name="fecha1" required>

	                        </div>
	                    </div>

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	                <button type="reset" value="" class="btn btn-warning">Limpiar formulario</button>
	                <button type="submit" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
	            </div>
	            </form>
	        </div>
	    </div>
	</div>
	<?php
		}
	?>
