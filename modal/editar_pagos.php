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
	                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar pago</h4>
	            </div>
	            <div class="modal-body">
	                <form class="form-horizontal" method="post" id="editar_cliente" name="editar_cliente">
	                    <div id="resultados_ajax2"></div>
	                    <div class="form-group">
	                   
	                        <div class="col-sm-8">
	                            
	                            <input type="hidden" name="mod_id" id="mod_id">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label for="mod_telefono" class="col-sm-3 control-label">Cliente</label>
	                        <div class="col-sm-8">

	                            <?php 
                                $query = "SELECT id_cliente as ID, nombre_cliente as nombre FROM clientes ORDER BY id_cliente ASC;";
                                $result = mysqli_query($con, $query);
                                ?>

	                            <input type="text" class="form-control" id="mod_telefono" name="mod_telefono" readonly>

	           
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="mod_email" class="col-sm-3 control-label">Monto</label>
	                        <div class="col-sm-8">
	                            <input type="text" class="form-control" id="mod_email" name="mod_email" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label for="mod_direccion" class="col-sm-3 control-label">Tipo</label>
	                        <div class="col-sm-8">
	                            <input type="text" class="form-control" id="mod_direccion" name="mod_direccion" readonly>
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="mod_estado" class="col-sm-3 control-label">Fecha de pago</label>
	                        <div class="col-sm-8">
	                            <input type="date" class="form-control" id="mod_estado" name="mod_estado">
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label for="mod_estado" class="col-sm-3 control-label">Factura</label>
	                       
                             <div class="col-sm-8">
	                            <?php 
                                $query = "SELECT Documento as Doc FROM pagos ORDER BY Doc ASC;";
                                $result = mysqli_query($con, $query);
                                ?>

	                            <input type="text" class="form-control" name="mod_doc" id="mod_doc" readonly>

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
