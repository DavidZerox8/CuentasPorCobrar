	<?php
		if (isset($con))
		{
	?>
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Facturas</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
	                <form>
	                    <div class="form-group">
	                        <label for="recipient-name" class="col-form-label">Cliente:</label>
	                        <input type="text" class="form-control" id="recipient-name" readonly>
	                    </div>
	                    <label for="message-text" class="col-form-label">Facturas disponibles:</label>
	                    <div class="form-group" id="documento_f">

	                    </div>
	                    
	                    <div class="form-group" id="botones">
	                        
	                    </div>
	                </form>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
	                
	               <!-- <button id="btn_f" name="btn_f" type="button" class="btn btn-primary" onclick="pparametros()" data-dismiss="modal">Guardar factura</button> -->
	                
	                <button id="btn_f" name="btn_f" type="button" class="btn btn-primary" data-dismiss="modal">Guardar facturas</button>
	            </div>
	        </div>
	    </div>
	</div>
	<?php
		}
	?>
