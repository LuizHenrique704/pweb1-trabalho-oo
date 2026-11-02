<?php

declare(strict_types=1);

class Veiculo
{
    private string $placa;
    private string $modelo;

    public function __construct(string $placa, string $modelo)
    {
        $placa = strtoupper(trim($placa));

        if ($placa === '') {
            throw new Exception('A placa não pode estar vazia.');
        }

        $this->placa = $placa;
        $this->modelo = trim($modelo);
    }

    public function getPlaca(): string
    {
        return $this->placa;
    }

    public function getModelo(): string
    {
        return $this->modelo;
    }

    public function calcularTarifaHora(): float
    {
        return 5.0;
    }

    public function paraArray(): array
    {
        return [
            'placa' => $this->getPlaca(),
            'modelo' => $this->getModelo(),
            'tarifaHora' => $this->calcularTarifaHora(),
        ];
    }
}