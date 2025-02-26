<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

$pageTitle = "Gestão de Horários";
include '../includes/head.php';

// Buscar todas as turmas para filtro
$turmas = $conn->query("SELECT * FROM turmas");

// Buscar horários existentes
$horarios = $conn->query("
    SELECT h.*, t.nome AS turma_nome 
    FROM horarios h 
    JOIN turmas t ON h.turma_id = t.id 
    ORDER BY FIELD(h.dia_semana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta'), h.hora_inicio
");

?>


<link rel="stylesheet" href="../public/css/dashboards.css">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard_admin.php">
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

<div class="container mt-5">
    <h2 class="text-center text-primary">📅 Gestão de Horários</h2>

    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAddHorario">
        <i class="bi bi-calendar-plus"></i> Adicionar Horário
    </button>

    <div class="table-responsive">
        <table class="table table-bordered table-striped shadow">
            <thead class="table-dark text-center">
                <tr>
                    <th>Turma</th>
                    <th>Dia da Semana</th>
                    <th>Hora Início</th>
                    <th>Hora Fim</th>
                    <th>Disciplina</th>
                    <th>Professor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php while ($horario = $horarios->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($horario['turma_nome']); ?></td>
                        <td><?= htmlspecialchars($horario['dia_semana']); ?></td>
                        <td><?= htmlspecialchars($horario['hora_inicio']); ?></td>
                        <td><?= htmlspecialchars($horario['hora_fim']); ?></td>
                        <td><?= htmlspecialchars($horario['disciplina']); ?></td>
                        <td><?= htmlspecialchars($horario['professor']); ?></td>
                        <td>
                            <a href="edit_horario.php?id=<?= $horario['id']; ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="delete_horario.php?id=<?= $horario['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este horário?');">
                                <i class="bi bi-trash"></i> Excluir
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Adicionar Horário -->
<div class="modal fade" id="modalAddHorario" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="../controllers/process_horarios.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Horário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Turma</label>
                    <select name="turma_id" class="form-select" required>
                        <option value="">Selecione</option>
                        <?php while ($turma = $turmas->fetch_assoc()) : ?>
                            <option value="<?= $turma['id']; ?>"><?= htmlspecialchars($turma['nome']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Dia da Semana</label>
                    <select name="dia_semana" class="form-select" required>
                        <option value="Segunda">Segunda-feira</option>
                        <option value="Terça">Terça-feira</option>
                        <option value="Quarta">Quarta-feira</option>
                        <option value="Quinta">Quinta-feira</option>
                        <option value="Sexta">Sexta-feira</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hora Início</label>
                    <input type="time" name="hora_inicio" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hora Fim</label>
                    <input type="time" name="hora_fim" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Disciplina</label>
                    <input type="text" name="disciplina" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Professor</label>
                    <input type="text" name="professor" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
