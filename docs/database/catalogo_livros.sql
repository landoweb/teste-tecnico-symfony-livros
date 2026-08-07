-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 07-Ago-2026 às 18:16
-- Versão do servidor: 8.0.26
-- versão do PHP: 7.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `livraria`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `assunto`
--

CREATE TABLE `assunto` (
  `id` int NOT NULL,
  `descricao` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `assunto`
--

INSERT INTO `assunto` (`id`, `descricao`) VALUES
(11, 'Aventura'),
(4, 'Cinema e Cultura Pop'),
(8, 'Desenvolvimento Web'),
(2, 'Fantasia'),
(1, 'Ficção Científica'),
(7, 'Frameworks PHP'),
(6, 'PHP'),
(9, 'Programação'),
(3, 'Star Wars'),
(5, 'Symfony');

-- --------------------------------------------------------

--
-- Estrutura da tabela `autor`
--

CREATE TABLE `autor` (
  `id` int NOT NULL,
  `nome` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `autor`
--

INSERT INTO `autor` (`id`, `nome`) VALUES
(1, 'DONALD F. GLUT'),
(12, 'E. K. Johnston'),
(2, 'GEORGE LUCAS'),
(3, 'Jaime da Costa Pereira Neto'),
(4, 'Machado de Assis');

-- --------------------------------------------------------

--
-- Estrutura da tabela `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20260807063528', '2026-08-07 06:37:05', 383),
('DoctrineMigrations\\Version20260807105358', '2026-08-07 10:58:51', 57),
('DoctrineMigrations\\Version20260807142714', '2026-08-07 14:28:15', 27),
('DoctrineMigrations\\Version20260807162148', '2026-08-07 16:23:04', 23);

-- --------------------------------------------------------

--
-- Estrutura da tabela `livro`
--

CREATE TABLE `livro` (
  `id` int NOT NULL,
  `titulo` varchar(40) NOT NULL,
  `editora` varchar(40) NOT NULL,
  `edicao` int NOT NULL,
  `ano_publicacao` varchar(4) NOT NULL,
  `valor` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `livro`
--

INSERT INTO `livro` (`id`, `titulo`, `editora`, `edicao`, `ano_publicacao`, `valor`) VALUES
(1, 'STAR WARS: DARK EDITION', 'Darkside Books - Cinebookclub', 1, '2019', '75.04'),
(2, 'Symfony - Escolhendo Um Framework Php', 'Clube de Autores', 1, '2022', '44.61'),
(3, 'Box Star Wars: Trilogia Padmé Amidala', 'Universo Geek', 1, '2025', '41.67');

-- --------------------------------------------------------

--
-- Estrutura da tabela `livro_assunto`
--

CREATE TABLE `livro_assunto` (
  `livro_id` int NOT NULL,
  `assunto_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `livro_assunto`
--

INSERT INTO `livro_assunto` (`livro_id`, `assunto_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(3, 1),
(3, 2),
(3, 4),
(3, 11);

-- --------------------------------------------------------

--
-- Estrutura da tabela `livro_autor`
--

CREATE TABLE `livro_autor` (
  `livro_id` int NOT NULL,
  `autor_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `livro_autor`
--

INSERT INTO `livro_autor` (`livro_id`, `autor_id`) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 12);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_relatorio_livros`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vw_relatorio_livros` (
`autor_id` int
,`autor_nome` varchar(40)
,`livro_id` int
,`livro_titulo` varchar(40)
,`editora` varchar(40)
,`edicao` int
,`ano_publicacao` varchar(4)
,`valor` decimal(10,2)
,`assuntos` text
);

-- --------------------------------------------------------

--
-- Estrutura para vista `vw_relatorio_livros`
--
DROP TABLE IF EXISTS `vw_relatorio_livros`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_relatorio_livros`  AS SELECT `a`.`id` AS `autor_id`, `a`.`nome` AS `autor_nome`, `l`.`id` AS `livro_id`, `l`.`titulo` AS `livro_titulo`, `l`.`editora` AS `editora`, `l`.`edicao` AS `edicao`, `l`.`ano_publicacao` AS `ano_publicacao`, `l`.`valor` AS `valor`, group_concat(distinct `ass`.`descricao` order by `ass`.`descricao` ASC separator ', ') AS `assuntos` FROM ((((`autor` `a` join `livro_autor` `la` on((`la`.`autor_id` = `a`.`id`))) join `livro` `l` on((`l`.`id` = `la`.`livro_id`))) left join `livro_assunto` `las` on((`las`.`livro_id` = `l`.`id`))) left join `assunto` `ass` on((`ass`.`id` = `las`.`assunto_id`))) GROUP BY `a`.`id`, `a`.`nome`, `l`.`id`, `l`.`titulo`, `l`.`editora`, `l`.`edicao`, `l`.`ano_publicacao`, `l`.`valor` ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `assunto`
--
ALTER TABLE `assunto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_assunto_descricao` (`descricao`);

--
-- Índices para tabela `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_autor_nome` (`nome`);

--
-- Índices para tabela `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Índices para tabela `livro`
--
ALTER TABLE `livro`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `livro_assunto`
--
ALTER TABLE `livro_assunto`
  ADD PRIMARY KEY (`livro_id`,`assunto_id`),
  ADD KEY `IDX_53C2C52A5864C5AF` (`livro_id`),
  ADD KEY `IDX_53C2C52A4CE74285` (`assunto_id`);

--
-- Índices para tabela `livro_autor`
--
ALTER TABLE `livro_autor`
  ADD PRIMARY KEY (`livro_id`,`autor_id`),
  ADD KEY `IDX_67499925864C5AF` (`livro_id`),
  ADD KEY `IDX_674999214D45BBE` (`autor_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `assunto`
--
ALTER TABLE `assunto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `autor`
--
ALTER TABLE `autor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `livro_assunto`
--
ALTER TABLE `livro_assunto`
  ADD CONSTRAINT `FK_53C2C52A4CE74285` FOREIGN KEY (`assunto_id`) REFERENCES `assunto` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_53C2C52A5864C5AF` FOREIGN KEY (`livro_id`) REFERENCES `livro` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `livro_autor`
--
ALTER TABLE `livro_autor`
  ADD CONSTRAINT `FK_674999214D45BBE` FOREIGN KEY (`autor_id`) REFERENCES `autor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_67499925864C5AF` FOREIGN KEY (`livro_id`) REFERENCES `livro` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
