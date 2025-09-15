
// Esperar a que todas las librerías estén cargadas
$(document).ready(function () {
    // Configuración global de CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Verificar que jQuery Validate esté disponible
    if (typeof $.fn.validate === 'undefined') {
        console.error('jQuery Validate no se cargó correctamente');
    } else {
        console.log('jQuery Validate cargado correctamente');
    }
    // Manejar el estado de los labels flotantes
    function actualizarLabelFlotante(input) {
        const $input = $(input);
        if ($input.val().trim() !== '') {
            $input.addClass('tiene-contenido');
        } else {
            $input.removeClass('tiene-contenido');
        }
    }

    // Aplicar estado inicial de labels
    $('.campo-entrada').each(function () {
        actualizarLabelFlotante(this);
    });

    // Eventos para manejar los labels flotantes
    $('.campo-entrada').on('input', function () {
        actualizarLabelFlotante(this);
    });

    $('.campo-entrada').on('focus', function () {
        $(this).removeClass('error');
        $(this).parent().find('.mensaje-error').remove();
    });
});

// Función para mostrar/ocultar contraseñas
function alternarVisibilidadPassword(input, boton) {
    if (input.type === 'password') {
        input.type = 'text';
        boton.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        input.type = 'password';
        boton.innerHTML = '<i class="fas fa-eye"></i>';
    }
}

// Función para mostrar estados de carga en botones
function mostrarCargando(boton, mostrar = true) {
    const textoOriginal = boton.find('.texto-boton');
    const cargando = boton.find('.cargando');

    if (mostrar) {
        textoOriginal.hide();
        cargando.show();
        boton.prop('disabled', true);
    } else {
        textoOriginal.show();
        cargando.hide();
        boton.prop('disabled', false);
    }
}

// Función para mostrar errores de validación (mejorada)
function mostrarErrores(errores, formulario) {
    // Limpiar errores anteriores
    formulario.find('.campo-entrada').removeClass('error');
    formulario.find('.mensaje-error').remove();

    // Mostrar nuevos errores
    $.each(errores, function (campo, mensajes) {
        const input = formulario.find(`[name="${campo}"]`);
        input.addClass('error');

        if (Array.isArray(mensajes)) {
            input.parent().append(`<span class="mensaje-error">${mensajes[0]}</span>`);
        } else {
            input.parent().append(`<span class="mensaje-error">${mensajes}</span>`);
        }
    });
}

// Remover errores al escribir
$(document).on('input focus', '.campo-entrada', function () {
    $(this).removeClass('error');
    $(this).parent().find('.mensaje-error').remove();
});

// Manejar alertas de sesión
@if (session('exito'))
    $(document).ready(function () {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session("exito") }}',
            confirmButtonColor: '#4f46e5'
        });
    });
@endif

@if (session('error'))
    $(document).ready(function () {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session("error") }}',
            confirmButtonColor: '#4f46e5'
        });
    });
@endif
