<?php

declare(strict_types=1);

require_once __DIR__ . '/Veiculo.php';

class Moto extends Veiculo
{
    public function __construct(string $placa, string $modelo)
    {
        parent::__construct($placa, $modelo);
    }

    public function calcularTarifaHora(): float
    {
        return 3.0;
    }

    public function paraArray(): array
    {
        $dados = parent::paraArray();
        $dados['tipo'] = 'moto';

        return $dados;
    }
}