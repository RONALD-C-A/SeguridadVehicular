@extends('auth.layout')

@section('titulo', 'Recuperar Contraseña')

@section('contenido')
<div class="logo-auth">
    <i class="fas fa-key"></i>
    <h2>Recuperar Contraseña</h2>
    <p>Ingresa tu correo para restablecer tu contraseña</p>
</div>

<form id="formularioRecuperacion" method="POST">
    @csrf
    <div class="grupo-campo">
        <input type="email" name="email" id="email" class="campo-entrada"  required>
                    <label for="email" class="label-flotante">Correo electrónico</label>

        <i class="fas fa-envelope icono-campo"></i>
    </div>

    <button type="submit" class="boton-primario">
        <span class="texto-boton">Enviar Enlace de Recuperación</span>
        <div class="cargando">
            <div class="spinner"></div>
        </div>
    </button>
</form>

<div class="enlaces-adicionales">
    <a href="{{ route('mostrar.login') }}" class="enlace">
        <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
    </a>
</div>
<div
    style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; padding: 15px; margin-top: 20px; font-size: 0.9rem; color: #0c4a6e;">
    <div style="display: flex; align-items: center; margin-bottom: 8px;">
        <i class="fas fa-info-circle" style="color: #0ea5e9; margin-right: 8px;"></i>
        <strong>¿Cómo funciona?</strong>
    </div>
    <ul style="margin: 0; padding-left: 20px;">
        <li>Ingresa tu correo electrónico registrado</li>
        <li>Recibirás un enlace de recuperación</li>
        <li>El enlace expira en 1 hora por seguridad</li>
        <li>Haz clic en el enlace para crear una nueva contraseña</li>
    </ul>
</div>
@endsection

@section('scripts-adicionales')
<script>
    $(document).ready(function() {
    // Configurar validación del formulario
    $("#formularioRecuperacion").validate({
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 45
            }
        },
        messages: {
            email: {
                required: "El correo electrónico es obligatorio",
                email: "Ingrese un correo electrónico válido",
                maxlength: "El correo no puede exceder 45 caracteres"
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
            const $boton = $('#formularioRecuperacion button[type="submit"]');
            mostrarCargando($boton, true);

            $.ajax({
                url: "{{ route('enviar.recuperacion') }}",
                method: 'POST',
                data: $(form).serialize(),
                success: function(respuesta) {
                    mostrarCargando($boton, false);
                    
                    if (respuesta.exito) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Correo Enviado!',
                            text: respuesta.mensaje,
                            confirmButtonColor: '#4f46e5',
                            confirmButtonText: 'Entendido'
                        }).then(() => {
                            // Limpiar el formulario
                            form.reset();
                        });
                    } else {
                        if (respuesta.errores) {
                            mostrarErrores(respuesta.errores, $('#formularioRecuperacion'));
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
                error: function(xhr) {
                    mostrarCargando($boton, false);
                    let mensaje = 'Ocurrió un error inesperado. Inténtalo nuevamente.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: mensaje,
                        confirmButtonColor: '#4f46e5'
                    });
                }
            });
        }
    });


});
</script>
@endsection