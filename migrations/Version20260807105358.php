<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260807105358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cria a view utilizada pelo relatório de livros agrupados por autor';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE VIEW vw_relatorio_livros AS
            SELECT
                a.id AS autor_id,
                a.nome AS autor_nome,
                l.id AS livro_id,
                l.titulo AS livro_titulo,
                l.editora,
                l.edicao,
                l.ano_publicacao,
                l.valor,
                GROUP_CONCAT(
                    DISTINCT ass.descricao
                    ORDER BY ass.descricao
                    SEPARATOR ', '
                ) AS assuntos
            FROM autor a
            INNER JOIN livro_autor la
                ON la.autor_id = a.id
            INNER JOIN livro l
                ON l.id = la.livro_id
            LEFT JOIN livro_assunto las
                ON las.livro_id = l.id
            LEFT JOIN assunto ass
                ON ass.id = las.assunto_id
            GROUP BY
                a.id,
                a.nome,
                l.id,
                l.titulo,
                l.editora,
                l.edicao,
                l.ano_publicacao,
                l.valor
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP VIEW IF EXISTS vw_relatorio_livros');
    }
}
