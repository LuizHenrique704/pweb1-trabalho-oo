<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Carro.php';
require_once __DIR__ . '/../src/Moto.php';
require_once __DIR__ . '/../src/Estadia.php';
require_once __DIR__ . '/../src/Estacionamento.php';

// Funções auxiliares.
function escapar(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function moeda(float $valor): string
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function campoTexto(array $dados, string $campo): string
{
    $valor = $dados[$campo] ?? '';

    return is_string($valor) ? $valor : '';
}

// Dados iniciais para demonstrar o sistema.
$estacionamento = new Estacionamento('Estacionamento Central', 5);

$estacionamento->adicionarEstadia(
    new Estadia(1, new Carro('ABC1D23', 'Uno'), 3)
);

$estacionamento->adicionarEstadia(
    new Estadia(2, new Moto('XYZ4E56', 'Biz'), 2)
);

// Escolha da página.
$pagina = $_GET['pagina'] ?? 'inicio';

if (
    !is_string($pagina)
    || !in_array($pagina, ['inicio', 'estadias', 'detalhe', 'cadastro'], true)
) {
    $pagina = '404';
    http_response_code(404);
}

$erro = '';
$sucesso = '';
$estadiaCriada = null;
$estadiaSelecionada = null;

$campos = [
    'tipo' => 'carro',
    'placa' => '',
    'modelo' => '',
    'horas' => '',
];

// Formulário: validação e criação dos objetos.
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && $pagina === 'cadastro'
) {
    foreach (array_keys($campos) as $campo) {
        $campos[$campo] = campoTexto($_POST, $campo);
    }

    try {
        if (!in_array($campos['tipo'], ['carro', 'moto'], true)) {
            throw new Exception('Selecione carro ou moto.');
        }

        $horas = filter_var($campos['horas'], FILTER_VALIDATE_INT);

        if ($horas === false) {
            throw new Exception('Informe a duração em horas inteiras.');
        }

        if ($campos['tipo'] === 'carro') {
            $veiculo = new Carro($campos['placa'], $campos['modelo']);
        } else {
            $veiculo = new Moto($campos['placa'], $campos['modelo']);
        }

        $maiorId = 0;

        foreach ($estacionamento->getEstadias() as $estadia) {
            $maiorId = max($maiorId, $estadia->getId());
        }

        $novaEstadia = new Estadia($maiorId + 1, $veiculo, $horas);
        $estacionamento->adicionarEstadia($novaEstadia);

        $estadiaCriada = $novaEstadia;
        $sucesso = 'Estadia cadastrada nesta requisição.';
    } catch (Exception $e) {
        $erro = $e->getMessage();
        http_response_code(422);
    }
}

// Detalhe: validação do ID e busca do objeto.
if ($pagina === 'detalhe') {
    $idRecebido = $_GET['id'] ?? '';
    $id = is_string($idRecebido)
        ? filter_var($idRecebido, FILTER_VALIDATE_INT)
        : false;

    if ($id !== false && $id > 0) {
        foreach ($estacionamento->getEstadias() as $estadia) {
            if ($estadia->getId() === $id) {
                $estadiaSelecionada = $estadia;
                break;
            }
        }
    }

    if ($estadiaSelecionada === null) {
        $pagina = '404';
        http_response_code(404);
    }
}

// JSON: resposta antes de qualquer HTML.
if (($_GET['formato'] ?? '') === 'json') {
    header('Content-Type: application/json; charset=utf-8');

    if ($pagina === '404') {
        $dadosJson = ['erro' => 'Página ou estadia não encontrada.'];
    } elseif ($erro !== '') {
        $dadosJson = ['erro' => $erro];
    } elseif ($estadiaSelecionada !== null) {
        $dadosJson = $estadiaSelecionada->paraArray();
    } else {
        $dadosJson = $estacionamento->paraArray();
    }

    echo json_encode(
        $dadosJson,
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
    );
    exit;
}

// Dados para a apresentação.
$titulos = [
    'inicio' => 'Visão geral',
    'estadias' => 'Estadias registradas',
    'detalhe' => 'Detalhe da estadia',
    'cadastro' => 'Cadastrar estadia',
    '404' => 'Não encontrado',
];

$titulo = $titulos[$pagina];
$estadias = $estacionamento->getEstadias();
$vagasLivres = $estacionamento->getCapacidade() - count($estadias);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= escapar($titulo) ?> | Estacionamento</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<header class="cabecalho">
    <div class="container">
        <p class="identificacao">PROGRAMAÇÃO WEB I</p>
        <h1><?= escapar($estacionamento->getNome()) ?></h1>

        <nav aria-label="Menu principal">
            <a href="?pagina=inicio">Início</a>
            <a href="?pagina=estadias">Estadias</a>
            <a href="?pagina=cadastro">Cadastrar</a>
            <a href="?formato=json">JSON</a>
        </nav>
    </div>
</header>

<main class="container">
    <h2><?= escapar($titulo) ?></h2>

    <p class="aviso">
        Versão demonstrativa: os dados são recriados a cada requisição.
        Cada estadia registrada ocupa uma vaga; esta versão não registra saídas.
    </p>

    <?php if ($pagina === 'inicio'): ?>
        <section class="indicadores" aria-label="Resumo do estacionamento">
            <article class="cartao">
                <h3>Capacidade</h3>
                <strong>
                    <?= escapar((string) $estacionamento->getCapacidade()) ?>
                </strong>
            </article>

            <article class="cartao">
                <h3>Vagas livres</h3>
                <strong><?= escapar((string) $vagasLivres) ?></strong>
            </article>

            <article class="cartao">
                <h3>Valor das estadias</h3>
                <strong>
                    <?= escapar(moeda($estacionamento->calcularTotal())) ?>
                </strong>
            </article>
        </section>

        <section class="cartao">
            <h3>Como funciona</h3>
            <p>
                Informe o veículo e a duração em horas inteiras.
                Carros custam R$ 5,00 por hora e motos, R$ 3,00 por hora.
            </p>
            <a class="botao" href="?pagina=cadastro">Cadastrar estadia</a>
        </section>

    <?php elseif ($pagina === 'estadias'): ?>
        <div class="tabela-container">
            <table>
                <caption>Veículos e valores das estadias registradas</caption>
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Placa</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Horas</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Detalhe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estadias as $estadia): ?>
                        <tr>
                            <td><?= escapar((string) $estadia->getId()) ?></td>
                            <td>
                                <?= escapar($estadia->getVeiculo()->getPlaca()) ?>
                            </td>
                            <td>
                                <?= escapar($estadia->getVeiculo()->getModelo()) ?>
                            </td>
                            <td><?= escapar((string) $estadia->getHoras()) ?></td>
                            <td><?= escapar(moeda($estadia->calcularValor())) ?></td>
                            <td>
                                <a href="?pagina=detalhe&amp;id=<?= escapar((string) $estadia->getId()) ?>">
                                    Ver estadia
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php elseif ($pagina === 'detalhe'): ?>
        <article class="cartao">
            <h3>
                Estadia <?= escapar((string) $estadiaSelecionada->getId()) ?>
            </h3>
            <dl>
                <dt>Placa</dt>
                <dd>
                    <?= escapar($estadiaSelecionada->getVeiculo()->getPlaca()) ?>
                </dd>

                <dt>Modelo</dt>
                <dd>
                    <?= escapar($estadiaSelecionada->getVeiculo()->getModelo()) ?>
                </dd>

                <dt>Horas</dt>
                <dd><?= escapar((string) $estadiaSelecionada->getHoras()) ?></dd>

                <dt>Tarifa por hora</dt>
                <dd>
                    <?= escapar(moeda(
                        $estadiaSelecionada->getVeiculo()->calcularTarifaHora()
                    )) ?>
                </dd>

                <dt>Valor da estadia</dt>
                <dd>
                    <?= escapar(moeda($estadiaSelecionada->calcularValor())) ?>
                </dd>
            </dl>
        </article>

    <?php elseif ($pagina === 'cadastro'): ?>
        <?php if ($erro !== ''): ?>
            <p class="mensagem erro" role="alert"><?= escapar($erro) ?></p>
        <?php endif; ?>

        <?php if ($sucesso !== ''): ?>
            <section class="mensagem sucesso" role="status">
                <h3><?= escapar($sucesso) ?></h3>
                <p>
                    Placa:
                    <?= escapar($estadiaCriada->getVeiculo()->getPlaca()) ?>
                    — Valor:
                    <?= escapar(moeda($estadiaCriada->calcularValor())) ?>
                </p>
                <p>Este cadastro ainda não será mantido ao navegar ou atualizar.</p>
            </section>
        <?php endif; ?>

        <form method="post" action="?pagina=cadastro" class="cartao">
            <label for="tipo">Tipo de veículo</label>
            <select id="tipo" name="tipo" required>
                <option value="carro"
                    <?= $campos['tipo'] === 'carro' ? 'selected' : '' ?>>
                    Carro — R$ 5,00/h
                </option>
                <option value="moto"
                    <?= $campos['tipo'] === 'moto' ? 'selected' : '' ?>>
                    Moto — R$ 3,00/h
                </option>
            </select>

            <label for="placa">Placa</label>
            <input
                id="placa"
                name="placa"
                type="text"
                value="<?= escapar($campos['placa']) ?>"
                required
            >

            <label for="modelo">Modelo</label>
            <input
                id="modelo"
                name="modelo"
                type="text"
                value="<?= escapar($campos['modelo']) ?>"
            >

            <label for="horas">Duração em horas inteiras</label>
            <input
                id="horas"
                name="horas"
                type="number"
                step="1"
                value="<?= escapar($campos['horas']) ?>"
                required
            >

            <p class="ajuda">
                A duração deve ser positiva. Para testar a regra no servidor,
                tente enviar zero.
            </p>

            <button type="submit">Cadastrar estadia</button>
        </form>

    <?php else: ?>
        <section class="cartao">
            <h3>404 — Página ou estadia não encontrada</h3>
            <p>Confira o endereço ou retorne à página inicial.</p>
            <a class="botao" href="?pagina=inicio">Voltar ao início</a>
        </section>
    <?php endif; ?>
</main>

<footer class="container rodape">
    <p>Sistema orientado a objetos em PHP — Tema 08: Estacionamento.</p>
</footer>
</body>
</html>