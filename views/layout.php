<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="build/js/app.js"></script>
    <link rel="icon" href="data:,">
    <link rel="shortcut icon" href="<?= asset('images/CCEG.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>ESCUELA REGIONAL DE COMUNICACIONES</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/car_escuela/">
                <img src="<?= asset('./images/CCEG.png') ?>" width="35" alt="logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Registros Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-journal-plus"></i> Registros
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/car_escuela/instructor">
                                    <i class="bi bi-person-badge"></i> Instructores
                                </a></li>
                            <li><a class="dropdown-item" href="/car_escuela/alumno">
                                    <i class="bi bi-mortarboard"></i> Alumnos
                                </a></li>
                            <li><a class="dropdown-item" href="/car_escuela/sancion">
                                    <i class="bi bi-exclamation-octagon"></i> Arresto
                                </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-people"></i> Alumnos
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li>
                                <a class="dropdown-item" href="/car_escuela/alugrado">
                                    <i class="bi bi-list-ol"></i> Por Grados
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/car_escuela/conducta">
                                    <i class="bi bi-list-ol"></i> Por conducta General
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-clock-history"></i>Arresto
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li>
                                <a class="dropdown-item" href="/car_escuela/arresto">
                                    <i class="bi bi-list-stars"></i> Nomina
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-award"></i> Estadisticas
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li>
                                <a class="dropdown-item" href="/car_escuela/demerito">
                                    <i class="bi bi-list-stars"></i> Demeritos por grado
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/car_escuela/estadisticas">
                                    <i class="bi bi-graph-up"></i> Generales
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Faltas Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-shield-exclamation"></i> Tipos de Falta
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/car_escuela/falta?tipo=LEVE">
                                    <i class="bi bi-exclamation"></i> Leves
                                </a></li>
                            <li><a class="dropdown-item" href="/car_escuela/falta?tipo=GRAVE">
                                    <i class="bi bi-exclamation-circle"></i> Graves
                                </a></li>
                            <li><a class="dropdown-item" href="/car_escuela/falta?tipo=GRAVISIMAS">
                                    <i class="bi bi-exclamation-triangle"></i> Gravísimas
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="/car_escuela/falta">
                                    <i class="bi bi-list-check"></i> Todas las Faltas
                                </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-info-circle"></i> Ayuda
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="#">
                                    <i class="bi bi-book"></i> Manual de Usuario
                                </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <div class="container-fluid pt-5 mb-4" style="min-height: 85vh">
        <?php echo $contenido; ?>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size: xx-small; font-weight: bold;">
                    Brigada de Comunicaciones, <?= date('Y') ?> &copy;
                </p>
            </div>
        </div>
    </div>
</body>

</html>