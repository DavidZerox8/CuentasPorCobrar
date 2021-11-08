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
			<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nuevo cliente</h4>
		  </div>
		  <div class="modal-body">
			<form class="form-horizontal" method="post" id="guardar_cliente" name="guardar_cliente">
			<div id="resultados_ajax"></div>
          
              <div class="form-group">
				<label for="nombre" class="col-sm-3 control-label">ID</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="id_c" name="id_c" required>
				</div>
			  </div>
          
			  <div class="form-group">
				<label for="nombre" class="col-sm-3 control-label">Nombre</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="nombre" name="nombre" required>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="telefono" class="col-sm-3 control-label">Teléfono</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="telefono" name="telefono" required>
				</div>
			  </div>
			  
			  <div class="form-group" style="display:none;">
				<label for="email" class="col-sm-3 control-label">Email</label>
				<div class="col-sm-8">
					<input type="email" class="form-control" id="email" name="email">
				  
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="direccion" class="col-sm-3 control-label">Dirección</label>
				<div class="col-sm-8">
					<textarea class="form-control" id="direccion" name="direccion" maxlength="255" required></textarea>
				  
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="telefono" class="col-sm-3 control-label">RFC</label>
				<div class="col-sm-8">
				  <input maxlength="13" type="text" class="form-control" id="rfc" name="rfc" required>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="telefono" class="col-sm-3 control-label">D&iacute;as de cr&eacute;dito</label>
				<div class="col-sm-8">
				  <input type="number"  maxlength="3" class="form-control" id="credito" name="credito" required>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="telefono" class="col-sm-3 control-label">Contacto</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="contacto" name="contacto" required>
				</div>
			  </div>
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="reset" value="Limpiar Formulario" class="btn btn-warning">Limpiar Formulario</button>
			<button type="submit" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
		  </div>
		  </form>
		</div>
	  </div>
	</div>
	<?php
		}
	?>