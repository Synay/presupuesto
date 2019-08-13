<div class="modal fade" id="agregarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form method="POST" id="formulario_cliente" name="formulario_cliente">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Agregar <b>Cliente</b></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">			
					<div class="row">
						<div class="col-sm-12">
							<label>Cliente:</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="user"></i></span>
								</div>
								<input type="text" class="form-control" name="nombre_cliente"  placeholder="Nombre de la empresa" tabindex="1">
							</div>
						</div>
						<div class="col-sm-6">
							<label>RUT:</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="hash"></i></span>
								</div>
								<input type="text" class="form-control" name="rut_cliente" id="rut_cliente" value="<?php campo('rut_cliente') ?>" placeholder="RUT" tabindex="2">
							</div>
						</div>

						<div class="col-sm-6">
							<label>Correo electrónico:</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="mail"></i></span>
								</div>
								<input type="email" class="form-control input-medium" name="email_cliente" value="<?php campo('email_cliente') ?>" placeholder="Correo electrónico" tabindex="3">
							</div>
							
						</div>
						<div class="col-sm-12">
							<label>Dirección:</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="map-pin"></i></span>
								</div>
								<input type="text" class="form-control input-medium" name="direccion" placeholder="Dirección de la empresa" tabindex="6">
							</div>
						</div>
						<div class="col-sm-6">
							<label>Teléfono</label><sub> (Código de área + número. Ejemplo: <b>+56 65 2348911</b>)</sub>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="phone"></i></span>
								</div>
								<input type="text" class="form-control" name="contacto" id="contacto" value="<?php campo('contacto') ?>" tabindex="4">
							</div>
						</div>

						<div class="col-sm-6">
							<label>Celular</label><sub> (Anteponer 9 + número. Ejemplo: <b>+56 9 68951608</b>)</sub>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i data-feather="phone"></i></span>
								</div>
								<input type="text" class="form-control" name="contacto2" id="contacto2" value="<?php campo('contacto2') ?>" tabindex="5">
							</div>
						</div>            
					</div>		
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal" tabindex="9">Cancelar</button>
					<button type="submit" name="agregar" class="btn btn-success" tabindex="10">Aceptar</button>
				</div>
			</form>
		</div>
	</div>
</div>