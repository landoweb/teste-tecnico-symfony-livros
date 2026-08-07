<?php

namespace App\Controller;

use App\Repository\RelatorioRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RelatorioController extends AbstractController
{
    #[Route('/relatorio', name: 'app_relatorio', methods: ['GET'])]
    public function index(RelatorioRepository $relatorioRepository): Response
    {
        $relatorio = $relatorioRepository->findLivrosAgrupadosPorAutor();

        return $this->render('relatorio/index.html.twig', [
            'relatorio' => $relatorio,
        ]);
    }

    #[Route('/relatorio/pdf', name: 'app_relatorio_pdf', methods: ['GET'])]
    public function pdf(RelatorioRepository $relatorioRepository): Response
    {
        $relatorio = $relatorioRepository->findLivrosAgrupadosPorAutor();

        $html = $this->renderView('relatorio/pdf.html.twig', [
            'relatorio' => $relatorio,
        ]);

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html, 'UTF-8');

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        $nomeArquivo = sprintf(
            'Relatorio_Livros_%s.pdf',
            date('Ymd_His')
        );

        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf(
                    'inline; filename="%s"',
                    $nomeArquivo
                ),
            ]
        );
    }
}