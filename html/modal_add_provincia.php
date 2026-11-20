<div id="addProvinciaModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form name="add_provincia" id="add_provincia">
				<div class="modal-header">						
					<h4 class="modal-title">Agregar Provincia</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
					<div class="form-group" hidden="">
						<label>Pais</label>
						<input type="text" name="add_pais"  id="add_pais" class="form-control">
					</div>
					<div class="form-group" hidden="">
						<label>Región</label>
						<input type="text" name="add_region" id="add_region" class="form-control">
					</div>
					<div class="form-group">
						<label>Provincia</label>
						<input type="text" name="name_provincia" id="name_provincia" class="form-control" required>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
					<input type="submit" class="btn btn-success" value="Guardar datos">
				</div>
			</form>
		</div>
	</div>
</div>
