<?php

namespace App\Controller;

use App\Entity\Autor;
use App\Form\AutorType;
use App\Repository\AutorRepository;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/autor')]
final class AutorController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    #[Route(name: 'app_autor_index', methods: ['GET'])]
    public function index(
        AutorRepository $autorRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        $query = $autorRepository
            ->createQueryBuilder('a')
            ->orderBy('a.nome', 'ASC');

        $autors = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('autor/index.html.twig', [
            'autors' => $autors,
        ]);
    }

    #[Route('/new', name: 'app_autor_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $autor = new Autor();

        $form = $this->createForm(AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($autor);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Autor cadastrado com sucesso.'
                );

                return $this->redirectToRoute(
                    'app_autor_index',
                    [],
                    Response::HTTP_SEE_OTHER
                );

            } catch (UniqueConstraintViolationException $e) {

                $this->logger->warning(
                    'Tentativa de cadastrar autor duplicado.',
                    [
                        'autor' => $autor->getNome(),
                        'sqlState' => $e->getCode(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'warning',
                    'Já existe um autor cadastrado com este nome.'
                );

                return $this->redirectToRoute(
                    'app_autor_new',
                    [],
                    Response::HTTP_SEE_OTHER
                );

            } catch (DBALException $e) {

                $this->logger->error(
                    'Erro de banco de dados ao cadastrar autor.',
                    [
                        'autor' => $autor->getNome(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'danger',
                    'Não foi possível cadastrar o autor devido a um erro no banco de dados.'
                );

                return $this->redirectToRoute(
                    'app_autor_new',
                    [],
                    Response::HTTP_SEE_OTHER
                );

            } catch (\Throwable $e) {

                $this->logger->critical(
                    'Erro inesperado ao cadastrar autor.',
                    [
                        'autor' => $autor->getNome(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'danger',
                    'Ocorreu um erro inesperado ao cadastrar o autor.'
                );

                return $this->redirectToRoute(
                    'app_autor_new',
                    [],
                    Response::HTTP_SEE_OTHER
                );
            }
        }

        return $this->render('autor/new.html.twig', [
            'autor' => $autor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_autor_show', methods: ['GET'])]
    public function show(Autor $autor): Response
    {
        return $this->render('autor/show.html.twig', [
            'autor' => $autor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_autor_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Autor $autor,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Autor atualizado com sucesso.'
                );

                return $this->redirectToRoute(
                    'app_autor_index',
                    [],
                    Response::HTTP_SEE_OTHER
                );

            } catch (UniqueConstraintViolationException $e) {

                $this->logger->warning(
                    'Tentativa de atualizar autor para um nome duplicado.',
                    [
                        'autor_id' => $autor->getId(),
                        'autor' => $autor->getNome(),
                        'sqlState' => $e->getCode(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'warning',
                    'Já existe um autor cadastrado com este nome.'
                );

                return $this->redirectToRoute(
                    'app_autor_edit',
                    ['id' => $autor->getId()],
                    Response::HTTP_SEE_OTHER
                );

            } catch (DBALException $e) {

                $this->logger->error(
                    'Erro de banco de dados ao atualizar autor.',
                    [
                        'autor_id' => $autor->getId(),
                        'autor' => $autor->getNome(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'danger',
                    'Não foi possível atualizar o autor devido a um erro no banco de dados.'
                );

                return $this->redirectToRoute(
                    'app_autor_edit',
                    ['id' => $autor->getId()],
                    Response::HTTP_SEE_OTHER
                );

            } catch (\Throwable $e) {

                $this->logger->critical(
                    'Erro inesperado ao atualizar autor.',
                    [
                        'autor_id' => $autor->getId(),
                        'autor' => $autor->getNome(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'danger',
                    'Ocorreu um erro inesperado ao atualizar o autor.'
                );

                return $this->redirectToRoute(
                    'app_autor_edit',
                    ['id' => $autor->getId()],
                    Response::HTTP_SEE_OTHER
                );
            }
        }

        return $this->render('autor/edit.html.twig', [
            'autor' => $autor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_autor_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Autor $autor,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->isCsrfTokenValid(
            'delete'.$autor->getId(),
            $request->getPayload()->getString('_token')
        )) {
            $this->addFlash(
                'danger',
                'Não foi possível excluir o autor: token de segurança inválido.'
            );

            return $this->redirectToRoute(
                'app_autor_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        /*
        * Regra de negócio opcional:
        *
        * Em alguns cenários pode ser desejável impedir a exclusão de um autor
        * que ainda possua livros associados, evitando que um livro permaneça
        * sem nenhum autor vinculado.
        *
        * Neste teste técnico optamos por permitir a exclusão. Como a tabela
        * intermediária (livro_autor) utiliza ON DELETE CASCADE, apenas os
        * relacionamentos são removidos e os livros permanecem cadastrados.
        *
        * Exemplo de implementação:
        *
        * if (!$autor->getLivros()->isEmpty()) {
        *     $this->addFlash(
        *         'warning',
        *         'Não é possível excluir este autor porque existem livros associados.'
        *     );
        *
        *     return $this->redirectToRoute(
        *         'app_autor_index',
        *         [],
        *         Response::HTTP_SEE_OTHER
        *     );
        * }
        */        

        try {
            $entityManager->remove($autor);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Autor excluído com sucesso.'
            );

        } catch (DBALException $e) {

            $this->logger->error(
                'Erro de banco de dados ao excluir autor.',
                [
                    'autor_id' => $autor->getId(),
                    'autor' => $autor->getNome(),
                    'exception' => $e,
                ]
            );

            $this->addFlash(
                'danger',
                'Não foi possível excluir o autor devido a um erro no banco de dados.'
            );

        } catch (\Throwable $e) {

            $this->logger->critical(
                'Erro inesperado ao excluir autor.',
                [
                    'autor_id' => $autor->getId(),
                    'autor' => $autor->getNome(),
                    'exception' => $e,
                ]
            );

            $this->addFlash(
                'danger',
                'Ocorreu um erro inesperado ao excluir o autor.'
            );
        }

        return $this->redirectToRoute(
            'app_autor_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }
}