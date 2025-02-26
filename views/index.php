<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Bracelet - Bem-vindo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
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
                    <li class="nav-item"><a class="nav-link" href="#home">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="#funcionalidades">Funcionalidades</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Depoimentos</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-light text-primary" href="login.html">Iniciar
                            Sessão</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section com Vídeo -->
    <header id="home" class="vh-100 d-flex align-items-center text-white text-center"
        style="background: url('../public/img/hero-bg.jpg') no-repeat center center/cover;">
        <div class="container">
            <h1 class="display-4 fw-bold">Bem-vindo ao School Bracelet</h1>
            <p class="lead">O projeto que visa substituir o tradicional cartão escolar por uma pulseira RFID</p>
            <a href="#video" class="btn btn-primary btn-lg">Ver Vídeo</a>
        </div>
    </header>

    <!-- Vídeo Explicativo -->
    <section id="video" class="py-5 bg-light text-center">
        <div class="container">
            <h2>Como funciona o School Bracelet?</h2>
            <p>Veja este vídeo explicativo sobre o funcionamento do sistema.</p>
            <video controls width="80%">
                <source src="../public/videos/video-explicativo.mp4" type="video/mp4">
                Seu navegador não suporta a reprodução de vídeos.
            </video>
        </div>
    </section>

    <!-- Depoimentos -->
    <section id="testimonials" class="py-5 text-center bg-dark text-white">
        <div class="container">
            <h2 class="text-light">O que dizem sobre nós?</h2>
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <p class="lead">"O projeto School Bracelet tornou a nossa vida escolar muito mais fácil!"</p>
                        <h5>- Miguel Monteiro, Aluno</h5>
                    </div>
                    <div class="carousel-item">
                        <p class="lead">"Agora consigo acompanhar melhor os alunos, está tudo mais organizado."</p>
                        <h5>- Prof. Simão Cunha</h5>
                    </div>
                    <div class="carousel-item">
                        <p class="lead">"Os pais ficam mais tranquilos pois conseguem saber quando os seus filhos entram e saem da
                            escola."</p>
                        <h5>- Maria Lopes, Encarregada de Educação</h5>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </section>

    <!-- Funcionalidades -->
    <section id="funcionalidades" class="container py-5">
        <h2 class="text-center">Funcionalidades do School Bracelet</h2>
        <p class="text-center">O nosso sistema integra uma pulseira com a tecnologia RFID para facilitar o funcionamento do sistema escolar.</p>
        <div class="row text-center">
            <div class="col-md-4">
                <i class="bi bi-person-check" style="font-size: 3rem;"></i>
                <h4>Controle de Presença</h4>
                <p>Registo de entradas e saídas de alunos via pulseiras RFID em tempo real.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-wallet2" style="font-size: 3rem;"></i>
                <h4>Gestão de Pagamentos</h4>
                <p>Permite o carregamento de saldo e compras no bar e na papelaria da escola.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-bar-chart" style="font-size: 3rem;"></i>
                <h4>Relatórios e Estatísticas</h4>
                <p>Monitorização do desempenho escolar e gestão financeira.</p>
            </div>
        </div>
    </section>

<!-- Rodapé -->
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row justify-content-center text-center">
            <!-- Sobre o School Bracelet -->
            <div class="col-md-4">
                <h5 class="fw-bold text-light">Sobre o School Bracelet</h5>
                <p>O School Bracelet é um sistema inovador que moderniza a gestão escolar através de uma pulseira com a tecnologia RFID, facilitando a entrada e saída de alunos, gestão de pagamentos e muito mais.</p>
            </div>

            <!-- Links Rápidos -->
            <div class="col-md-4">
                <h5 class="fw-bold text-light">Links Rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="#home" class="text-light text-decoration-none">Início</a></li>
                    <li><a href="#funcionalidades" class="text-light text-decoration-none">Funcionalidades</a></li>
                </ul>
            </div>

            <!-- Morada -->
            <div class="col-md-4">
                <h5 class="fw-bold text-light">Contactos</h5>
                <p><i class="bi bi-house"></i> Escola Profissional Bento de Jesus Caraça</p>
                <p><i class="bi bi-geo-alt"></i> Rua do Bonjardim - Porto</p>
                <p><i class="bi bi-envelope"></i> <a href="mailto:suporte@schoolbracelet.pt" class="text-light text-decoration-none">suporte@schoolbracelet.pt</a></p>
            </div>
        </div>

        <!-- Créditos -->
        <div class="text-center mt-3">
            <p class="mb-0">&copy; 2025 School Bracelet. Todos os direitos reservados.</p>
            <p class="mb-0">Site desenvolvido por: <strong>Diogo Silva  e Gonçalo Carreira</strong></p>
        </div>
    </div>
</footer>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>