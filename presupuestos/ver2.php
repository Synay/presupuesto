<?php
require_once "../functions/funcionesPresupuesto.php";
?>
<div class="modal fade" id="verPresupuesto2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content modal-lg">
			<div class="modal-header">						
				<h4 class="modal-title">Detalle del<b> presupuesto</b></h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				
				<table class="table table-hover">
					<thead>
						<tr class="table-active"><th></th><th>Nombre</th><th><center>Unidad</center></th><th style="text-align: right;">Cantidad</th><th style="text-align: right;">Precio ($)</th><th style="text-align: right;">Subtotal ($)</th></tr>
					</thead>
					<?php ver_presupuesto1();?>
				</table>					
			</div>
			<div class="modal-footer justify-content-start">
				<div class='my-legend'>
					<div class='legend-scale'>
						<ul class='legend-labels'>
							<li><span style='background:#ffeeba;'></span>Precios de insumos no cotizados</li>
							<li><span style='background:#c3e6cb;'></span>Precios de insumos cotizados</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>