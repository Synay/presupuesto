<div class="modal fade" id="eliminarPresupuesto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content modal-lg">
			<form method="POST" name="eliminar_presupuesto" id="eliminar_presupuesto">
				<div class="modal-header">						
					<h4 class="modal-title">Eliminar <b>Presupuesto</b></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
					<p>¿Seguro que quieres eliminar este presupuesto?</p>
					<p class="text-warning"><small> Esta acción no se puede deshacer.</small></p>
					<input type="hidden" name="eliminar_id_presupuesto" id="eliminar_id_presupuesto">
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
					<input type="submit" name="eliminar" class="btn btn-danger" value="Eliminar">
				</div>
			</form>
		</div>
	</div>
</div>