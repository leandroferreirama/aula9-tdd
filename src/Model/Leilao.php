<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    private bool $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance)
    {
        if(! empty($this->lances) && $this->ehDoUltimoUsuario($lance)){
            throw new \DomainException('Usuário não pode dar dois lances consecutivos');
        }
        $usuario = $lance->getUsuario();

        $totalLances = $this->quantidadeLancesPorUsuario($usuario);
        
        if($totalLances >= 5){
            throw new \DomainException('Usuário não pode dar mais que cinco lances');
        }
        $this->lances[] = $lance;
    }
    
    private function quantidadeLancesPorUsuario($usuario): int
    {
       $totalLances =  array_reduce(
            $this->lances,
            function(int $totalAcumulado, Lance $lanceAtual) use ($usuario){
                if($lanceAtual->getUsuario() == $usuario){
                    return $totalAcumulado + 1;
                }
                return $totalAcumulado;
            },
            0
        );

        return $totalLances;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    private function ehDoUltimoUsuario(Lance $lance): bool
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    public function finaliza()
    {
        $this->finalizado = true;
    }

    public function getFinalizado(): bool
    {
        return $this->finalizado;
    }
}
