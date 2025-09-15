@extends('auth.layout')

@section('titulo', 'Registro')

@section('contenido')
<div class="logo-auth">
    <i class="fas fa-user-plus"></i>
    <h2>Crear Cuenta</h2>
    <p>Completa tus datos para registrarte</p>
</div>

<form id="formularioRegistro" method="POST">
    @csrf
    <div class="grupo-campo">
        <input type="text" name="nombre" id="nombre" class="campo-entrada" required>
        <label for="text" class="label-flotante">Nombre completo</label>

        <i class="fas fa-user icono-campo"></i>
    </div>

    <div class="grupo-campo">
        <input type="text" name="nombreUsuario" id="nombreUsuario" class="campo-entrada"required>
            <label for="text" class="label-flotante">Nombre de usuario</label>
        <i class="fas fa-at icono-campo"></i>
    </div>

    <div class="grupo-campo">
        <input type="email" name="email" id="email" class="campo-entrada" required>
        <label for="email" class="label-flotante">Correo electrónico</label>
        <i class="fas fa-envelope icono-campo"></i>
    </div>

    <div class="grupo-campo">
        <input type="password" name="password" id="password" class="campo-entrada"  required>
        <label for="password" class="label-flotante">Contraseña</label>
        <button type="button" class="boton-ojo"
            onclick="alternarVisibilidadPassword(document.getElementById('password'), this)">
            <i class="fas fa-eye"></i>
        </button>
    </div>

    <div class="grupo-campo">
        <input type="password" name="confirmarPassword" id="confirmarPassword" class="campo-entrada" required>
            <label for="password" class="label-flotante">Confirmar contraseña</label>
        <button type="button" class="boton-ojo"
            onclick="alternarVisibilidadPassword(document.getElementById('confirmarPassword'), this)">
            <i class="fas fa-eye"></i>
        </button>
    </div>

    <button type="submit" class="boton-primario">
        <span class="texto-boton">Crear Cuenta</span>
        <div class="cargando">
            <div class="spinner"></div>
        </div>
    </button>
</form>

<div class="enlaces-adicionales">
    <a href="{{ route('mostrar.login') }}" class="enlace">¿Ya tienes cuenta? Iniciar sesión</a>
</div>
@endsection

@section('scripts-adicionales')
<script>
    $(document).ready(function() {
    // Configurar validación del formulario
    $("#formularioRegistro").validate({
        rules: {
            nombre: {
                required: true,
                minlength: 2,
                maxlength: 45
            },
            nombreUsuario: {
                required: true,
                minlength: 3,
                maxlength: 45,
                alphanumeric: true
            },
            email: {
                required: true,
                email: true,
                maxlength: 45
            },
            password: {
                required: true,
                minlength: 6
            },
            confirmarPassword: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            nombre: {
                required: "El nombre es obligatorio",
                minlength: "El nombre debe tener al menos 2 caracteres",
                maxlength: "El nombre no puede exceder 45 caracteres"
            },
            nombreUsuario: {
                required: "El nombre de usuario es obligatorio",
                minlength: "Debe tener al menos 3 caracteres",
                maxlength: "No puede exceder 45 caracteres",
                alphanumeric: "Solo se permiten letras, números y guiones bajos"
            },
            email: {
                required: "El correo electrónico es obligatorio",
                email: "Ingrese un correo electrónico válido",
                maxlength: "El correo no puede exceder 45 caracteres"
            },
            password: {
                required: "La contraseña es obligatoria",
                minlength: "La contraseña debe tener al menos 6 caracteres"
            },
            confirmarPassword: {
                required: "Debe confirmar la contraseña",
                equalTo: "Las contraseñas no coinciden"
            }
        },
        errorElement: "span",
        errorClass: "mensaje-error",
        highlight: function(element) {
            $(element).addClass('error');
        },
        unhighlight: function(element) {
            $(element).removeClass('error');
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent());
        },
        submitHandler: function(form) {
            const $boton = $('#formularioRegistro button[type="submit"]');
            mostrarCargando($boton, true);

            $.ajax({
                url: "{{ route('registrar') }}",
                method: 'POST',
                data: $(form).serialize(),
                success: function(respuesta) {
                    mostrarCargando($boton, false);
                    
                    if (respuesta.exito) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Registro Exitoso!',
                            text: respuesta.mensaje,
                            confirmButtonColor: '#4f46e5'
                        }).then(() => {
                            window.location.href = "{{ route('mostrar.login') }}";
                        });
                    } else {
                        if (respuesta.errores) {
                            mostrarErrores(respuesta.errores, $('#formularioRegistro'));
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: respuesta.mensaje,
                                confirmButtonColor: '#4f46e5'
                            });
                        }
                    }
                },
                error: function() {
                    mostrarCargando($boton, false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado. Inténtalo nuevamente.',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            });
        }
    });

    // Método personalizado para validar alphanumeric
    $.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9_]+$/i.test(value);
    });

    // Validación en tiempo real para nombre de usuario
    $('#nombreUsuario').on('input', function() {
        const valor = $(this).val();
        const patron = /^[a-zA-Z0-9_]+$/;
        
        if (valor && !patron.test(valor)) {
            $(this).addClass('error');
            if (!$(this).parent().find('.mensaje-error').length) {
                $(this).parent().append('<span class="mensaje-error">Solo se permiten letras, números y guiones bajos</span>');
            }
        }
    });

    // Validación en tiempo real para confirmar contraseña
    $('#confirmarPassword').on('input', function() {
        const password = $('#password').val();
        const confirmar = $(this).val();
        
        if (confirmar && password !== confirmar) {
            $(this).addClass('error');
            if (!$(this).parent().find('.mensaje-error').length) {
                $(this).parent().append('<span class="mensaje-error">Las contraseñas no coinciden</span>');
            }
        }
    });
});
</script>
@endsection