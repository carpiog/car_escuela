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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/control_arresto/">
                <img src="<?= asset('./images/CCEG.png') ?>" width="35px" alt="cit">
            </a>
            <div class="collapse navbar-collapse" id="navbarToggler">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-2"></i>REGISTROS
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li>
                                <a class="dropdown-item" href="/control_arresto/instructor">
                                    <i class="bi bi-person-plus me-2"></i>Registrar Instructores
                                </a>
                                <a class="dropdown-item" href="/control_arresto/alumno">
                                    <i class="bi bi-person-plus me-2"></i>Registrar Alumnos
                                </a>
                                <a class="dropdown-item" href="/control_arresto/sancion">
                                    <i class="bi bi-person-plus me-2"></i>Registrar Arresto
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-exclamation-triangle me-2"></i>TIPOS DE FALTA
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li>
                                <a class="dropdown-item" href="/control_arresto/falta?tipo=LEVE">
                                    <i class="bi bi-info-circle me-2"></i>Faltas Leves
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/control_arresto/falta?tipo=GRAVE">
                                    <i class="bi bi-exclamation-circle me-2"></i>Faltas Graves
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/control_arresto/falta?tipo=GRAVISIMAS">
                                    <i class="bi bi-exclamation-diamond me-2"></i>Faltas Gravísimas
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="/control_arresto/falta">
                                    <i class="bi bi-card-list me-2"></i>Todas las Faltas
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Nuevo Dropdown para "DEMERITOS" -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-trophy me-2"></i>DEMERITOS
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li>
                                <a class="dropdown-item" href="/control_arresto/demerito">
                                    <i class="bi bi-list me-2"></i>Ver Demeritos
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/control_arresto/estadisticas">
                                    <i class="bi bi-bar-chart me-2"></i>Estadísticas
                                </a>
                            </li>
                        </ul>
                    </div>
                </ul>
                <div class="col-lg-1 d-grid mb-lg-0 mb-2">
                    <a href="/menu/" class="btn btn-danger">
                        <i class="bi bi-arrow-bar-left"></i> MENÚ
                    </a>
                </div>
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