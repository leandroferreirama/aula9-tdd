<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    /** @test */
    public function AvaliadorDeveEncontrarOMaiorValorDeLanceEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $valor = $leiloeiro->getMaiorValor();

        self::assertEquals(2500, $valor);
    }

    /** @test */
    public function AvaliadorDeveEncontrarOMaiorValorDeLanceEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $valor = $leiloeiro->getMaiorValor();

        self::assertEquals(2500, $valor);
    }

    /** @test */
    public function AvaliadorDeveEncontrarOMenorValorDeLanceEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $valor = $leiloeiro->getMenorValor();

        self::assertEquals(2000, $valor);
    }

    /** @test */
    public function AvaliadorDeveEncontrarOMenorValorDeLanceEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $valor = $leiloeiro->getMenorValor();

        self::assertEquals(2000, $valor);
    }

    /** @test */
    public function AvaliadorDeveBuscarTreisMaioresValor()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $pedro = new Usuario('Pedro');
        $jose = new Usuario('Jose');
        $ana = new Usuario('ana');

        $leilao->recebeLance(new Lance($joao, 300));
        $leilao->recebeLance(new Lance($maria, 750));
        $leilao->recebeLance(new Lance($pedro, 980));
        $leilao->recebeLance(new Lance($jose, 150));
        $leilao->recebeLance(new Lance($ana, 1200));

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $maioresLances = $leiloeiro->getMaioresLances();
        self::assertCount(3, $maioresLances);
        self::assertEquals(1200, $maioresLances[0]->getValor());
        self::assertEquals(980, $maioresLances[1]->getValor());
        self::assertEquals(750, $maioresLances[2]->getValor());

    }
}