	<?php
		if (isset($con))
		{
	?>
	<!-- Modal -->
	<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar Cuenta por Cobrar</h4>
	            </div>

	            <div class="modal-body">

	                <form class="form-horizontal" method="post" id="editar_cliente" name="editar_cliente">

	                    <div id="resultados_ajax2"></div>

	                    <div class="form-group">
	                        <input type="hidden" name="mod_id" id="mod_id">
	                        <label for="nombre" class="col-sm-3 control-label">Cliente</label>
	                        <div class="col-sm-8">



	                            <input type="text" class="form-control" id="mod_cliente" name="mod_cliente" readonly>

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

	                            <select class="form-control" name="mod_concepto" id="mod_concepto" disabled>
	                                <option value="">-- Seleccione un concepto --</option>
	                                <?php  foreach ($result as $r): ?>

	                                <option value="<?php echo $r['ID']; ?>"> (<?php echo $r['ID'] ,") ", $r['concepto']; ?> - <?php echo $r['tipo']; ?></option>
	                                <?php endforeach; ?>

	                            </select>

	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Factura</label>
	                        <div class="col-sm-8">
	                            <input type="text" class="form-control" id="mod_factura" name="mod_factura" required>
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Fecha aplicaci&oacute;n</label>
	                        <div class="col-sm-8">
	                            <input type="date" class="form-control" id="mod_fecha_ap" name="mod_echa_ap" readonly>
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Fecha vencimiento</label>
	                        <div class="col-sm-8">
	                            <input type="date" class="form-control" id="mod_fecha_ven" name="mod_fecha_ven" readonly>
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Monto</label>
	                        <div class="col-sm-8">
	                            <input type="text" class="form-control" id="mod_monto" name="mod_monto" required>
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="telefono" class="col-sm-3 control-label">Observaciones</label>
	                        <div class="col-sm-8">
	                            <textarea class="form-control" name="mod_obs" id="mod_obs" cols="30" rows="10" required>
	                            </textarea>
	                        </div>
	                    </div>

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	                <button type="submit" class="btn btn-primary" id="actualizar_datos">Actualizar datos</button>
	            </div>
	            </form>
	        </div>
	    </div>
	</div>
	<?php
		}
	?>
