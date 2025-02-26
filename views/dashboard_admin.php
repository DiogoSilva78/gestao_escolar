<?php include '../includes/head.php'; ?>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador - School Bracelet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/css/dashboards.css">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../public/img/logo-epbjc.png" alt="Logo" width="35" height="35" class="me-2">
                <span>School Bracelet</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Painel</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_users.php">Usuários</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_turmas.php">Turmas</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white"
                            href="../controllers/logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Painel de Administração -->
    <div class="container mt-5">
        <div class="text-center text-primary">
            <h1 class="fw-bold titulo-sombreado">Painel de Administrador</h1>
        </div>

        <div class="row mt-4">
            <!-- Card Gestão de Usuários -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center admin-card">
                    <div class="card-body">
                        <i class="bi bi-people text-primary" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Gerir Utilizadores</h5>
                        <a href="manage_users.php" class="btn btn-outline-primary w-100">Aceder</a>
                    </div>
                </div>
            </div>

            <!-- Card Gestão de Turmas -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center admin-card">
                    <div class="card-body">
                        <i class="bi bi-mortarboard text-black" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Gerir Turmas</h5>
                        <a href="manage_turmas.php" class="btn btn-outline-dark w-100">Aceder</a>
                    </div>
                </div>
            </div>

            <!-- Card Gestão da Papelaria -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center admin-card">
                    <div class="card-body">
                        <i class="bi bi-shop text-success" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Gerir Papelaria</h5>
                        <a href="manage_papelaria.php" class="btn btn-outline-success w-100">Aceder</a>
                    </div>
                </div>
            </div>

            <!-- Card Gestão do Bar -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center admin-card">
                    <div class="card-body">
                        <i class="bi bi-cup-straw text-danger" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Gerir Bar</h5>
                        <a href="manage_bar.php" class="btn btn-outline-danger w-100">Aceder</a>
                    </div>
                </div>
            </div>

            <!-- Card Estatísticas -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center admin-card">
                    <div class="card-body">
                        <i class="bi bi-bar-chart text-info" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Estatísticas</h5>
                        <a href="estatisticas_admin.php" class="btn btn-outline-info w-100">Aceder</a>
                    </div>
                </div>
            </div>

            <!-- Card Avisos -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center admin-card">
                    <div class="card-body">
                        <i class="bi bi-megaphone text-warning" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Avisos</h5>
                        <a href="manage_avisos.php" class="btn btn-outline-warning w-100">Aceder</a>
                    </div>
                </div>
            </div>

            <!-- Card Portaria -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center admin-card">
                    <div class="card-body">
                        <i class="bi bi-door-open text-secondary" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Portaria</h5>
                        <a href="portaria.php" class="btn btn-outline-secondary w-100">Aceder</a>
                    </div>
                </div>
            </div>

            <!-- Card Horários -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center admin-card">
                    <div class="card-body">
                        <i class="bi bi-calendar-week text-secondary" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Horários</h5>
                        <a href="manage_horarios.php" class="btn btn-outline-secondary w-100">Aceder</a>
                    </div>
                </div>
            </div>

            <!-- Card Notas -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center admin-card">
                    <div class="card-body">
                        <i class="bi bi-book text-primary" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-2">Notas</h5>
                        <a href="insert_grades.php" class="btn btn-outline-primary w-100">Aceder</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
