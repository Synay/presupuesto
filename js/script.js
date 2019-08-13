//Tooltip de los mensajes sobrepuesto
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});
//Traducción para la tabla de reportes
var idioma=

            {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copyTitle": 'Información copiada',
                    "copyKeys": 'Use su teclado o menú para seleccionar el comando copiar',
                    "copySuccess": {
                        "_": '%d filas copiadas al portapapeles',
                        "1": '1 fila copiada al portapapeles'
                    },

                    "pageLength": {
                    "_": "Mostrar %d filas",
                    "-1": "Mostrar Todo"
                    }
                }
            };
    //Obtener Fecha de hoy
    var fecha_actual = new Date();
    var month = fecha_actual.getMonth()+1;
    var day = fecha_actual.getDate();

    var output = ((''+day).length<2 ? '0' : '') + day + '_' +
    ((''+month).length<2 ? '0' : '') + month + '_' +
     fecha_actual.getFullYear();

//Datatable de usuarios , clientes, diferentes presupuestos traducidos al español
$(document).ready(function() {
  $('#tablaUsuarios').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
    },
    "order": [[ 0, "desc" ]]
  });
  $('#tablaClientes').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
    },
    "order": [[ 0, "desc" ]]
  });
  $('#tablaPresupuestos1').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
    },
    "order": [[ 0, "desc" ]]
  });
  $('#tablaPresupuestos2').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
    },
    "order": [[ 0, "desc" ]]
  });
  $('#tablaPresupuestos3').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
    },
    "order": [[ 0, "desc" ]]
  });
  $('#tablaPresupuestos4').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
    },
    "order": [[ 0, "desc" ]]
  });


    $('#tablaPresupuestosReportes').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "language": idioma,
    "lengthMenu": [[5,10,20, -1],[5,10,50,"Mostrar Todo"]],
     dom: 'Blfrtip',
     buttons:{
     dom: {
            container:{
              tag:'div',
              className:'flexcontent'
            },
            buttonLiner: {
              tag: null
            }
          },
        buttons: [
                    {
                        extend:    'copyHtml5',
                        text:      '<i class="fa fa-clipboard"></i> Copiar',
                        title:'Listado de presupuestos',
                        titleAttr: 'Copiar',
                        className: 'btn-a btn-app btn btn-info'
                    },

                    {
                        extend:    'pdfHtml5',
                        text:      '<i class="far fa-file-pdf"></i> PDF',
                        title:'Listado de presupuestos',
                        filename: 'presupuestos_'+ output+'',
                        className: 'btn-a btn-app btn btn-danger',                       
                        customize:function(doc) {
                            doc.styles.title = {
                                color: '#4c8aa0',
                                fontSize: '20',
                                alignment: 'center'
                            }
                            doc.styles['td:nth-child(2)'] = { 
                                width: '100px',
                                'max-width': '100px',
                                alignment: 'left'
                            },
                            doc.styles.tableHeader = {
                                fillColor:'#4c8aa0',
                                color:'white',
                                alignment:'left'
                            },
                            doc.content[1].margin = [ 10, 10, 10, 10 ],
                            doc.defaultStyle.fontSize = 8
                        }
                    },
                    {
                        extend:    'excelHtml5',
                        text:      '<i class="fa fa-file-excel"></i> Excel',
                        title:'Listado de presupuestos',
                        filename: 'presupuestos_'+ output+'',
                        titleAttr: 'Excel',
                        className: 'btn-a btn-app btn btn-success'
                    },
                    {
                        extend:    'csvHtml5',
                        text:      '<i class="fa fa-file-csv"></i> CSV',
                        title:'Listado de presupuestos',
                        filename: 'presupuestos_'+ output+'',
                        titleAttr: 'CSV',
                        className: 'btn btn-app btn btn-warning'
                    }
                ]
          }
  });
} );

//Tiempo de las alertas
$(document).ready(function() {
  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
      $(this).remove(); 
    });
  }, 4000);
} );

//Formato del rut del cliente para el input
$("#rut_cliente").inputmask({
  mask: "9[9.999.999]-[9|K|k]",
});

//Formato del teléfono del cliente para el input
$("#contacto").inputmask({
  mask: "+56[ 99 9999999]",
});

//Formato del celular del cliente para el input
$("#contacto2").inputmask({
  mask: "+56[ 9 99999999]",
});

//Formato para el rut del cliente editado para el input
$("#editar_rut_cliente").inputmask({
  mask: "9[9.999.999]-[9|K|k]",
});

//Formato para el rut de la empresa editado para el input
$("#editar_rut_empresa").inputmask({
  mask: "9[9.999.999]-[9|K|k]",
});

//Formato del teléfono del cliente para el input
$("#editar_contacto").inputmask({
  mask: "+56[ 99 9999999]",
});

//Formato del celular del cliente para el input
$("#editar_contacto2").inputmask({
  mask: "+56[ 9 99999999]",
});

//Formato del teléfono de la empresa para el input
$("#editar_contacto_empresa").inputmask({
  mask: "+56[ 99 9999999]",
});

//Función que obtiene los datos del usuario a la vista del modal
$('#editarUsuario').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget) // Botón que activó el modal
          var nombre = button.data('nombre') 
          $('#editar_nombre').val(nombre)
          var apellido = button.data('apellido') 
          $('#editar_apellido').val(apellido)
          var email = button.data('email') 
          $('#editar_email').val(email)
          var usuario = button.data('usuario') 
          $('#editar_usuario').val(usuario)
          var privilegio = button.data('privilegio') 
          $('select[name=editar_privilegio').val(privilegio)
          var id = button.data('id') 
          $('#editar_id').val(id)
        });

//Función que obtiene los datos del cliente a la vista del modal
$('#editarCliente').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget) // Botón que activó el modal
          var nombre_cliente = button.data('nombre_cliente') 
          $('#editar_nombre_cliente').val(nombre_cliente)
          var rut_cliente = button.data('rut_cliente') 
          $('#editar_rut_cliente').val(rut_cliente)
          var email_cliente = button.data('email_cliente') 
          $('#editar_email_cliente').val(email_cliente)
          var direccion = button.data('direccion') 
          $('#editar_direccion').val(direccion)
          var contacto = button.data('contacto') 
          $('#editar_contacto').val(contacto)
          var contacto2 = button.data('contacto2') 
          $('#editar_contacto2').val(contacto2)
          var id_cliente = button.data('id_cliente') 
          $('#editar_id_cliente').val(id_cliente)
        });

//Función que obtiene el id de un usuario en específico
$('#eliminarUsuario').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Botón que activo el modal
      var id = button.data('id') 
      $('#eliminar_id').val(id)
    });

//Función que obtiene el id de un usuario en específico
$('#eliminarCliente').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Botón que activo el modal
      var id_cliente = button.data('id_cliente') 
      $('#eliminar_id_cliente').val(id_cliente)
    });

//Función que obtiene el id de un presupuesto en específico
$('#cotizarPresupuesto').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Botón que activo el modal
      var id_presupuesto = button.data('id_presupuesto') 
      $('#cotizar_id_presupuesto').val(id_presupuesto)
    });

//Función que obtiene el id de un presupuesto en específico
$('#eliminarPresupuesto').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Botón que activo el modal
      var id_presupuesto = button.data('id_presupuesto') 
      $('#eliminar_id_presupuesto').val(id_presupuesto)
    });

//Función que obtiene el id de un presupuesto en específico
$('#ejecucionPresupuesto').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Botón que activo el modal
      var id_presupuesto = button.data('id_presupuesto') 
      $('#ejecucion_id_presupuesto').val(id_presupuesto)
    });

//Función que obtiene el id de un presupuesto en específico
$('#controlarPresupuesto').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Botón que activo el modal
      var id_presupuesto = button.data('id_presupuesto') 
      $('#controlar_id_presupuesto').val(id_presupuesto)
    });

//Función que obtiene la fecha actual con respecto a la fecha que se desee
$(document).ready(function() {
 var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
 $('#fecha_presupuesto').datepicker({
   locale: 'es-es',
   format: 'dd/mm/yyyy',
   uiLibrary: 'bootstrap4',
   iconsLibrary: 'feather',
   minDate: today,
   maxDate: function () {
    return $('#fecha_termino').val();
  }
});
 $('#fecha_termino').datepicker({
  locale: 'es-es',
  format: 'dd/mm/yyyy',
  uiLibrary: 'bootstrap4',
  iconsLibrary: 'feather',
  minDate: function () {
    return $('#fecha_presupuesto').val();
  }
});
});

//Función que busca un cliente en el registro
$(document).ready(function() {
  $( ".busquedaCliente" ).select2({
    language: "es",
    minimumInputLength: 2,        
    ajax: {
      url: "ajax/clientes_json.php",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
                q: params.term // término de búsqueda
              };
            },
            processResults: function (data) {
              return {
                results: data
              };
            },
            cache: true
          },
          minimumInputLength: 2
        })
});

//Función que muestra el detalle del presupuesto para el grupo de operaciones
function mostrarDetalles(id) {
  var ruta = 'presupuestos/ver.php?id=' + id;
  $.get(ruta, function (data) {
    $('#divPresupuesto').html(data);
    $('#verPresupuesto').modal('show');
  });
}
//Función que muestra el detalle del presupuesto para el grupo de adquisiciones
function mostrarDetalles2(id) {
  var ruta = 'presupuestos/ver2.php?id=' + id;
  $.get(ruta, function (data) {
    $('#divPresupuesto2').html(data);
    $('#verPresupuesto2').modal('show');
  });
}

//Función que muestra el detalle del presupuesto para el grupo de adquisiciones
function mostrarDetalles3(id) {
  var ruta = 'presupuestos/ver3.php?id=' + id;
  $.get(ruta, function (data) {
    $('#divPresupuesto3').html(data);
    $('#verPresupuesto3').modal('show');
  });
}

//Función que agrega una fila de categoría
function add_row()
{
 $rowno=$("#categoria_tabla tr").length;
 $rowno=$rowno+1;
 $("#categoria_tabla tr:last").after("<tr id='row"+$rowno+"'><td><div class=\"input-group mb-3\"><input class=\"categoria form-control\" type='text' name='categoria[]' placeholder='Nombre de la categoría' required></div></td><td><button type=\"button\" class=\"delete btn btn-danger\" onclick=\"delete_row('row"+$rowno+"')\"> Eliminar</button></td></tr>");
}
//Función que elimina una fila de categoría 
function delete_row(rowno)
{
 $('#'+rowno).remove();
}

//Función que agrega una fila de subcategoría con respecto a la categoría creada
function add_row2(id)
{
 $rowno=$("#subcategoria_tabla_"+id+" tr").length;
 $rowno=$rowno+1;
 $("#subcategoria_tabla_"+id+" tr:last").after("<tr id='"+id+"row"+$rowno+"'><td><input type=\"hidden\" name=\"categoria_id[]\" id=\"categoria_id[]\" value="+id+" ><div class=\"input-group mb-3\"><input class=\"subcategoria form-control\" type='text' name='subcategoria[]' placeholder='Nombre de la subcategoría' required></div></td><td><button type=\"button\" class=\"delete btn btn-danger\" onclick=\"delete_row2('"+id+"row"+$rowno+"')\"> Eliminar</button></td></tr>");
}
//Función que elimina una fila de subcategoría
function delete_row2(rowno)
{
 $('#'+rowno).remove();
}

//Función que agrega una fila de insumo con respecto a la subcategoría creada
function add_row3(id)
{
 $rowno=$("#insumo_tabla_"+id+" tr").length;
 $rowno=$rowno+1;
 var first = document.getElementById('tipo[]');
 var options = first.innerHTML;
 $("#insumo_tabla_"+id+" tr:last").after("<tr id='"+id+"row"+$rowno+"'><td><input type=\"hidden\" name=\"subcategoria_id[]\" id=\"subcategoria_id[]\" value="+id+" ><div class=\"input-group mb-3\"><input class=\"insumo form-control\" type='text' name='insumo[]' placeholder='Descripción del insumo' required></div></td><td><div class=\"input-group mb-3\"><div class=\"input-group mb-3\"><select name=\"tipo[]\" id=\"tipo[]\" class=\"listarTipo custom-select\" required>"+options+"</select></div></div></td><td><div class=\"input-group mb-3\"><input class=\"cantidad form-control\" type=\"number\" min=\"0.01\" step=\"0.01\" name=\"cantidad[]\" placeholder=\"Cantidad\" required></div></td><td><button type=\"button\" class=\"delete btn btn-danger\" onclick=\"delete_row3('"+id+"row"+$rowno+"')\"> Eliminar</button></td></tr>");

}
//Función que elimina la fila de insumo
function delete_row3(rowno)
{
 $('#'+rowno).remove();
}

//Función que formatea con separadores de miles un número en el input del precioI
$(".precioI").on({
  "focus": function(event) {
    $(event.target).select();
  },
  "keyup": function(event) {
    $(event.target).val(function(index, value) {
      return value.replace(/\D/g, "")
      .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
    });
  }
});

//Función que calcula el precio*cantidad en subtotal y total para los inputs de solo lectura
$(function () {
  $('input[name^=cantidad_o], input[name^=subtotal], input[name=total], input[name=total_real], input[name=dif]').prop('readonly', true);
  var $tblrows = $("#tabla_precio tbody tr");

  $tblrows.each(function (index) {
    var $tblrow = $(this);

    $tblrow.find('input[name^=precio_insumo]').on('change', function () {

      var qty = $tblrow.find("[name^=cantidad_o]").val();
      var price = $tblrow.find("[name^=precio_insumo]").val();
      var price2 = price.split('.').join("");
      var subTotal = parseInt(qty, 10) * price2;

      if (!isNaN(subTotal)) {

        $tblrow.find('input[name^=subtotal]').val(subTotal.toString().replace(/\D/g, "")
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));
        var grandTotal = 0;

        $("input[name^=subtotal]").each(function () {
          var stval = $(this).val().split('.').join("");
          var stval2 = parseFloat(stval.split('.').join(""));
          grandTotal += stval2;
        });

        $('input[name=total]').val(grandTotal.toString().replace(/\D/g, "")
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));
      }
    });
  });
});

//Función que de los rangos de los porcentajes de costo de aministración
$('.porcentaje_adm').jRange({
  from: 0,
  to: 100,
  step: 1,
  scale: [0,10,20,30,40,50,60,70,80,90,100],
  format: '%s',
  width: 350,
  showLabels: true,
  snap: true,
  theme: 'theme-blue'
});

//Función que de los rangos de los porcentajes de costo de utilidad
$('.porcentaje_rent').jRange({
  from: 0 ,
  to: 100,
  step: 1,
  scale: [0,10,20,30,40,50,60,70,80,90,100],
  format: '%s',
  width: 350,
  showLabels: true,
  snap: true,
  theme: 'theme-blue'
});

//Función que calcula los porcentajes de administración y de rentabilidad
$(function () {
  $('input[name=costo], input[name=porcentaje_adm2], input[name=total_adm], input[name=porcentaje_rent2],input[name=total_total]').prop('readonly', true);
  $('input[name=porcentaje_adm]').on('change', function () {
    var porAdm = $("[name=porcentaje_adm]").val();
    var cost =  $('input[name=costo]').val();
    var cost2 = cost.replace('.', '');

    var subTotal = parseInt(porAdm, 10) * cost2;
    var subTotal2 = (subTotal/100).toFixed(3);
    var subTotal3 = parseFloat(subTotal2.replace('.', ''));

    if (!isNaN(subTotal3)) {
      $('input[name=porcentaje_adm2]').val(subTotal3.toString().replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));

      var subTotal4 = ((subTotal/100)+parseFloat(cost2)).toFixed(3);
      var subTotal5 = parseFloat(subTotal4.replace('.', ''));

      if(!isNaN(subTotal5)){
        $('input[name=total_adm]').val(subTotal5.toString().replace(/\D/g, "")
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));

        var porRent = $("[name=porcentaje_rent]").val();
        var total_adm =  $('input[name=total_adm]').val();
        var total_adm2 = total_adm.replace('.', '');

        var subTotal6 = parseInt(porRent, 10) * total_adm2;
        var subTotal7 = (subTotal6/100).toFixed(3);
        var subTotal8 = parseFloat(subTotal7.replace('.', ''));

        if (!isNaN(subTotal8)) {
          $('input[name=porcentaje_rent2]').val(subTotal8.toString().replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));

          var subTotal9 = ((subTotal6/100)+parseFloat(total_adm2)).toFixed(3);
          var subTotal10 = parseFloat(subTotal9.replace('.', ''));

          if(!isNaN(subTotal10)){
            $('input[name=total_total]').val(subTotal10.toString().replace(/\D/g, "")
              .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));
          }
        }
      }
    }
  });
  $('input[name=porcentaje_rent]').on('change', function () {
    var porRent = $("[name=porcentaje_rent]").val();
    var total_adm =  $('input[name=total_adm]').val();
    var total_adm2 = total_adm.replace('.', '');

    var subTotal = parseInt(porRent, 10) * total_adm2;
    var subTotal2 = (subTotal/100).toFixed(3);
    var subTotal3 = parseFloat(subTotal2.replace('.', ''));

    if (!isNaN(subTotal3)) {
      $('input[name=porcentaje_rent2]').val(subTotal3.toString().replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));

      var subTotal4 = ((subTotal/100)+parseFloat(total_adm2)).toFixed(3);
      var subTotal5 = parseFloat(subTotal4.replace('.', ''));

      if(!isNaN(subTotal5)){
        $('input[name=total_total]').val(subTotal5.toString().replace(/\D/g, "")
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));
      }
    }
  });
});

//Función que calcula los gastos reales con respectos a los gastos previstos
$(function () {
  $('input[name^=cantidad_r], input[name^=subtotal], input[name^=subt_r], input[name^=diferencia] , input[name=total], input[name=total_real], input[name=gasto_adm], input[name=gasto_adm2], input[name=gasto_total_prev], input[name=gasto_total_real]').prop('readonly', true);
  var $tblrows = $("#tabla_gasto tbody tr");

  $tblrows.each(function (index) {
    var $tblrow = $(this);

    $tblrow.find('input[name^=precio_r]').on('change', function () {

      var qty = $tblrow.find("[name^=cantidad_r]").val();
      var price = $tblrow.find("[name^=precio_r]").val();
      var price2 = price.split('.').join("");
      var subTotal = parseInt(qty, 10) * price2;

      if (!isNaN(subTotal)) {

        $tblrow.find('input[name^=subt_r]').val(subTotal.toString().replace(/\D/g, "")
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));
        var grandTotal = 0;

        $("input[name^=subt_r]").each(function () {
          var stval = $(this).val().split('.').join("");
          var stval2 = parseFloat(stval.split('.').join(""));
          grandTotal += stval2;
        });

        $('input[name=total_real]').val(grandTotal.toString().replace(/\D/g, "")
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));


        var subt_prev = $tblrow.find("[name^=subtotal_prev]").val();
        var subt_real = $tblrow.find("[name^=subt_r]").val();
        var subt_prev2 = subt_prev.split('.').join("");
        var subt_real2 = subt_real.split('.').join("");
        var subTotal2 = subt_prev2 - subt_real2;


        if (!isNaN(subTotal2)) {
          $tblrow.find('input[name^=diferencia]').val(subTotal2.toString()
            .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));
          if(subTotal2>0){
           $tblrow.find('input[name^=diferencia]').css("color","green");
         }
         else if(subTotal2<0){
          $tblrow.find('input[name^=diferencia]').css("color","red");
        }else{
          $tblrow.find('input[name^=diferencia]').css("color","black");
        }
        var grandTotal2 = 0;

        $("input[name^=diferencia]").each(function () {
          var stval3 = $(this).val().split('.').join("");
          var stval4 = parseFloat(stval3.split('.').join(""));
          grandTotal2 += stval4;
        });

        $('input[name=dif]').val(grandTotal2.toString()
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));
        if(grandTotal2>0){
         $('input[name=dif]').css("color","green");
       }
       else if(grandTotal2<0){
        $('input[name=dif]').css("color","red");
      }else{
        $('input[name=dif]').css("color","black");
      }
    }
  }
});
  });
});

//Función que deja una fecha de gasto entre la fecha del presupuesto
$(function () {
  $("input[name^=fecha_gasto]").attr('readonly',true).each(function () {
    var fecha_min = $(this).data("fecha_min")
    var fecha_max = $(this).data("fecha_max")
    $(this).datepicker({
     locale: 'es-es',
     format: 'dd/mm/yyyy',
     uiLibrary: 'bootstrap4',
     iconsLibrary: 'feather',
     minDate: new Date(fecha_min),
     maxDate: new Date(fecha_max)
   });
  });
});

$(function () {
  $("input[name^=fecha_g]").each(function () {
    var fecha_min = $(this).data("fecha_min")
    var fecha_max = $(this).data("fecha_max")
    $(this).datepicker({
     locale: 'es-es',
     format: 'dd/mm/yyyy',
     uiLibrary: 'bootstrap4',
     iconsLibrary: 'feather',
     minDate: new Date(fecha_min),
     maxDate: new Date(fecha_max)
   });
  });
});