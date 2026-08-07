<?php

namespace App\Tests\Entity;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use PHPUnit\Framework\TestCase;

class LivroTest extends TestCase
{
    public function testLivroInicialmenteNaoPossuiId(): void
    {
        $livro = new Livro();

        $this->assertNull($livro->getId());
    }

    public function testDadosBasicosPodemSerDefinidos(): void
    {
        $livro = new Livro();

        $livro
            ->setTitulo('Dom Casmurro')
            ->setEditora('Editora Exemplo')
            ->setEdicao(3)
            ->setAnoPublicacao('1899')
            ->setValor('59.90');

        $this->assertSame('Dom Casmurro', $livro->getTitulo());
        $this->assertSame('Editora Exemplo', $livro->getEditora());
        $this->assertSame(3, $livro->getEdicao());
        $this->assertSame('1899', $livro->getAnoPublicacao());
        $this->assertSame('59.90', $livro->getValor());
    }

    public function testColecoesIniciamVazias(): void
    {
        $livro = new Livro();

        $this->assertCount(0, $livro->getAutores());
        $this->assertCount(0, $livro->getAssuntos());
    }

    public function testAutorPodeSerAdicionado(): void
    {
        $livro = new Livro();
        $autor = new Autor();

        $livro->addAutor($autor);

        $this->assertCount(1, $livro->getAutores());

        $this->assertTrue(
            $livro->getAutores()->contains($autor)
        );
    }

    public function testMesmoAutorNaoEhAdicionadoDuasVezes(): void
    {
        $livro = new Livro();
        $autor = new Autor();

        $livro->addAutor($autor);
        $livro->addAutor($autor);

        $this->assertCount(1, $livro->getAutores());
    }

    public function testAutorPodeSerRemovido(): void
    {
        $livro = new Livro();
        $autor = new Autor();

        $livro->addAutor($autor);
        $livro->removeAutor($autor);

        $this->assertCount(0, $livro->getAutores());
    }

    public function testAssuntoPodeSerAdicionado(): void
    {
        $livro = new Livro();
        $assunto = new Assunto();

        $livro->addAssunto($assunto);

        $this->assertCount(1, $livro->getAssuntos());

        $this->assertTrue(
            $livro->getAssuntos()->contains($assunto)
        );
    }

    public function testMesmoAssuntoNaoEhAdicionadoDuasVezes(): void
    {
        $livro = new Livro();
        $assunto = new Assunto();

        $livro->addAssunto($assunto);
        $livro->addAssunto($assunto);

        $this->assertCount(1, $livro->getAssuntos());
    }

    public function testAssuntoPodeSerRemovido(): void
    {
        $livro = new Livro();
        $assunto = new Assunto();

        $livro->addAssunto($assunto);
        $livro->removeAssunto($assunto);

        $this->assertCount(0, $livro->getAssuntos());
    }
}