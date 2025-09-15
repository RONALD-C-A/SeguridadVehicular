<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Escritorio - Sistema de Autenticación</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.27/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --color-primario: #4f46e5;
            --color-secundario: #6366f1;
            --color-exito: #10b981;
            --color-fondo: #f8fafc;
            --color-texto: #1f2937;
            --color-sidebar: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--color-fondo);
            color: var(--color-texto);
            line-height: 1.6;
        }

        /* Header */
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-header {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-primario);
        }

        .info-usuario {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar-usuario {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-primario), var(--color-secundario));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .datos-usuario {
            text-align: right;
        }

        .nombre-usuario {
            font-weight: 600;
            color: var(--color-texto);
            margin-bottom: 2px;
        }

        .rol-usuario {
            font-size: 0.85rem;
            color: #6b7280;
            text-transform: capitalize;
        }

        .boton-cerrar {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .boton-cerrar:hover {
            background: #fecaca;
            transform: translateY(-1px);
        }

        /* Contenido principal */
        .contenido-principal {
            margin-top: 90px;
            padding: 2rem;
            min-height: calc(100vh - 90px);
        }

        .contenedor-bienvenida {
            max-width: 1200px;
            margin: 0 auto;
        }

        .tarjeta-bienvenida {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
        }

        .icono-bienvenida {
            font-size: 4rem;
            color: var(--color-primario);
            margin-bottom: 1.5rem;
        }

        .titulo-bienvenida {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--color-texto);
            margin-bottom: 1rem;
        }

        .subtitulo-bienvenida {
            font-size: 1.2rem;
            color: #6b7280;
            margin-bottom: 2rem;
        }

        /* Tarjetas de información */
        .tarjetas-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .tarjeta-info {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .tarjeta-info:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .encabezado-tarjeta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1.5rem;
        }

        .icono-tarjeta {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .icono-usuario {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .icono-seguridad {
            background: linear-gradient(135deg, #10b981, #047857);
        }

        .icono-actividad {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .titulo-tarjeta {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--color-texto);
        }

        .contenido-tarjeta {
            line-height: 1.7;
        }

        .elemento-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .elemento-info:last-child {
            border-bottom: none;
        }

        .etiqueta-info {
            font-weight: 500;
            color: #6b7280;
        }

        .valor-info {
            font-weight: 600;
            color: var(--color-texto);
        }

        .estado-activo {
            background: #d1fae5;
            color: #065f46;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .rol-badge {
            background: linear-gradient(135deg, var(--color-primario), var(--color-secundario));
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .info-usuario {
                order: -1;
            }

            .contenido-principal {
                padding: 1rem;
                margin-top: 120px;
            }

            .tarjeta-bienvenida {
                padding: 2rem 1.5rem;
            }

            .titulo-bienvenida {
                font-size: 2rem;
            }

            .subtitulo-bienvenida {
                font-size: 1rem;
            }

            .tarjetas-info {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .tarjeta-info {
                padding: 1.5rem;
            }
        }

        /* Animaciones */
        .tarjeta-bienvenida {
            animation: fadeInUp 0.8s ease-out;
        }

        .tarjeta-info {
            animation: fadeInUp 0.8s ease-out;
        }

        .tarjeta-info:nth-child(2) {
            animation-delay: 0.2s;
        }

        .tarjeta-info:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Efectos de hover en elementos */
        .elemento-info:hover {
            background: #f9fafb;
            border-radius: 8px;
            padding-left: 1rem;
            padding-right: 1rem;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo-header">
            <i class="fas fa-shield-alt"></i>
            Sistema de Seguridad Vehicular - Seguros JP S.A.
        </div>
        
        <div class="info-usuario">
            <div class="avatar-usuario">
                {{ substr(Auth::user()->Nombre, 0, 1) }}{{ substr(explode(' ', Auth::user()->Nombre)[1] ?? '', 0, 1) }}
            </div>
            <div class="datos-usuario">
                <div class="nombre-usuario">{{ Auth::user()->Nombre }}</div>
                <div class="rol-usuario">{{ strtolower(Auth::user()->Rol) }}</div>
            </div>
            <button class="boton-cerrar" onclick="cerrarSesion()">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </button>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="contenido-principal">
        <div class="contenedor-bienvenida">
            <!-- Tarjeta de Bienvenida -->
            <div class="tarjeta-bienvenida">
                <div class="icono-bienvenida">
                    <i class="fas fa-user-check"></i>
                </div>
                <h1 class="titulo-bienvenida">¡Bienvenido, {{ Auth::user()->Nombre }}!</h1>
                <p class="subtitulo-bienvenida">
                    Has iniciado sesión exitosamente en el sistema. Aquí tienes un resumen de tu cuenta.
                </p>
            </div>

            <!-- Tarjetas de Información -->
            <div class="tarjetas-info">
                <!-- Información del Usuario -->
                <div class="tarjeta-info">
                    <div class="encabezado-tarjeta">
                        <div class="icono-tarjeta icono-usuario">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="titulo-tarjeta">Información Personal</h3>
                    </div>
                    <div class="contenido-tarjeta">
                        <div class="elemento-info">
                            <span class="etiqueta-info">Nombre completo:</span>
                            <span class="valor-info">{{ Auth::user()->Nombre }}</span>
                        </div>
                        <div class="elemento-info">
                            <span class="etiqueta-info">Nombre de usuario:</span>
                            <span class="valor-info">{{ Auth::user()->NombreUsuario }}</span>
                        </div>
                        <div class="elemento-info">
                            <span class="etiqueta-info">Correo electrónico:</span>
                            <span class="valor-info">{{ Auth::user()->Email }}</span>
                        </div>
                        <div class="elemento-info">
                            <span class="etiqueta-info">Rol del sistema:</span>
                            <span class="rol-badge">{{ strtolower(Auth::user()->Rol) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Información de Seguridad -->
                <div class="tarjeta-info">
                    <div class="encabezado-tarjeta">
                        <div class="icono-tarjeta icono-seguridad">
                            <i class="fas fa-shield-check"></i>
                        </div>
                        <h3 class="titulo-tarjeta">Estado de Seguridad</h3>
                    </div>
                    <div class="contenido-tarjeta">
                        <div class="elemento-info">
                            <span class="etiqueta-info">Estado de la cuenta:</span>
                            <span class="estado-activo">
                                <i class="fas fa-check-circle"></i> Activa
                            </span>
                        </div>
                        <div class="elemento-info">
                            <span class="etiqueta-info">Autenticación:</span>
                            <span class="valor-info">Verificada</span>
                        </div>
                        <div class="elemento-info">
                            <span class="etiqueta-info">Última actualización:</span>
                            <span class="valor-info">
                                {{ Auth::user()->UltimaActualizacion ? Auth::user()->UltimaActualizacion->format('d/m/Y H:i') : 'N/A' }}
                            </span>
                        </div>
                        <div class="elemento-info">
                            <span class="etiqueta-info">Sesión actual:</span>
                            <span class="valor-info">{{ date('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Información de Actividad -->
                <div class="tarjeta-info">
                    <div class="encabezado-tarjeta">
                        <div class="icono-tarjeta icono-actividad">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="titulo-tarjeta">Actividad de la Cuenta</h3>
                    </div>
                    <div class="contenido-tarjeta">
                        <div class="elemento-info">
                            <span class="etiqueta-info">Fecha de registro:</span>
                            <span class="valor-info">
                                {{ Auth::user()->FechaRegistro->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <div class="elemento-info">
                            <span class="etiqueta-info">Tiempo como miembro:</span>
                            <span class="valor-info">
                                {{ Auth::user()->FechaRegistro->diffForHumans() }}
                            </span>
                        </div>
                        <div class="elemento-info">
                            <span class="etiqueta-info">ID de usuario:</span>
                            <span class="valor-info">#{{ Auth::user()->IdUsuario }}</span>
                        </div>
                        <div class="elemento-info">
                            <span class="etiqueta-info">Zona horaria:</span>
                            <span class="valor-info">{{ date_default_timezone_get() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.27/sweetalert2.min.js"></script>

    <script>
        // Configuración global de CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Función para cerrar sesión
        function cerrarSesion() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que quieres salir del sistema?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar indicador de carga
                    Swal.fire({
                        title: 'Cerrando sesión...',
                        text: 'Espera un momento',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Redirigir a cerrar sesión
                    window.location.href = "{{ route('cerrar.sesion') }}";
                }
            });
        }

        // Actualizar tiempo en vivo
        function actualizarTiempo() {
            const ahora = new Date();
            const opciones = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            
            const tiempoFormateado = ahora.toLocaleString('es-ES', opciones);
            
            // Buscar el elemento que muestra la sesión actual y actualizarlo
            const elementoSesion = document.querySelector('.elemento-info:last-child .valor-info');
            if (elementoSesion && elementoSesion.textContent.includes('/')) {
                elementoSesion.textContent = tiempoFormateado.replace(',', '');
            }
        }

        // Actualizar cada segundo
        setInterval(actualizarTiempo, 1000);

        // Mensaje de bienvenida al cargar la página
        $(document).ready(function() {
            // Efecto de aparición gradual para las tarjetas
            $('.tarjeta-info').each(function(index) {
                $(this).css('animation-delay', (index * 0.2) + 's');
            });

            // Tooltip para elementos interactivos
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Efecto de hover mejorado para elementos de información
            $('.elemento-info').hover(
                function() {
                    $(this).css('transform', 'translateX(5px)');
                },
                function() {
                    $(this).css('transform', 'translateX(0)');
                }
            );
        });

        // Protección contra salida accidental
        window.addEventListener('beforeunload', function(e) {
            // Solo mostrar advertencia si hay cambios no guardados
            // En este caso básico, no es necesario pero se puede implementar
            return undefined;
        });

        // Detectar inactividad (opcional)
        let tiempoInactivo = 0;
        const LIMITE_INACTIVIDAD = 30 * 60 * 1000; // 30 minutos

        function reiniciarTiempoInactivo() {
            tiempoInactivo = 0;
        }

        // Eventos que reinician el contador de inactividad
        document.addEventListener('mousemove', reiniciarTiempoInactivo);
        document.addEventListener('keypress', reiniciarTiempoInactivo);
        document.addEventListener('click', reiniciarTiempoInactivo);

        // Comprobar inactividad cada minuto
        setInterval(function() {
            tiempoInactivo += 60000;
            
            if (tiempoInactivo >= LIMITE_INACTIVIDAD) {
                Swal.fire({
                    title: 'Sesión por expirar',
                    text: 'Has estado inactivo durante mucho tiempo. ¿Quieres continuar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Continuar',
                    cancelButtonText: 'Cerrar sesión'
                }).then((result) => {
                    if (result.isConfirmed) {
                        reiniciarTiempoInactivo();
                    } else {
                        window.location.href = "{{ route('cerrar.sesion') }}";
                    }
                });
            }
        }, 60000);
    </script>
</body>
</html>