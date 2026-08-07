<?php

namespace App\Repository;

use App\Entity\Livro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livro>
 */
class LivroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livro::class);
    }

    /*
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
     *     return $this->createQueryBuilder('l')
     *         ->orderBy('l.titulo', 'ASC')
     *         ->setFirstResult($offset)
     *         ->setMaxResults($limite)
     *         ->getQuery()
     *         ->getResult();
     * }
     */

    /*
     * Exemplo de busca utilizando Doctrine QueryBuilder.
     *
     * Em um projeto maior, seria comum disponibilizar filtros por título,
     * autor e assunto para facilitar a localização dos livros cadastrados.
     *
     * Neste teste técnico optamos por manter a listagem simples, porém a
     * implementação abaixo demonstra uma abordagem comum utilizando
     * QueryBuilder e relacionamentos do Doctrine.
     *
     * Exemplo:
     *
     * public function buscar(
     *     ?string $titulo,
     *     ?int $autorId,
     *     ?int $assuntoId
     * ): array
     * {
     *     $qb = $this->createQueryBuilder('l')
     *         ->leftJoin('l.autores', 'a')
     *         ->leftJoin('l.assuntos', 's');
     *
     *     if ($titulo) {
     *         $qb
     *             ->andWhere('l.titulo LIKE :titulo')
     *             ->setParameter('titulo', '%' . $titulo . '%');
     *     }
     *
     *     if ($autorId) {
     *         $qb
     *             ->andWhere('a.id = :autorId')
     *             ->setParameter('autorId', $autorId);
     *     }
     *
     *     if ($assuntoId) {
     *         $qb
     *             ->andWhere('s.id = :assuntoId')
     *             ->setParameter('assuntoId', $assuntoId);
     *     }
     *
     *     return $qb
     *         ->orderBy('l.titulo', 'ASC')
     *         ->getQuery()
     *         ->getResult();
     * }
     */
}