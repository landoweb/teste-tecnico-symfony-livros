<?php

namespace App\Controller;

use App\Entity\Assunto;
use App\Form\AssuntoType;
use App\Repository\AssuntoRepository;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/assunto')]
final class AssuntoController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    #[Route(name: 'app_assunto_index', methods: ['GET'])]
    public function index(AssuntoRepository $assuntoRepository): Response
    {
        return $this->render('assunto/index.html.twig', [
            'assuntos' => $assuntoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_assunto_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $assunto = new Assunto();

        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($assunto);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Assunto cadastrado com sucesso.'
                );

                return $this->redirectToRoute(
                    'app_assunto_index',
                    [],
                    Response::HTTP_SEE_OTHER
                );

            } catch (UniqueConstraintViolationException $e) {

                $this->logger->warning(
                    'Tentativa de cadastrar assunto duplicado.',
                    [
                        'assunto' => $assunto->getDescricao(),
                        'sqlState' => $e->getCode(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'warning',
                    'Já existe um assunto cadastrado com esta descrição.'
                );

                return $this->redirectToRoute(
                    'app_assunto_new',
                    [],
                    Response::HTTP_SEE_OTHER
                );

            } catch (DBALException $e) {

                $this->logger->error(
                    'Erro de banco de dados ao cadastrar assunto.',
                    [
                        'assunto' => $assunto->getDescricao(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'danger',
                    'Não foi possível cadastrar o assunto devido a um erro no banco de dados.'
                );

                return $this->redirectToRoute(
                    'app_assunto_new',
                    [],
                    Response::HTTP_SEE_OTHER
                );

            } catch (\Throwable $e) {

                $this->logger->critical(
                    'Erro inesperado ao cadastrar assunto.',
                    [
                        'assunto' => $assunto->getDescricao(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'danger',
                    'Ocorreu um erro inesperado ao cadastrar o assunto.'
                );

                return $this->redirectToRoute(
                    'app_assunto_new',
                    [],
                    Response::HTTP_SEE_OTHER
                );
            }
        }

        return $this->render('assunto/new.html.twig', [
            'assunto' => $assunto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_assunto_show', methods: ['GET'])]
    public function show(Assunto $assunto): Response
    {
        return $this->render('assunto/show.html.twig', [
            'assunto' => $assunto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_assunto_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Assunto $assunto,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Assunto atualizado com sucesso.'
                );

                return $this->redirectToRoute(
                    'app_assunto_index',
                    [],
                    Response::HTTP_SEE_OTHER
                );

            } catch (UniqueConstraintViolationException $e) {

                $this->logger->warning(
                    'Tentativa de atualizar assunto para uma descrição duplicada.',
                    [
                        'assunto_id' => $assunto->getId(),
                        'descricao' => $assunto->getDescricao(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'warning',
                    'Já existe um assunto cadastrado com esta descrição.'
                );

                return $this->redirectToRoute(
                    'app_assunto_edit',
                    ['id' => $assunto->getId()],
                    Response::HTTP_SEE_OTHER
                );

            } catch (DBALException $e) {

                $this->logger->error(
                    'Erro de banco de dados ao atualizar assunto.',
                    [
                        'assunto_id' => $assunto->getId(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'danger',
                    'Não foi possível atualizar o assunto devido a um erro no banco de dados.'
                );
            }
        }

        return $this->render('assunto/edit.html.twig', [
            'assunto' => $assunto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_assunto_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Assunto $assunto,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->isCsrfTokenValid(
            'delete'.$assunto->getId(),
            $request->getPayload()->getString('_token')
        )) {
            $this->addFlash(
                'danger',
                'Não foi possível excluir o assunto: token de segurança inválido.'
            );

            return $this->redirectToRoute(
                'app_assunto_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        /*
        * Regra de negócio opcional:
        *
        * Em alguns cenários pode ser desejável impedir a exclusão de um assunto
        * que ainda possua livros associados, evitando que um livro permaneça
        * sem nenhum assunto vinculado.
        *
        * Neste teste técnico optamos por permitir a exclusão. Como a tabela
        * intermediária (livro_assunto) utiliza ON DELETE CASCADE, apenas os
        * relacionamentos são removidos e os livros permanecem cadastrados.
        *
        * Exemplo de implementação:
        *
        * if (!$assunto->getLivros()->isEmpty()) {
        *     $this->addFlash(
        *         'warning',
        *         'Não é possível excluir este assunto porque existem livros associados.'
        *     );
        *
        *     return $this->redirectToRoute(
        *         'app_assunto_index',
        *         [],
        *         Response::HTTP_SEE_OTHER
        *     );
        * }
        */        

        try {
            $entityManager->remove($assunto);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Assunto excluído com sucesso.'
            );

        } catch (DBALException $e) {

            $this->logger->error(
                'Erro de banco de dados ao excluir assunto.',
                [
                    'assunto_id' => $assunto->getId(),
                    'exception' => $e,
                ]
            );

            $this->addFlash(
                'danger',
                'Não foi possível excluir o assunto devido a um erro no banco de dados.'
            );
        }

        return $this->redirectToRoute(
            'app_assunto_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }
}