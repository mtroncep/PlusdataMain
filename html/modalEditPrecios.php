<div id="editProductModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="edit_product" id="edit_product">
					<div class="modal-header">						
						<h4 class="modal-title">Editar Precio</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Importe</label>
							<input type="text" name="edit_importe" id="edit_importe" class="form-control" required autofocus>
						</div>
						<div class="form-group">
							<label>Cantidad Días</label>
							<input type="text" name="edit_cantidad" id="edit_cantidad" class="form-control" required autofocus>
						</div>
						<div class="form-group" hidden=>
							<label>Id</label>
							<input type="text" name="edit_id" id="edit_id" class="form-control">
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
						<input type="submit" class="btn btn-info" value="Guardar datos">
					</div>
				</form>
			</div>
		</div>
	</div>