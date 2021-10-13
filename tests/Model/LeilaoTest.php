<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    /** @test */
    public function leilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        $leilao = new Leilao("Brasília Amarela");
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode dar mais que cinco lances');

        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 1500));

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        
        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($maria, 3500));
        
        $leilao->recebeLance(new Lance($joao, 4000));
        $leilao->recebeLance(new Lance($maria, 4500));
        
        $leilao->recebeLance(new Lance($joao, 5000));
        $leilao->recebeLance(new Lance($maria, 5500));
        
        $leilao->recebeLance(new Lance($joao, 6000));
        $leilao->recebeLance(new Lance($maria, 6500));

    }
    /** @test */
    public function leilaoNaoDeveReceberLancesRepitidos()
    {
        $leilao = new Leilao('Variante');

        $joao = new Usuario('João');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode dar dois lances consecutivos');

        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($joao, 1500));
    }


    /** 
     * @test 
     * @dataProvider geraLances
    */
    public function leilaoDeveReceberLances(int $qtdLances, Leilao $leilao, array $valores)
    {
        self::assertCount($qtdLances, $leilao->getLances());

        foreach($valores as $i => $valorEsperado){  
            self::assertEquals($valorEsperado, $leilao->getLances()[$i]->getValor());
        }
    }

    public function geraLances()
    {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 2000));

        $leilaoB = new Leilao('Fusca 1972 0KM');
        $leilaoB->recebeLance(new Lance($maria, 5000));

        return [
            '2-lances'=>[2, $leilao, [1000,2000]],
            '1-lance'=>[1, $leilaoB, [5000]]
        ];
    }
}
