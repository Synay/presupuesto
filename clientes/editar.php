<div class="modal fade" id="editarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form method="POST" id="formulario_editar_cliente" name="formulario_editar_cliente">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Editar <b>Cliente</b></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				<input type="hidden" name="editar_id_cliente" id="editar_id_cliente" >			
					<div class="row">
						<div class="col-sm-12">
							<label>Cliente:</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="user"></i></span>
								</div>
								<input type="text" class="form-control" name="editar_nombre_cliente" id="editar_nombre_cliente"  placeholder="Nombre de la empresa" tabindex="1">
							</div>
						</div>
						<div class="col-sm-6">
							<label>RUT:</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="hash"></i></span>
								</div>
								<input type="text" class="form-control" name="editar_rut_cliente" id="editar_rut_cliente"  placeholder="RUT" tabindex="2">
							</div>
						</div>

						<div class="col-sm-6">
							<label>Correo electrónico:</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="mail"></i></span>
								</div>
								<input type="email" class="form-control input-medium" name="editar_email_cliente" id="editar_email_cliente"  placeholder="Correo electrónico" tabindex="3">
							</div>
							
						</div>
						<div class="col-sm-12">
							<label>Dirección:</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="map-pin"></i></span>
								</div>
								<input type="text" class="form-control input-medium" name="editar_direccion" id="editar_direccion" placeholder="Dirección de la empresa" tabindex="6">
							</div>
						</div>
						<div class="col-sm-6">
							<label>Teléfono</label><sub> (Código de área + número. Ejemplo: <b>+56 65 2348911</b>)</sub>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="phone"></i></span>
								</div>
								<input type="text" class="form-control" name="editar_contacto" id="editar_contacto" tabindex="4">
							</div>
						</div>

						<div class="col-sm-6">
							<label>Celular</label><sub> (Anteponer 9 + número. Ejemplo: <b>+56 9 68951608</b>)</sub>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="phone"></i></span>
								</div>
								<input type="text" class="form-control" name="editar_contacto2" id="editar_contacto2" tabindex="5">
							</div>
						</div>             
					</div>		
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="editar" class="btn btn-info">Guardar Cambios</button>
				</div>
			</form>
		</div>
	</div>
</div>
