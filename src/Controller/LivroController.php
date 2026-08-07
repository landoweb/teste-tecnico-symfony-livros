<?php

namespace App\Controller;

use App\Entity\Livro;
use App\Form\LivroType;
use App\Repository\LivroRepository;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livro')]
final class LivroController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    #[Route(name: 'app_livro_index', methods: ['GET'])]
    public function index(LivroRepository $livroRepository): Response
    {
        return $this->render('livro/index.html.twig', [
            'livros' => $livroRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_livro_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $livro = new Livro();

        $form = $this->createForm(LivroType::class, $livro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($livro);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Livro cadastrado com sucesso.'
                );

                return $this->redirectToRoute(
                    'app_livro_index',
                    [],
                    Response::HTTP_SEE_OTHER
                );

            } catch (DBALException $e) {

                $this->logger->error(
                    'Erro de banco de dados ao cadastrar livro.',
                    [
                        'titulo' => $livro->getTitulo(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'danger',
                    'Não foi possível cadastrar o livro devido a um erro no banco de dados.'
                );

                return $this->redirectToRoute(
                    'app_livro_new',
                    [],
                    Response::HTTP_SEE_OTHER
                );

            } catch (\Throwable $e) {

                $this->logger->critical(
                    'Erro inesperado ao cadastrar livro.',
                    [
                        'titulo' => $livro->getTitulo(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'danger',
                    'Ocorreu um erro inesperado ao cadastrar o livro.'
                );

                return $this->redirectToRoute(
                    'app_livro_new',
                    [],
                    Response::HTTP_SEE_OTHER
                );
            }
        }

        return $this->render('livro/new.html.twig', [
            'livro' => $livro,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livro_show', methods: ['GET'])]
    public function show(Livro $livro): Response
    {
        return $this->render('livro/show.html.twig', [
            'livro' => $livro,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livro_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Livro $livro,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(LivroType::class, $livro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Livro atualizado com sucesso.'
                );

                return $this->redirectToRoute(
                    'app_livro_index',
                    [],
                    Response::HTTP_SEE_OTHER
                );

            } catch (DBALException $e) {

                $this->logger->error(
                    'Erro de banco de dados ao atualizar livro.',
                    [
                        'livro_id' => $livro->getId(),
                        'titulo' => $livro->getTitulo(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'danger',
                    'Não foi possível atualizar o livro devido a um erro no banco de dados.'
                );

                return $this->redirectToRoute(
                    'app_livro_edit',
                    ['id' => $livro->getId()],
                    Response::HTTP_SEE_OTHER
                );

            } catch (\Throwable $e) {

                $this->logger->critical(
                    'Erro inesperado ao atualizar livro.',
                    [
                        'livro_id' => $livro->getId(),
                        'titulo' => $livro->getTitulo(),
                        'exception' => $e,
                    ]
                );

                $this->addFlash(
                    'danger',
                    'Ocorreu um erro inesperado ao atualizar o livro.'
                );

                return $this->redirectToRoute(
                    'app_livro_edit',
                    ['id' => $livro->getId()],
                    Response::HTTP_SEE_OTHER
                );
            }
        }

        return $this->render('livro/edit.html.twig', [
            'livro' => $livro,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livro_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Livro $livro,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->isCsrfTokenValid(
            'delete'.$livro->getId(),
            $request->getPayload()->getString('_token')
        )) {
            $this->addFlash(
                'danger',
                'Não foi possível excluir o livro: token de segurança inválido.'
            );

            return $this->redirectToRoute(
                'app_livro_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        try {
            $entityManager->remove($livro);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Livro excluído com sucesso.'
            );

        } catch (DBALException $e) {

            $this->logger->error(
                'Erro de banco de dados ao excluir livro.',
                [
                    'livro_id' => $livro->getId(),
                    'titulo' => $livro->getTitulo(),
                    'exception' => $e,
                ]
            );

            $this->addFlash(
                'danger',
                'Não foi possível excluir o livro devido a um erro no banco de dados.'
            );

        } catch (\Throwable $e) {

            $this->logger->critical(
                'Erro inesperado ao excluir livro.',
                [
                    'livro_id' => $livro->getId(),
                    'titulo' => $livro->getTitulo(),
                    'exception' => $e,
                ]
            );

            $this->addFlash(
                'danger',
                'Ocorreu um erro inesperado ao excluir o livro.'
            );
        }

        return $this->redirectToRoute(
            'app_livro_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }
}