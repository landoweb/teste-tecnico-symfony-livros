<?php

namespace App\Tests\Entity;

use App\Entity\Assunto;
use App\Entity\Livro;
use PHPUnit\Framework\TestCase;

class AssuntoTest extends TestCase
{
    public function testAssuntoInicialmenteNaoPossuiId(): void
    {
        $assunto = new Assunto();

        $this->assertNull($assunto->getId());
    }

    public function testDescricaoPodeSerDefinida(): void
    {
        $assunto = new Assunto();

        $retorno = $assunto->setDescricao('Ficção Científica');

        $this->assertSame(
            'Ficção Científica',
            $assunto->getDescricao()
        );

        $this->assertSame(
            $assunto,
            $retorno
        );
    }

    public function testColecaoDeLivrosIniciaVazia(): void
    {
        $assunto = new Assunto();

        $this->assertCount(
            0,
            $assunto->getLivros()
        );
    }

    public function testLivroPodeSerAdicionadoAoAssunto(): void
    {
        $assunto = new Assunto();
        $livro = new Livro();

        $assunto->addLivro($livro);

        $this->assertCount(
            1,
            $assunto->getLivros()
        );

        $this->assertTrue(
            $assunto->getLivros()->contains($livro)
        );

        $this->assertTrue(
            $livro->getAssuntos()->contains($assunto)
        );
    }

    public function testMesmoLivroNaoEhAdicionadoDuasVezes(): void
    {
        $assunto = new Assunto();
        $livro = new Livro();

        $assunto->addLivro($livro);
        $assunto->addLivro($livro);

        $this->assertCount(
            1,
            $assunto->getLivros()
        );
    }

    public function testLivroPodeSerRemovidoDoAssunto(): void
    {
        $assunto = new Assunto();
        $livro = new Livro();

        $assunto->addLivro($livro);
        $assunto->removeLivro($livro);

        $this->assertCount(
            0,
            $assunto->getLivros()
        );

        $this->assertFalse(
            $livro->getAssuntos()->contains($assunto)
        );
    }
}