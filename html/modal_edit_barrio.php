<div id="editBarrioModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form name="edit_barrio" id="edit_barrio">
				<div class="modal-header">						
					<h4 class="modal-title">Editar Barrio</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
					<div class="form-group">
						<label>Barrio/label>
						<input type="text" name="edit_name" id="edit_name" class="form-control" required>
					</div>
					<div class="form-group" hidden="">
						<label>Código</label>
						<input type="text" name="edit_code" id="edit_code" class="form-control">
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