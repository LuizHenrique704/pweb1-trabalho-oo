<?php

declare(strict_types=1);

require_once __DIR__ . '/src/Veiculo.php';
require_once __DIR__ . '/src/Carro.php';
require_once __DIR__ . '/src/Moto.php';
require_once __DIR__ . '/src/Estadia.php';
require_once __DIR__ . '/src/Estacionamento.php';

function verificar(bool $condicao, string $descricao): void
{
    if (!$condicao) {
        echo "FALHOU: {$descricao}\n";
        exit(1);
    }

    echo "OK: {$descricao}\n";
}

function testarRecusa(
    callable $operacao,
    string $mensagemEsperada,
    string $descricao
): void {
    try {
        $operacao();
    } catch (Exception $e) {
        verificar(
            $e->getMessage() === $mensagemEsperada,
            $descricao . ' — ' . $e->getMessage()
        );

        return;
    }

    echo "FALHOU: {$descricao} — a operação foi aceita.\n";
    exit(1);
}

echo "=== 1. Veículos e encapsulamento ===\n";

$veiculo = new Veiculo(' def2g34 ', ' Modelo padrão ');
$carro = new Carro(' abc1d23 ', ' Uno ');
$moto = new Moto(' xyz4e56 ', ' Biz ');

verificar(
    $veiculo->getPlaca() === 'DEF2G34'
    && $veiculo->getModelo() === 'Modelo padrão'
    && $veiculo->calcularTarifaHora() === 5.0,
    'Veiculo aceita dados válidos e aplica a tarifa padrão.'
);

verificar(
    $carro->getPlaca() === 'ABC1D23'
    && $carro->getModelo() === 'Uno'
    && $moto->getPlaca() === 'XYZ4E56'
    && $moto->getModelo() === 'Biz',
    'Getters retornam placas e modelos padronizados.'
);

foreach ([Veiculo::class, Carro::class, Moto::class] as $classe) {
    foreach (['', '   '] as $placa) {
        testarRecusa(
            fn() => new $classe($placa, 'Teste'),
            'A placa não pode estar vazia.',
            "{$classe} recusa placa " . var_export($placa, true)
        );
    }
}

echo "\n=== 2. Herança e polimorfismo ===\n";

$veiculos = [$carro, $moto];
$tarifasEsperadas = [5.0, 3.0];

foreach ($veiculos as $indice => $objeto) {
    $tarifa = $objeto->calcularTarifaHora();

    verificar(
        $objeto instanceof Veiculo
        && $tarifa === $tarifasEsperadas[$indice],
        get_class($objeto)
        . ' responde ao mesmo método com tarifa de R$ '
        . number_format($tarifa, 2, ',', '.')
    );
}

echo "\n=== 3. Estadias e cálculo individual ===\n";

$estadiaCarro = new Estadia(1, $carro, 3);
$estadiaMoto = new Estadia(2, $moto, 2);

verificar(
    $estadiaCarro->getId() === 1
    && $estadiaCarro->getVeiculo() === $carro
    && $estadiaCarro->getHoras() === 3,
    'Estadia guarda identificador, veículo e horas.'
);

verificar(
    $estadiaCarro->calcularValor() === 15.0
    && $estadiaMoto->calcularValor() === 6.0,
    'Carro por 3 horas custa R$ 15,00; moto por 2 horas custa R$ 6,00.'
);

foreach ([0, -2] as $horas) {
    testarRecusa(
        fn() => new Estadia(3, $carro, $horas),
        'A duração da estadia deve ser maior que zero.',
        "Recusa duração de {$horas} horas"
    );
}

foreach ([0, -1] as $id) {
    testarRecusa(
        fn() => new Estadia($id, $carro, 1),
        'O identificador da estadia deve ser maior que zero.',
        "Recusa identificador {$id}"
    );
}

echo "\n=== 4. Estacionamento e composição ===\n";

$estacionamento = new Estacionamento(' Central ', 2);

verificar(
    $estacionamento->getNome() === 'Central'
    && $estacionamento->getCapacidade() === 2
    && $estacionamento->getEstadias() === []
    && $estacionamento->calcularTotal() === 0.0,
    'Estacionamento válido começa vazio e com total zero.'
);

$estacionamento->adicionarEstadia($estadiaCarro);

verificar(
    count($estacionamento->getEstadias()) === 1
    && $estacionamento->calcularTotal() === 15.0,
    'Aceita a primeira estadia enquanto há vaga.'
);

$antesDaRecusa = $estacionamento->paraArray();

testarRecusa(
    fn() => $estacionamento->adicionarEstadia(
        new Estadia(1, $moto, 1)
    ),
    'Já existe uma estadia com esse identificador.',
    'Recusa identificador repetido mesmo havendo vaga'
);

verificar(
    $estacionamento->paraArray() === $antesDaRecusa,
    'Recusa por identificador repetido preserva os dados.'
);

$estacionamento->adicionarEstadia($estadiaMoto);

verificar(
    $estacionamento->getEstadias() === [$estadiaCarro, $estadiaMoto]
    && $estacionamento->calcularTotal() === 21.0,
    'Aceita identificador diferente na última vaga e soma R$ 21,00.'
);

$antesDaRecusa = $estacionamento->paraArray();

testarRecusa(
    fn() => $estacionamento->adicionarEstadia(
        new Estadia(3, $carro, 1)
    ),
    'O estacionamento está lotado.',
    'Recusa nova estadia quando a capacidade foi atingida'
);

verificar(
    $estacionamento->paraArray() === $antesDaRecusa,
    'Recusa por lotação preserva os dados.'
);

foreach (['', '   '] as $nome) {
    testarRecusa(
        fn() => new Estacionamento($nome, 2),
        'O nome do estacionamento não pode estar vazio.',
        'Recusa nome ' . var_export($nome, true)
    );
}

foreach ([0, -1] as $capacidade) {
    testarRecusa(
        fn() => new Estacionamento('Central', $capacidade),
        'A capacidade deve ser maior que zero.',
        "Recusa capacidade {$capacidade}"
    );
}

echo "\n=== 5. Conversão para array e JSON ===\n";

verificar(
    $veiculo->paraArray() === [
        'placa' => 'DEF2G34',
        'modelo' => 'Modelo padrão',
        'tarifaHora' => 5.0,
    ],
    'Veiculo converte seus dados para array.'
);

verificar(
    $carro->paraArray()['tipo'] === 'carro'
    && $moto->paraArray()['tipo'] === 'moto'
    && $carro->paraArray()['tarifaHora'] === 5.0
    && $moto->paraArray()['tarifaHora'] === 3.0,
    'Classes filhas incluem tipo e tarifa correta no array.'
);

$dados = $estacionamento->paraArray();

verificar(
    $dados === [
        'nome' => 'Central',
        'capacidade' => 2,
        'estadias' => [
            [
                'id' => 1,
                'veiculo' => $carro->paraArray(),
                'horas' => 3,
                'valor' => 15.0,
            ],
            [
                'id' => 2,
                'veiculo' => $moto->paraArray(),
                'horas' => 2,
                'valor' => 6.0,
            ],
        ],
        'total' => 21.0,
    ],
    'Composição inteira é convertida em arrays com os valores esperados.'
);

$json = json_encode(
    $dados,
    JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
);

$recuperado = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

verificar(
    $recuperado['estadias'][1]['veiculo']['tipo'] === 'moto'
    && (float) $recuperado['total'] === 21.0,
    'JSON gerado pode ser lido preservando tipo do veículo e total.'
);

echo "\nSUCESSO: todos os testes do núcleo passaram.\n";