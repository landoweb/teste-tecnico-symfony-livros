<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

class RelatorioRepository
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public function findLivrosAgrupadosPorAutor(): array
    {
        return $this->connection
            ->executeQuery(
                '
                    SELECT
                        autor_id,
                        autor_nome,
                        livro_id,
                        livro_titulo,
                        editora,
                        edicao,
                        ano_publicacao,
                        valor,
                        assuntos
                    FROM vw_relatorio_livros
                    ORDER BY autor_nome, livro_titulo
                '
            )
            ->fetchAllAssociative();
    }
}