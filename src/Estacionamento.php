<?php

declare(strict_types=1);

require_once __DIR__ . '/Estadia.php';

class Estacionamento
{
    private string $nome;
    private int $capacidade;

    /** @var Estadia[] */
    private array $estadias;

    public function __construct(string $nome, int $capacidade)
    {
        $nome = trim($nome);

        if ($nome === '') {
            throw new Exception(
                'O nome do estacionamento não pode estar vazio.'
            );
        }

        if ($capacidade <= 0) {
            throw new Exception(
                'A capacidade deve ser maior que zero.'
            );
        }

        $this->nome = $nome;
        $this->capacidade = $capacidade;
        $this->estadias = [];
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getCapacidade(): int
    {
        return $this->capacidade;
    }

    /** @return Estadia[] */
    public function getEstadias(): array
    {
        return $this->estadias;
    }

    public function adicionarEstadia(Estadia $estadia): void
    {
        foreach ($this->estadias as $existente) {
            if ($existente->getId() === $estadia->getId()) {
                throw new Exception(
                    'Já existe uma estadia com esse identificador.'
                );
            }
        }

        if (count($this->estadias) >= $this->capacidade) {
            throw new Exception('O estacionamento está lotado.');
        }

        $this->estadias[] = $estadia;
    }

    public function calcularTotal(): float
    {
        $total = 0.0;

        foreach ($this->estadias as $estadia) {
            $total += $estadia->calcularValor();
        }

        return $total;
    }

    public function paraArray(): array
    {
        $estadias = [];

        foreach ($this->estadias as $estadia) {
            $estadias[] = $estadia->paraArray();
        }

        return [
            'nome' => $this->nome,
            'capacidade' => $this->capacidade,
            'estadias' => $estadias,
            'total' => $this->calcularTotal(),
        ];
    }
}