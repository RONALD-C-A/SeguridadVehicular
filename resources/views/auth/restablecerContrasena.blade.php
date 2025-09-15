@extends('auth.layout')

@section('titulo', 'Restablecer Contraseña')

@section('contenido')
<div class="logo-auth">
    <i class="fas fa-lock-open"></i>
    <h2>Nueva Contraseña</h2>
    <p>Ingresa tu nueva contraseña segura</p>
</div>

<form id="formularioRestablecimiento" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="grupo-campo">
        <input type="password" name="password" id="password" class="campo-entrada" required>
        <label for="password" class="label-flotante">Nueva contraseña</label>
        <button type="button" class="boton-ojo"
            onclick="alternarVisibilidadPassword(document.getElementById('password'), this)">
            <i class="fas fa-eye"></i>
        </button>
    </div>

    <div class="grupo-campo">
        <input type="password" name="confirmarPassword" id="confirmarPassword" class="campo-entrada" required>
        <label for="password" class="label-flotante">Confirmar nueva contraseña</label>
        <button type="button" class="boton-ojo"
            onclick="alternarVisibilidadPassword(document.getElementById('confirmarPassword'), this)">
            <i class="fas fa-eye"></i>
        </button>
    </div>

    <!-- Indicador de fortaleza de contraseña -->
    <div id="indicadorFortaleza" style="margin-bottom: 20px;">
        <div style="font-size: 0.85rem; margin-bottom: 5px; color: #6b7280;">Fortaleza de la contraseña:</div>
        <div style="height: 4px; background: #e5e7eb; border-radius: 2px; overflow: hidden;">
            <div id="barraFortaleza" style="height: 100%; width: 0%; transition: all 0.3s ease; background: #ef4444;">
            </div>
        </div>
        <div id="textoFortaleza" style="font-size: 0.8rem; margin-top: 5px; color: #6b7280;">Muy débil</div>
    </div>

    <button type="submit" class="boton-primario">
        <span class="texto-boton">Cambiar Contraseña</span>
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
    style="background: #f0fdf4; border: 1px solid #22c55e; border-radius: 8px; padding: 15px; margin-top: 20px; font-size: 0.9rem; color: #15803d;">
    <div style="display: flex; align-items: center; margin-bottom: 8px;">
        <i class="fas fa-shield-alt" style="color: #22c55e; margin-right: 8px;"></i>
        <strong>Consejos para una contraseña segura:</strong>
    </div>
    <ul style="margin: 0; padding-left: 20px;">
        <li>Al menos 8 caracteres de longitud</li>
        <li>Combina letras mayúsculas y minúsculas</li>
        <li>Incluye números y símbolos especiales</li>
        <li>Evita información personal obvia</li>
    </ul>
</div>
@endsection

@section('scripts-adicionales')
<script>
    $(document).ready(function() {
    // Configurar validación del formulario
    $("#formularioRestablecimiento").validate({
        rules: {
            password: {
                required: true,
                minlength: 6,
                strongPassword: true
            },
            confirmarPassword: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: "La nueva contraseña es obligatoria",
                minlength: "La contraseña debe tener al menos 6 caracteres",
                strongPassword: "La contraseña debe ser más segura"
            },
            confirmarPassword: {
                required: "Debe confirmar la nueva contraseña",
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
            const $boton = $('#formularioRestablecimiento button[type="submit"]');
            mostrarCargando($boton, true);

            $.ajax({
                url: "{{ route('restablecer.contrasena') }}",
                method: 'POST',
                data: $(form).serialize(),
                success: function(respuesta) {
                    mostrarCargando($boton, false);
                    
                    if (respuesta.exito) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Contraseña Restablecida!',
                            text: respuesta.mensaje,
                            confirmButtonColor: '#4f46e5',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = respuesta.redireccion;
                        });
                    } else {
                        if (respuesta.errores) {
                            mostrarErrores(respuesta.errores, $('#formularioRestablecimiento'));
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

    // Método personalizado para validar contraseña fuerte
    $.validator.addMethod("strongPassword", function(value, element) {
        if (this.optional(element)) return true;
        
        const fortaleza = calcularFortalezaPassword(value);
        return fortaleza >= 3; // Requiere al menos fortaleza "Buena"
    });

    // Función para calcular fortaleza de contraseña
    function calcularFortalezaPassword(password) {
        let puntuacion = 0;
        const criterios = {
            longitud: password.length >= 8,
            minuscula: /[a-z]/.test(password),
            mayuscula: /[A-Z]/.test(password),
            numero: /[0-9]/.test(password),
            especial: /[^A-Za-z0-9]/.test(password)
        };

        // Puntuación basada en criterios cumplidos
        Object.values(criterios).forEach(cumple => {
            if (cumple) puntuacion++;
        });

        return puntuacion;
    }

    // Actualizar indicador de fortaleza en tiempo real
    $('#password').on('input', function() {
        const password = $(this).val();
        const fortaleza = calcularFortalezaPassword(password);
        const barra = $('#barraFortaleza');
        const texto = $('#textoFortaleza');

        let porcentaje, color, descripcion;

        switch (fortaleza) {
            case 0:
            case 1:
                porcentaje = 20;
                color = '#ef4444';
                descripcion = 'Muy débil';
                break;
            case 2:
                porcentaje = 40;
                color = '#f97316';
                descripcion = 'Débil';
                break;
            case 3:
                porcentaje = 60;
                color = '#eab308';
                descripcion = 'Regular';
                break;
            case 4:
                porcentaje = 80;
                color = '#22c55e';
                descripcion = 'Buena';
                break;
            case 5:
                porcentaje = 100;
                color = '#10b981';
                descripción = 'Excelente';
                break;
        }

        barra.css({
            'width': porcentaje + '%',
            'background-color': color
        });
        
        texto.text(descripcion).css('color', color);
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