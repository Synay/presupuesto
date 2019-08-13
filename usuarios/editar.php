<div class="modal fade" id="editarUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form method="POST" id="formulario_editar_usuario" name="formulario_editar_usuario">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Editar <b>Usuario</b></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="editar_id" id="editar_id" >
					<div class="row">
						<div class="col-sm-6">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="user"></i></span>
								</div>
								<input type="text" class="form-control" name="editar_nombre" id="editar_nombre" placeholder="Nombre" tabindex="1" autofocus>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="user"></i></span>
								</div>
								<input type="text" class="form-control" name="editar_apellido" id="editar_apellido" placeholder="Apellido" tabindex="2">
							</div>
						</div>
					</div>	
					<div class="row">
						<div class="col-sm-12">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="mail"></i></span>
								</div>	
								<input type="email" class="form-control" name="editar_email" id="editar_email" placeholder="Correo electrónico" tabindex="3">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="user"></i></span>
								</div>
								<input type="text" class="form-control" name="editar_usuario" id="editar_usuario" placeholder="Nombre de usuario" tabindex="4">
							</div>
						</div>

						<div class="col-sm-12">
							<div class="input-group mb-3">
								<select class="custom-select" name="editar_privilegio" id="editar_privilegio" tabindex="5">
									<option>Elegir área...</option>
									<option value="2">Adquisiciones</option>
									<option value="3">Contabilidad</option>
									<option value="4">Gerencia</option>
									<option value="5">Operaciones</option>
								</select>
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
