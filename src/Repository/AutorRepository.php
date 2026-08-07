<?php

namespace App\Repository;

use App\Entity\Autor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Autor>
 */
class AutorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Autor::class);
    }

    /**
     * Exemplo de implementação de paginação utilizando Doctrine QueryBuilder.
     *
     * Em projetos com grande volume de registros, normalmente optamos por
     * bibliotecas consolidadas como o KnpPaginatorBundle, responsáveis por
     * abstrair toda a lógica de paginação, ordenação e navegação entre páginas.
     *
     * Neste projeto, a paginação não foi implementada por não haver necessidade,
     * já que o volume de dados previsto é reduzido.
     *
     * Exemplo:
     *
     * public function findPaginado(int $pagina, int $limite): array
     * {
     *     $offset = ($pagina - 1) * $limite;
     *
     *     return $this->createQueryBuilder('a')
     *         ->orderBy('a.nome', 'ASC')
     *         ->setFirstResult($offset)
     *         ->setMaxResults($limite)
     *         ->getQuery()
     *         ->getResult();
     * }
     */
}
