//Validaciones de formularios
$(document).ready(function(){

    $.validator.setDefaults({
        highlight: function(element){
            $(element).addClass('invalido').removeClass('valido');
        },
        unhighlight: function(element){   
            $(element).addClass('valido').removeClass('invalido');
        }

    });

    $.validator.addMethod('validarNombre', function(value, element){
      return this.optional(element) || /^[a-zÁÉÍÓÚáéíóúñÑ\s]{2,50}$/i.test(value);
  }, 'El nombre solo puede usar letras y un espacio. Además, debe contener entre de 2 a 50 caracteres.');

    $.validator.addMethod('validarApellido', function(value, element){
        return this.optional(element) || /^[a-zÁÉÍÓÚáéíóúñÑ\s]{2,100}$/i.test(value);
    }, 'El apellido solo puede usar letras y un espacio. Además, debe contener entre de 2 a 100 caracteres.');

    $.validator.addMethod('validarUsuario', function(value, element){
        return this.optional(element) || /^[a-z][\w]{2,30}$/i.test(value);
    }, 'El nombre de usuario debe tener por lo menos 3 caracteres. Debe comenzar con una letra y solo se puede usar letras y números.');

    $.validator.addMethod('validarEmail', function(value, element){
        return this.optional(element) || /^[a-z]+[\w-\.]{2,}@([\w-]{2,}\.)+[\w-]{2,4}$/i.test(value);
    }, 'Correo electrónico debe ser en un formato válido.');

    $.validator.addMethod('validarUsuarioEmail', function(value, element){
        return this.optional(element) || /(?=^[a-z]+[\w@\.]{2,50}$)/i.test(value);
    }, 'Por favor escriba un nombre de usuario o correo electrónico válido.');

    $.validator.addMethod('validarTelefono', function(value, element){
        return this.optional(element) || /^((\+?56\s)?(0?2|0?3[2-5]|0?4[1-5]|0?5[123578]|0?6[13457]|0?7[1235])?(\s2\d{6}|\s\d{6}))$/i.test(value);
    }, 'Por favor escriba un telefóno válido.');

    $.validator.addMethod('validarCelular', function(value, element){
        return this.optional(element) || /^(\+?56)?(\s?)(0?9)(\s?)[98765]\d{7}$/i.test(value);
    }, 'Por favor escriba un celular válido.');

    $.validator.addMethod('validarRut', function(value, element){
        return this.optional(element) || /\d{1,2}\.\d{3}\.\d{3}[\-][0-9kK]{1}/i.test(value);
    }, 'Por favor escriba un RUT válido.');

    $.validator.addMethod('validarFecha', function(value, element){
        return this.optional(element) || /(^(((0[1-9]|1[0-9]|2[0-8])[\/](0[1-9]|1[012]))|((29|30|31)[\/](0[13578]|1[02]))|((29|30)[\/](0[4,6,9]|11)))[\/](19|[2-9][0-9])\d\d$)|(^29[\/]02[\/](19|[2-9][0-9])(00|04|08|12|16|20|24|28|32|36|40|44|48|52|56|60|64|68|72|76|80|84|88|92|96)$)/i.test(value);
    }, 'Por favor escriba una fecha válida.');


    $('#formulario_login').validate({
        errorPlacement: function(error, element){
            if(element.attr('type') == "textbox"){
                error.insertAfter(element.parent('div'));
            }
            else{
                error.insertAfter(element.parent('div'));  
            }
        },
        rules:{
            usuarioOemail:{
                required: true,
                validarUsuarioEmail: true
            },
            clave:{
                required: true
            }
        },
        messages:{
            usuarioOemail:{
                required: 'Nombre de usuario o correo electrónico es un campo requerido.'
            },
            clave:{
                required: 'Contraseña es un campo requerido.'
            }
        }
    });

    $('#formulario_registro').validate({
        errorPlacement: function(error, element){
            if(element.attr('type') == "checkbox"){
                error.insertAfter(element.parent('label').parent('div').parent('div'));
            }
            else{
                error.insertAfter(element.parent('div'));  
            }
        },
        rules:{
          nombre:{
             required: true,
             validarNombre: true
         },
         apellido:{
             required: true,
             validarApellido: true
         },
         usuario:{
             required: true,
             validarUsuario: true
         },
         email:{
             required: true,
             validarEmail: true
         },
         privilegio:{
            required: true
        },
        clave:{
         required: true
     },
     re_clave:{
         required: true,
         equalTo: "#clave"
     },
     terminos:{
         required: true
     }
 },
 messages:{
  nombre:{
     required: 'Nombre es un campo requerido.'
 },
 apellido:{
     required: 'Apellido es un campo requerido.'
 },
 usuario:{
     required: 'Usuario es un campo requerido.'
 },
 email:{
     required: 'Correo electrónico es un campo requerido.'
 },
 privilegio:{
    required: 'Departamento es un campo requerido.'
},
clave:{
 required: 'Contraseña es un campo requerido.'
},
re_clave:{
 required: 'Repetir contraseña es un campo requerido.',
 equalTo: "Las contraseñas proveídas no son iguales."

},
terminos:{
 required: 'Términos y condiciones es un campo requerido.'
}
}
});

    $('#formulario_reset').validate({
        errorPlacement: function(error, element){
            if(element.attr('type') == "checkbox"){
                error.insertAfter(element.parent('label').parent('div').parent('div'));
            }
            else{
                error.insertAfter(element.parent('div'));  
            }
        },
        rules:{
            email:{
                required: true,
                validarEmail: true
            }
        },
        messages:{
            email:{
                required: 'Correo electrónico es un campo requerido.'
            }
        }
    });

    $('#formulario_resetP').validate({
        errorPlacement: function(error, element){
            if(element.attr('type') == "checkbox"){
                error.insertAfter(element.parent('label').parent('div').parent('div'));
            }
            else{
                error.insertAfter(element.parent('div'));  
            }
        },
        rules:{
            clave:{
                required: true
            },
            re_clave:{
                required: true,
                equalTo: "#clave"
            }
        },
        messages:{
            clave:{
                required: 'Contraseña es un campo requerido.'
            },
            re_clave:{
                required: 'Repetir contraseña es un campo requerido.',
                equalTo: "Las contraseñas proveídas no son iguales."

            }
        }
    });

    $('#formulario_usuario').validate({
        errorPlacement: function(error, element){
            if(element.attr('type') == "checkbox"){
                error.insertAfter(element.parent('label').parent('div').parent('div'));
            }
            else{
                error.insertAfter(element.parent('div'));  
            }
        },
        rules:{
            nombre:{
                required: true,
                validarNombre: true
            },
            apellido:{
                required: true,
                validarApellido: true
            },
            usuario:{
                required: true,
                validarUsuario: true
            },
            email:{
                required: true,
                validarEmail: true
            },
            privilegio:{
                required: true
            }
        },
        messages:{
            nombre:{
                required: 'Nombre es un campo requerido.'
            },
            apellido:{
                required: 'Apellido es un campo requerido.'
            },
            usuario:{
                required: 'Usuario es un campo requerido.'
            },
            email:{
                required: 'Correo electrónico es un campo requerido.'
            },
            privilegio:{
                required: 'Departamento es un campo requerido.'
            }
        }
    });


    $('#formulario_editar_usuario').validate({
        errorPlacement: function(error, element){
            if(element.attr('type') == "checkbox"){
                error.insertAfter(element.parent('label').parent('div').parent('div'));
            }
            else{
                error.insertAfter(element.parent('div'));  
            }
        },
        rules:{
            editar_nombre:{
                required: true,
                validarNombre: true
            },
            editar_apellido:{
                required: true,
                validarApellido: true
            },
            editar_usuario:{
                required: true,
                validarUsuario: true
            },
            editar_email:{
                required: true,
                validarEmail: true
            },
            editar_privilegio:{
                required: true
            }
        },
        messages:{
            editar_nombre:{
                required: 'Nombre es un campo requerido.'
            },
            editar_apellido:{
                required: 'Apellido es un campo requerido.'
            },
            editar_usuario:{
                required: 'Usuario es un campo requerido.'
            },
            editar_email:{
                required: 'Correo electrónico es un campo requerido.'
            },
            editar_privilegio:{
                required: 'Departamento es un campo requerido.'
            }
        }
    });

    $('#formulario_cliente').validate({
        errorPlacement: function(error, element){
            if(element.attr('type') == "checkbox"){
                error.insertAfter(element.parent('label').parent('div').parent('div'));
            }
            else{
                error.insertAfter(element.parent('div'));  
            }
        },
        rules:{
            nombre_cliente:{
                required: true
            },
            rut_cliente:{
                required: true,
                validarRut: true
            },
            email_cliente:{
                required: true,
                validarEmail: true
            },
            direccion:{
                required: true,
            },
            contacto:{
                validarTelefono: true

            },
            contacto2:{
                validarCelular: true

            }
        },
        messages:{
            nombre_cliente:{
                required: 'Nombre de la empresa es un campo requerido.'
            },
            rut_cliente:{
                required: 'RUT es un campo requerido.'
            },
            email_cliente:{
                 required: 'Correo electrónico es un campo requerido.'
            },
            direccion:{
                 required: 'Dirección de la empresa es un campo requerido.'
            }
        }
    });

        $('#formulario_editar_cliente').validate({
        errorPlacement: function(error, element){
            if(element.attr('type') == "checkbox"){
                error.insertAfter(element.parent('label').parent('div').parent('div'));
            }
            else{
                error.insertAfter(element.parent('div'));  
            }
        },
        rules:{
            editar_nombre_cliente:{
                required: true
            },
            editar_rut_cliente:{
                required: true,
                validarRut: true
            },
            editar_email_cliente:{
                required: true,
                validarEmail: true
            },
            editar_direccion:{
                required: true,
            },
            editar_contacto:{
                validarTelefono: true

            },
            editar_contacto2:{
                validarCelular: true

            }
        },
        messages:{
            editar_nombre_cliente:{
                required: 'Nombre de la empresa es un campo requerido.'
            },
            editar_rut_cliente:{
                required: 'RUT es un campo requerido.'
            },
            editar_email_cliente:{
                 required: 'Correo electrónico es un campo requerido.'
            },
            editar_direccion:{
                 required: 'Dirección de la empresa es un campo requerido.'
            }
        }
    });

        $('#formulario_empresa').validate({
        errorPlacement: function(error, element){
            if(element.attr('type') == "checkbox"){
                error.insertAfter(element.parent('label').parent('div').parent('div'));
            }
            else{
                error.insertAfter(element.parent('div'));  
            }
        },
        rules:{
            editar_nombre_empresa:{
                required: true
            },
            editar_rut_empresa:{
                required: true,
                validarRut: true
            },
            editar_direccion_empresa:{
                required: true,
            },
            editar_contacto_empresa:{
                required : true,
                validarTelefono: true

            }
        },
        messages:{
            editar_nombre_empresa:{
                required: 'Nombre de la empresa es un campo requerido.'
            },
            editar_rut_empresa:{
                required: 'RUT es un campo requerido.'
            },
            editar_direccion_empresa:{
                 required: 'Dirección de la empresa es un campo requerido.'
            },
            editar_contacto_empresa:{
                 required: 'Teléfono de la empresa es un campo requerido.'
            }
        }
    });




     $('#formulario_presupuesto').validate({
        errorPlacement: function(error, element){
            if(element.attr('type') == "checkbox"){
                error.insertAfter(element.parent('label').parent('div').parent('div'));
            }
            else{
                error.insertAfter(element.parent('div'));  
            }
        },
        rules:{
            nombre_obra:{
                required: true
            },
            fecha_presupuesto:{
                required: true,
                validarFecha: true
            },
            fecha_termino:{
                required: true,
                validarFecha: true
            },
            id_cliente:{
                required: true
            }
        },
        messages:{
            nombre_obra:{
                required: 'Nombre de la obra es un campo requerido.'
            },
            fecha_presupuesto:{
                required: 'Fecha Inicio es un campo requerido.'
            },
            fecha_termino:{
                required: 'Fecha de término es un campo requerido.'
            },
            id_cliente:{
                required: 'Cliente es un campo requerido.'
            }
        }
    });
});

$(function () {
$('.categoria').each(function () {
        $(this).rules("add", {
            required: true
        });
    });
});

$(function () {
$('.subcategoria').each(function () {
        $(this).rules("add", {
            required: true
        });
    });
});

$(function () {
$('.insumo').each(function () {
        $(this).rules("add", {
            required: true
        });
    });
$('.listarTipo').each(function () {
        $(this).rules("add", {
            required: true
        });
    });
$('.cantidad').each(function () {
        $(this).rules("add", {
            required: true
        });
    });
});