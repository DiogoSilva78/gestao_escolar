-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26-Fev-2025 às 17:26
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gestao_escolar`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `alunos`
--

CREATE TABLE `alunos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `turma_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `alunos`
--

INSERT INTO `alunos` (`id`, `user_id`, `turma_id`) VALUES
(1, 4, 2),
(2, 2, 2),
(3, 6, 2),
(4, 8, 2),
(5, 9, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `avisos`
--

CREATE TABLE `avisos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `data_publicacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `avisos`
--

INSERT INTO `avisos` (`id`, `titulo`, `mensagem`, `data_publicacao`) VALUES
(2, 'Greve dos Funcionários', 'No dia 19/02/2025, a escola estará encerrada devido a: Greve dos Funcionários.', '2025-02-18 09:39:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `transacao_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `area_curso` varchar(100) NOT NULL,
  `ano` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cursos`
--

INSERT INTO `cursos` (`id`, `area_curso`, `ano`) VALUES
(1, 'Informática', '10º'),
(2, 'Informática', '11º'),
(3, 'Informática', '12º'),
(4, 'Gestão', '10º'),
(5, 'Gestão', '11º'),
(6, 'Gestão', '12º'),
(7, 'Marketing', '10º'),
(8, 'Marketing', '11º'),
(9, 'Marketing', '12º'),
(10, 'Relações Públicas', '10º'),
(11, 'Relações Públicas', '11º'),
(12, 'Relações Públicas', '12º');

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplinas`
--

CREATE TABLE `disciplinas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `disciplinas`
--

INSERT INTO `disciplinas` (`id`, `nome`) VALUES
(1, 'Matemática'),
(2, 'Português'),
(3, 'Física'),
(4, 'Química'),
(5, 'História'),
(6, 'Geografia'),
(7, 'Inglês');

-- --------------------------------------------------------

--
-- Estrutura da tabela `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  `dia_semana` enum('Segunda','Terça','Quarta','Quinta','Sexta') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `disciplina` varchar(255) NOT NULL,
  `professor` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `horarios`
--

INSERT INTO `horarios` (`id`, `turma_id`, `dia_semana`, `hora_inicio`, `hora_fim`, `disciplina`, `professor`) VALUES
(1, 2, 'Segunda', '08:20:00', '09:20:00', 'Portuguêss', 'Marta Silva');

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `professor_id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `lida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `mensagens`
--

INSERT INTO `mensagens` (`id`, `professor_id`, `aluno_id`, `mensagem`, `data_envio`, `lida`) VALUES
(1, 11, 2, 'Olá Mariana. Como estão a correr as tuas férias ? \r\n\r\nJá fizeste os trabalhos que enviei ?\r\n\r\nBoas férias ;)', '2025-02-25 00:09:21', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulos`
--

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `modulos`
--

INSERT INTO `modulos` (`id`, `nome`) VALUES
(1, 'Módulo 1 - Introdução'),
(2, 'Módulo 2 - Fundamentos'),
(3, 'Módulo 3 - Desenvolvimento'),
(4, 'Módulo 4 - Projeto Final');

-- --------------------------------------------------------

--
-- Estrutura da tabela `notas`
--

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL,
  `nota` decimal(5,2) NOT NULL,
  `data_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `notas`
--

INSERT INTO `notas` (`id`, `aluno_id`, `disciplina_id`, `modulo_id`, `nota`, `data_registro`) VALUES
(1, 4, 2, 1, 15.00, '2025-02-18 09:05:09'),
(3, 2, 1, 1, 14.00, '2025-02-19 01:01:37'),
(4, 9, 2, 1, 12.00, '2025-02-24 23:09:41'),
(5, 6, 2, 1, 2.00, '2025-02-26 14:43:50'),
(6, 8, 5, 4, 10.00, '2025-02-26 14:44:17');

-- --------------------------------------------------------

--
-- Estrutura da tabela `portaria`
--

CREATE TABLE `portaria` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `portaria`
--

INSERT INTO `portaria` (`id`, `user_id`, `tipo`, `data_hora`) VALUES
(1, 2, 'entrada', '2025-02-17 22:13:34'),
(2, 2, 'saida', '2025-02-17 22:13:38'),
(3, 2, 'entrada', '2025-02-17 22:16:54'),
(4, 2, 'saida', '2025-02-17 22:17:03'),
(5, 2, 'entrada', '2025-02-17 22:23:51'),
(6, 2, 'saida', '2025-02-17 22:23:55'),
(7, 2, 'entrada', '2025-02-17 22:27:04'),
(8, 2, 'saida', '2025-02-17 22:27:07'),
(9, 2, 'entrada', '2025-02-18 00:03:40'),
(10, 2, 'saida', '2025-02-18 00:03:43'),
(11, 2, 'entrada', '2025-02-18 00:03:46'),
(12, 2, 'saida', '2025-02-18 00:03:47'),
(13, 2, 'entrada', '2025-02-24 23:57:05'),
(14, 2, 'saida', '2025-02-24 23:57:14');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `categoria` enum('papelaria','bar') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `imagem`, `preco`, `stock`, `categoria`) VALUES
(1, 'Kinder', 'kinder.jpg', 2.00, 10, 'bar'),
(2, 'Caneta', 'caneta.png', 1.00, 0, 'papelaria'),
(3, 'Kit-Kat ', 'kit-kat.jpg', 1.00, 0, 'bar'),
(4, 'Lápis', 'lapis.jpeg', 1.00, 1, 'papelaria');

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacoes`
--

CREATE TABLE `transacoes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tipo` enum('compra','carregamento') NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `transacoes`
--

INSERT INTO `transacoes` (`id`, `user_id`, `tipo`, `valor`, `data`) VALUES
(1, 2, 'carregamento', 12.00, '2025-02-16 17:02:45'),
(2, 2, 'carregamento', 12.00, '2025-02-16 17:02:47'),
(3, 2, 'carregamento', 2.00, '2025-02-16 17:21:23'),
(4, 2, 'compra', 2.00, '2025-02-16 23:15:33'),
(5, 2, 'compra', 2.00, '2025-02-16 23:19:07'),
(6, 4, 'compra', 1.00, '2025-02-19 15:43:41'),
(7, 4, 'compra', 2.00, '2025-02-19 15:45:11'),
(8, 4, 'carregamento', 1.00, '2025-02-19 15:50:21'),
(9, 2, 'carregamento', 10.00, '2025-02-24 23:54:26');

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacoes_itens`
--

CREATE TABLE `transacoes_itens` (
  `id` int(11) NOT NULL,
  `transacao_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `transacoes_itens`
--

INSERT INTO `transacoes_itens` (`id`, `transacao_id`, `produto_id`, `quantidade`) VALUES
(1, 6, 2, 1),
(2, 7, 2, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `turmas`
--

CREATE TABLE `turmas` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `ano` varchar(10) NOT NULL,
  `area_curso` varchar(100) NOT NULL,
  `abreviatura_curso` varchar(20) NOT NULL,
  `letra_turma` varchar(5) NOT NULL,
  `professor_id` int(11) DEFAULT NULL,
  `modulos` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `abreviatura` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `turmas`
--

INSERT INTO `turmas` (`id`, `nome`, `ano`, `area_curso`, `abreviatura_curso`, `letra_turma`, `professor_id`, `modulos`, `observacoes`, `abreviatura`) VALUES
(2, 'Técnico De Gestão e Programação de Sistemas Informáticos', '12º', 'Informática', 'TGPSI', 'B', 11, NULL, NULL, 'TGPSI'),
(3, 'Técnico De Gestão e Programação de Sistemas Informáticos', '11', 'Informática', 'TGPSI', 'A', 11, NULL, NULL, NULL),
(4, 'Marketing', '10', 'Gestão', '', 'A', 12, NULL, NULL, 'MKT');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo` enum('admin','funcionario','professor','aluno') NOT NULL,
  `rfid_tag` varchar(50) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cartao_cidadao` varchar(20) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `contato_emergencia` varchar(15) DEFAULT NULL,
  `encarregado_nome` varchar(100) DEFAULT NULL,
  `encarregado_contato` varchar(15) DEFAULT NULL,
  `encarregado_email` varchar(100) DEFAULT NULL,
  `morada` text DEFAULT NULL,
  `localidade` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `nome`, `foto`, `email`, `password`, `tipo`, `rfid_tag`, `saldo`, `created_at`, `cartao_cidadao`, `telefone`, `contato_emergencia`, `encarregado_nome`, `encarregado_contato`, `encarregado_email`, `morada`, `localidade`) VALUES
(1, 'Diogo', NULL, 'diogosousasilva07@gmail.com', '$2y$10$kOsaayFauh2n553aO0SSV.BO5I.7I4OXeApUvbDxnt1UbcHlM0C.e', 'admin', '8d8824d9', 0.00, '2025-02-14 00:45:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Mariana', 'public/img/67b38ab1e66c0.jpg', 'marianafilipaalmeida1122@gmail.com', '', 'aluno', '101adda4', 32.00, '2025-02-15 16:39:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vila Nova de Gaia'),
(4, 'Miguel', NULL, 'miguelangeloferreira@gmail.com', '$2y$10$kOsaayFauh2n553aO0SSV.BO5I.7I4OXeApUvbDxnt1UbcHlM0C.e', 'aluno', NULL, 8.00, '2025-02-15 17:12:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Miguel', NULL, 'miguelangelo@gmail.com', '$2y$10$06S6FerPcefPu7N6oJHAMuwZuWitb.B5Os9rvp02KxPav7tVVjE5C', 'funcionario', NULL, 0.00, '2025-02-16 16:34:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Ricardo Martins', NULL, '07ricardomartins@gmail.com', '$2y$10$BRoLcaQ2ffhRYsLqYYWaI.mrqItOTJcqsoc3OHRC47nFICOhcDexW', 'aluno', NULL, 0.00, '2025-02-17 14:42:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Rafael', 'public/img/67b38ab1e66c0.jpg', 'rafaellobao@gmail.com', '$2y$10$CdLBlYfVISX4C4mr.LvLpOc/Q.Qdm.OTLQqJxP8SFbbmZ84lj4FfO', 'aluno', NULL, 0.00, '2025-02-17 19:14:57', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Diogo Bermudes', '1740580060_gomas.jpg', 'diogobermudes@gmail.com', '$2y$10$8AJpNqgHkDi8bD9XYuwEZu4uVYwviCzYsoODSivlJAXcRInVqVdAu', 'aluno', NULL, 0.00, '2025-02-17 23:06:45', '', '916814143', '935871005', 'Natália Bermudes Teixeira ', '935871005', 'natalia.teixeira.silva@gmail.com', 'Rua Monte do Viso, S/N ( ao lado do 528 ) \r\n4475-140', 'Maia'),
(11, 'Carla Malafaya', NULL, 'carlamalafaya@gmail.com', '$2y$10$6ed6vTLUSOa5JM.pLuxSKu3YFv99t1oXGIovMzhbtrLvM1Bl2KyQC', 'professor', NULL, 0.00, '2025-02-18 10:05:10', '', '', '', '', '', '', '', 'Amarante'),
(12, 'Simão Cunha', '1740580060_gomas.jpg', 'simaocunha@gmail.com', '$2y$10$6ed6vTLUSOa5JM.pLuxSKu3YFv99t1oXGIovMzhbtrLvM1Bl2KyQC', 'professor', '', 0.00, '2025-02-26 14:27:40', '', '', '', '', '', '', '', 'Amarante');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `turma_id` (`turma_id`);

--
-- Índices para tabela `avisos`
--
ALTER TABLE `avisos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transacao_id` (`transacao_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turma_id` (`turma_id`);

--
-- Índices para tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `professor_id` (`professor_id`),
  ADD KEY `aluno_id` (`aluno_id`);

--
-- Índices para tabela `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`),
  ADD KEY `disciplina_id` (`disciplina_id`),
  ADD KEY `modulo_id` (`modulo_id`);

--
-- Índices para tabela `portaria`
--
ALTER TABLE `portaria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices para tabela `transacoes_itens`
--
ALTER TABLE `transacoes_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transacao_id` (`transacao_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `rfid_tag` (`rfid_tag`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alunos`
--
ALTER TABLE `alunos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `avisos`
--
ALTER TABLE `avisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `portaria`
--
ALTER TABLE `portaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `transacoes_itens`
--
ALTER TABLE `transacoes_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `alunos`
--
ALTER TABLE `alunos`
  ADD CONSTRAINT `alunos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alunos_ibfk_2` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`transacao_id`) REFERENCES `transacoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`aluno_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_ibfk_3` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `portaria`
--
ALTER TABLE `portaria`
  ADD CONSTRAINT `portaria_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD CONSTRAINT `transacoes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `transacoes_itens`
--
ALTER TABLE `transacoes_itens`
  ADD CONSTRAINT `transacoes_itens_ibfk_1` FOREIGN KEY (`transacao_id`) REFERENCES `transacoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transacoes_itens_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
