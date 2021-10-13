<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    private Avaliador $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }
    
    /** @test */
    public function leilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar leilões finalizado');

        $leilao = new Leilao('Fiat 147 0km');
        $leilao->recebeLance(new Lance(new Usuario('João'), 1000));

        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }

    /** @test */
    public function leilaoVazioNaoPodeSer()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar leilão vazio.');

        $leilao = new Leilao('Fusca Azul');
        $this->leiloeiro->avalia($leilao);
    }

    /** 
     * @test 
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoreo
     * */
    public function AvaliadorDeveEncontrarOMaiorValorDeLance(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $valor = $this->leiloeiro->getMaiorValor();

        self::assertEquals(2500, $valor);
    }

    /** 
     * @test 
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoreo
     * */
    public function AvaliadorDeveEncontrarOMenorValorDeLance(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $valor = $this->leiloeiro->getMenorValor();

        self::assertEquals(1500, $valor);
    }

    /** 
     * @test 
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoreo
     * */
    public function AvaliadorDeveBuscarTreisMaioresValor(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maioresLances = $this->leiloeiro->getMaioresLances();
        self::assertCount(3, $maioresLances);
        self::assertEquals(2500, $maioresLances[0]->getValor());
        self::assertEquals(2000, $maioresLances[1]->getValor());
        self::assertEquals(1800, $maioresLances[2]->getValor());

    }

    public function leilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');
        $pedro = new Usuario('Pedro');
        $jose = new Usuario('Jose');

        $leilao->recebeLance(new Lance($pedro, 1500));
        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($jose, 1800));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return ['ordem-crescente' => [$leilao]];
    }

    public function leilaoEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');
        $pedro = new Usuario('Pedro');
        $jose = new Usuario('Jose');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($jose, 1800));
        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($pedro, 1500));

        return ['ordem-decrescente' => [$leilao]];
    }

    public function leilaoEmOrdemAleatoreo()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');
        $pedro = new Usuario('Pedro');
        $jose = new Usuario('Jose');

        $leilao->recebeLance(new Lance($jose, 1800));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($pedro, 1500));

        return ['orderm-aleatoria' =>[$leilao]];
    }
}