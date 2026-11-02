<?php

declare(strict_types=1);

require_once __DIR__ . '/Veiculo.php';

class Estadia
{
    private int $id;
    private Veiculo $veiculo;
    private int $horas;

    public function __construct(int $id, Veiculo $veiculo, int $horas)
    {
        if ($id <= 0) {
            throw new Exception(
                'O identificador da estadia deve ser maior que zero.'
            );
        }

        if ($horas <= 0) {
            throw new Exception(
                'A duração da estadia deve ser maior que zero.'
            );
        }

        $this->id = $id;
        $this->veiculo = $veiculo;
        $this->horas = $horas;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVeiculo(): Veiculo
    {
        return $this->veiculo;
    }

    public function getHoras(): int
    {
        return $this->horas;
    }

    public function calcularValor(): float
    {
        return $this->horas * $this->veiculo->calcularTarifaHora();
    }

    public function paraArray(): array
    {
        return [
            'id' => $this->id,
            'veiculo' => $this->veiculo->paraArray(),
            'horas' => $this->horas,
            'valor' => $this->calcularValor(),
        ];
    }
}