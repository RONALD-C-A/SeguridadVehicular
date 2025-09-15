@extends('auth.layout')

@section('titulo', 'Iniciar Sesión')

@section('contenido')
<div class="logo-auth">
    <i class="fas fa-user-circle"></i>
    <h2>Iniciar Sesión</h2>
    <p>Ingresa tus credenciales para acceder</p>
</div>

<form id="formularioLogin" method="POST">
    @csrf
    <div class="grupo-campo">
        <input type="text" name="usuario" id="usuario" class="campo-entrada" required>
        <label for="usuario" class="label-flotante">Correo electrónico o nombre de usuario</label>


        <i class="fas fa-user icono-campo"></i>
    </div>

    <div class="grupo-campo">
        <input type="password" name="password" id="password" class="campo-entrada" required>
        <label for="password" class="label-flotante">Contraseña</label>

        <button type="button" class="boton-ojo"
            onclick="alternarVisibilidadPassword(document.getElementById('password'), this)">
            <i class="fas fa-eye"></i>
        </button>
    </div>

    <button type="submit" class="boton-primario">
        <span class="texto-boton">Iniciar Sesión</span>
        <div class="cargando">
            <div class="spinner"></div>
        </div>
    </button>
</form>

<div class="enlaces-adicionales">
    <a href="{{ route('mostrar.registro') }}" class="enlace">¿No tienes cuenta? Regístrate</a>
    <a href="{{ route('mostrar.recuperacion') }}" class="enlace">¿Olvidaste tu contraseña?</a>
</div>
@endsection

@section('scripts-adicionales')
<script>
    $(document).ready(function() {
    // Configurar validación del formulario
    $("#formularioLogin").validate({
        rules: {
            usuario: {
                required: true,
                minlength: 3
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            usuario: {
                required: "El usuario o correo es obligatorio",
                minlength: "Debe tener al menos 3 caracteres"
            },
            password: {
                required: "La contraseña es obligatoria",
                minlength: "Debe tener al menos 6 caracteres"
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
            const $boton = $('#formularioLogin button[type="submit"]');
            mostrarCargando($boton, true);

            $.ajax({
                url: "{{ route('iniciar.sesion') }}",
                method: 'POST',
                data: $(form).serialize(),
                success: function(respuesta) {
                    mostrarCargando($boton, false);
                    
                    if (respuesta.exito) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Bienvenido!',
                            text: respuesta.mensaje,
                            confirmButtonColor: '#4f46e5',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = respuesta.redireccion;
                        });
                    } else {
                        if (respuesta.errores) {
                            mostrarErrores(respuesta.errores, $('#formularioLogin'));
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
});
</script>
@endsection