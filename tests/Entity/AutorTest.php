<?php

namespace App\Tests\Entity;

use App\Entity\Autor;
use App\Entity\Livro;
use PHPUnit\Framework\TestCase;

class AutorTest extends TestCase
{
    public function testAutorInicialmenteNaoPossuiId(): void
    {
        $autor = new Autor();

        $this->assertNull($autor->getId());
    }

    public function testNomePodeSerDefinido(): void
    {
        $autor = new Autor();

        $retorno = $autor->setNome('Machado de Assis');

        $this->assertSame(
            'Machado de Assis',
            $autor->getNome()
        );

        $this->assertSame(
            $autor,
            $retorno
        );
    }

    public function testColecaoDeLivrosIniciaVazia(): void
    {
        $autor = new Autor();

        $this->assertCount(
            0,
            $autor->getLivros()
        );
    }

    public function testLivroPodeSerAdicionadoAoAutor(): void
    {
        $autor = new Autor();
        $livro = new Livro();

        $autor->addLivro($livro);

        $this->assertCount(
            1,
            $autor->getLivros()
        );

        $this->assertTrue(
            $autor->getLivros()->contains($livro)
        );

        $this->assertTrue(
            $livro->getAutores()->contains($autor)
        );
    }

    public function testMesmoLivroNaoEhAdicionadoDuasVezes(): void
    {
        $autor = new Autor();
        $livro = new Livro();

        $autor->addLivro($livro);
        $autor->addLivro($livro);

        $this->assertCount(
            1,
            $autor->getLivros()
        );
    }

    public function testLivroPodeSerRemovidoDoAutor(): void
    {
        $autor = new Autor();
        $livro = new Livro();

        $autor->addLivro($livro);
        $autor->removeLivro($livro);

        $this->assertCount(
            0,
            $autor->getLivros()
        );

        $this->assertFalse(
            $livro->getAutores()->contains($autor)
        );
    }
}