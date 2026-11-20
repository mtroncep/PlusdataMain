<div id="addCiudadModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form name="add_ciudad" id="add_ciudad">
				<div class="modal-header">						
					<h4 class="modal-title">Agregar Ciudad</h4>
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
					<div class="form-group" hidden="">
						<label>Provincia</label>
						<input type="text" name="add_provincia" id="add_provincia" class="form-control">
					</div>
					<div class="form-group" hidden="">
						<label>Comarca</label>
						<input type="text" name="add_comarca" id="add_comarca" class="form-control">
					</div>
					<div class="form-group" hidden="">
						<label>Municipio</label>
						<input type="text" name="add_municipio" id="add_municipio" class="form-control">
					</div>
					<div class="form-group">
						<label>Ciudad</label>
						<input type="text" name="name_ciudad" id="name_ciudad" class="form-control" required>
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