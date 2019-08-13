<div class="modal fade" id="cotizarPresupuesto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content modal-lg">
			<form method="POST" name="cotizar_presupuesto" id="cotizar_presupuesto">
				<div class="modal-header">						
					<h4 class="modal-title">Validar <b>Presupuesto</b></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
					<p>¿Seguro que quieres validar este presupuesto?</p>
					<input type="hidden" name="cotizar_id_presupuesto" id="cotizar_id_presupuesto">
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
					<input type="submit" name="cotizar" class="btn btn-success" value="Aceptar">
				</div>
			</form>
		</div>
	</div>
</div>