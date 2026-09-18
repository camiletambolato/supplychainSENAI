<?php
declare(strict_types=1);

// Dados das cotações
$cotacoesAbertas = [
    [
        'id' => 1,
        'fornecedor' => 'Eletro Parts Ltda',
        'email' => 'contato@eletroparts.com.br',
        'categoria' => 'Eletrônicos',
        'descricao' => 'Bobinas de cobre 2mm - 100kg',
        'valor' => 4500.00,
        'prazo' => 7,
        'condicao' => '30 dias',
        'data_abertura' => '2026-09-10'
    ],
    [
        'id' => 2,
        'fornecedor' => 'Peças Mecânicas do Brasil',
        'email' => 'vendas@pecasmec.com.br',
        'categoria' => 'Mecânica',
        'descricao' => 'Rolamentos de esferas NSK 6204',
        'valor' => 1250.50,
        'prazo' => 5,
        'condicao' => 'À Vista',
        'data_abertura' => '2026-09-12'
    ],
    [
        'id' => 3,
        'fornecedor' => 'Supply Express',
        'email' => 'compras@supplyexpress.com.br',
        'categoria' => 'Consumíveis',
        'descricao' => 'Óleo hidráulico ISO 46 - 200L',
        'valor' => 2100.00,
        'prazo' => 3,
        'condicao' => '60 dias',
        'data_abertura' => '2026-09-15'
    ],
    [
        'id' => 4,
        'fornecedor' => 'Serviços Técnicos SENAI',
        'email' => 'servicos@senaigeral.com.br',
        'categoria' => 'Serviços',
        'descricao' => 'Manutenção preventiva - 40h',
        'valor' => 3200.00,
        'prazo' => 1,
        'condicao' => '30 dias',
        'data_abertura' => '2026-09-14'
    ],
    [
        'id' => 5,
        'fornecedor' => 'Eletrônicos Avançados SA',
        'email' => 'vendas@eletravanc.com.br',
        'categoria' => 'Eletrônicos',
        'descricao' => 'Transformador 10kVA 220/110',
        'valor' => 8750.00,
        'prazo' => 15,
        'condicao' => '90 dias',
        'data_abertura' => '2026-09-11'
    ],
    [
        'id' => 6,
        'fornecedor' => 'Plásticos Industriais',
        'email' => 'venda@plasticosindustriais.com.br',
        'categoria' => 'Consumíveis',
        'descricao' => 'Tubos PVC 75mm - 50 metros',
        'valor' => 650.00,
        'prazo' => 2,
        'condicao' => 'À Vista',
        'data_abertura' => '2026-09-13'
    ],
    [
        'id' => 7,
        'fornecedor' => 'Automação Industrial Plus',
        'email' => 'suporte@autoplus.com.br',
        'categoria' => 'Eletrônicos',
        'descricao' => 'CLP Siemens S7-1200',
        'valor' => 15800.00,
        'prazo' => 21,
        'condicao' => '60 dias',
        'data_abertura' => '2026-09-09'
    ],
    [
        'id' => 8,
        'fornecedor' => 'Consultoria Técnica Premium',
        'email' => 'info@consultoriatech.com.br',
        'categoria' => 'Serviços',
        'descricao' => 'Auditoria de processos - 3 dias',
        'valor' => 5600.00,
        'prazo' => 5,
        'condicao' => '30 dias',
        'data_abertura' => '2026-09-08'
    ]
];

const CATEGORIAS_PERMITIDAS = [
    'Eletrônicos',
    'Mecânica',
    'Consumíveis',
    'Serviços'
];

const CONDICOES_PAGAMENTO = [
    'À Vista',
    '30 dias',
    '60 dias',
    '90 dias'
];


/**
 * Valida os dados da nova cotação.
 */
function validarCotacao(array $dados): array
{
    $erros = [];

    if (strlen(trim($dados['nome_fornecedor'] ?? '')) < 5) {
        $erros['nome_fornecedor'] =
            'O nome do fornecedor deve ter no mínimo 5 caracteres.';
    }

    if (!filter_var($dados['email_fornecedor'] ?? '', FILTER_VALIDATE_EMAIL)) {
        $erros['email_fornecedor'] =
            'Informe um e-mail válido.';
    }

    if (!in_array(
        $dados['categoria_produto'] ?? '',
        CATEGORIAS_PERMITIDAS,
        true
    )) {
        $erros['categoria_produto'] =
            'Selecione uma categoria válida.';
    }

    if (strlen(trim($dados['descricao_item'] ?? '')) < 10) {
        $erros['descricao_item'] =
            'A descrição deve ter no mínimo 10 caracteres.';
    }

    // Aceita R$ ou somente números
    $valor = $dados['valor_cotacao'] ?? '';
    $valor = str_replace('R$', '', $valor);
    $valor = str_replace(' ', '', $valor);
    $valor = str_replace('.', '', $valor);
    $valor = str_replace(',', '.', $valor);

    if (!is_numeric($valor) || (float)$valor <= 0) {
        $erros['valor_cotacao'] =
            'Informe um valor positivo em R$.';
    }

    $prazo = filter_var(
        $dados['prazo_entrega_dias'] ?? '',
        FILTER_VALIDATE_INT
    );

    if ($prazo === false || $prazo < 1 || $prazo > 60) {
        $erros['prazo_entrega_dias'] =
            'O prazo deve estar entre 1 e 60 dias.';
    }

    if (!in_array(
        $dados['condicoes_pagamento'] ?? '',
        CONDICOES_PAGAMENTO,
        true
    )) {
        $erros['condicoes_pagamento'] =
            'Selecione uma condição de pagamento válida.';
    }

    return $erros;
}


/**
 * Filtra as cotações por fornecedor e valor máximo.
 */
function filtrarCotacoes(
    array $cotacoes,
    string $fornecedor = '',
    ?float $valorMaximo = null
): array {

    return array_filter($cotacoes, function ($cotacao) use (
        $fornecedor,
        $valorMaximo
    ) {

        if (
            $fornecedor != '' &&
            stripos($cotacao['fornecedor'], $fornecedor) === false
        ) {
            return false;
        }

        if (
            $valorMaximo !== null &&
            $cotacao['valor'] > $valorMaximo
        ) {
            return false;
        }

        return true;
    });
}


// Variáveis do formulário
$dados = [
    'nome_fornecedor' => '',
    'email_fornecedor' => '',
    'categoria_produto' => '',
    'descricao_item' => '',
    'valor_cotacao' => '',
    'prazo_entrega_dias' => '',
    'condicoes_pagamento' => ''
];

$erros = [];
$mensagem = '';


// GET - Filtro
$filtroFornecedor = $_GET['fornecedor'] ?? '';
$filtroValor = $_GET['valor_max'] ?? '';

if ($filtroValor != '') {

    $valorFiltro = str_replace('.', '', $filtroValor);
    $valorFiltro = str_replace(',', '.', $valorFiltro);

    if (is_numeric($valorFiltro)) {
        $filtroValorNumero = (float)$valorFiltro;
    } else {
        $filtroValorNumero = null;
    }

} else {
    $filtroValorNumero = null;
}


// POST - Nova cotação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dados['nome_fornecedor'] =
        trim($_POST['nome_fornecedor'] ?? '');

    $dados['email_fornecedor'] =
        trim($_POST['email_fornecedor'] ?? '');

    $dados['categoria_produto'] =
        $_POST['categoria_produto'] ?? '';

    $dados['descricao_item'] =
        trim($_POST['descricao_item'] ?? '');

    $dados['valor_cotacao'] =
        trim($_POST['valor_cotacao'] ?? '');

    $dados['prazo_entrega_dias'] =
        $_POST['prazo_entrega_dias'] ?? '';

    $dados['condicoes_pagamento'] =
        $_POST['condicoes_pagamento'] ?? '';


    $erros = validarCotacao($dados);


    if (empty($erros)) {

        $valor = $dados['valor_cotacao'];

        $valor = str_replace('R$', '', $valor);
        $valor = str_replace(' ', '', $valor);
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);

        $novaCotacao = [
            'id' => count($cotacoesAbertas) + 1,
            'fornecedor' => $dados['nome_fornecedor'],
            'email' => $dados['email_fornecedor'],
            'categoria' => $dados['categoria_produto'],
            'descricao' => $dados['descricao_item'],
            'valor' => (float)$valor,
            'prazo' => (int)$dados['prazo_entrega_dias'],
            'condicao' => $dados['condicoes_pagamento'],
            'data_abertura' => date('Y-m-d')
        ];

        $cotacoesAbertas[] = $novaCotacao;

        $mensagem = 'Cotação cadastrada com sucesso!';

        // Limpa o formulário depois do cadastro
        $dados = [
            'nome_fornecedor' => '',
            'email_fornecedor' => '',
            'categoria_produto' => '',
            'descricao_item' => '',
            'valor_cotacao' => '',
            'prazo_entrega_dias' => '',
            'condicoes_pagamento' => ''
        ];
    }
}


// Aplicar filtros
$cotacoesFiltradas = filtrarCotacoes(
    $cotacoesAbertas,
    $filtroFornecedor,
    $filtroValorNumero
);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>SupplyChain SENAI</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #FFEBED; /* Lavender Blush */
            color: #3e828e; /* Dark Amethyst */
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        header {
            background: #F6B6B7; /* Powder Blush */
            color: #3e828e; /* Dark Amethyst */
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px #34145d14;
        }

        header h1 {
            margin: 0 0 5px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #F6B6B7;
            box-shadow: 0 2px 8px #27153d0f;
        }

        h2 {
            color: #3e828e; /* Dark Amethyst */
            border-bottom: 2px solid #FFEBED;
            padding-bottom: 8px;
            margin-top: 0;
        }

        .filtros {
            display: flex;
            gap: 15px;
            align-items: flex-end;
        }

        .campo {
            flex: 1;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #3e828e;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #F6B6B7; /* Powder Blush */
            border-radius: 5px;
            font-size: 15px;
            color: #3e828e;
            outline: none;
            background-color: #ffffff;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #27153D;
            box-shadow: 0 0 0 2px rgba(246, 182, 183, 0.5);
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        button {
            background: #F6B6B7; /* Powder Blush */
            color: #27153D; /* Dark Amethyst */
            font-weight: bold;
            border: none;
            padding: 11px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover {
            background: #b18384;
            color: #FFEBED;
        }

        .limpar {
            background: #FFEBED; /* Lavender Blush */
            color: #27153D;
            font-weight: bold;
            text-decoration: none;
            padding: 11px 20px;
            border-radius: 5px;
            border: 1px solid #F6B6B7;
            display: inline-block;
            text-align: center;
        }

        .limpar:hover {
            background: #f0c2c3;
        }

        .acoes {
            display: flex;
            gap: 10px;
        }

        .tabela {
            overflow-x: auto;
        }

        .tabela-cotacoes {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 850px;
        }

        .tabela-cotacoes th {
            background: #3e828e; /* Dark Amethyst */
            color: #FFEBED; /* Lavender Blush */
            padding: 12px;
            text-align: left;
        }

        .tabela-cotacoes td {
            padding: 12px;
            border-bottom: 1px solid #FFEBED;
        }

        .tabela-cotacoes tr:hover {
            background: #FFEBED; /* Lavender Blush */
        }

        .valor {
            color: #3e828e;
            font-weight: bold;
        }

        .formulario {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .completo {
            grid-column: 1 / 3;
        }

        .erro {
            color: #b3002d;
            font-size: 14px;
            margin-top: 5px;
            font-weight: bold;
        }

        .sucesso {
            background: #FFEBED;
            color: #297e72;
            border: 1px solid #F6B6B7;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        @media (max-width: 700px) {

            .filtros {
                flex-direction: column;
            }

            .filtros .campo,
            .acoes {
                width: 100%;
            }

            .acoes {
                flex-direction: column;
            }

            .formulario {
                grid-template-columns: 1fr;
            }

            .completo {
                grid-column: auto;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <header>

        <h1>SupplyChain SENAI</h1>

        <p>
            Autoatendimento e Cotação de Fornecedores
        </p>

    </header>


    <!-- FILTRO DE COTAÇÕES -->

    <div class="card">

        <h2>Filtro de Cotações</h2>

        <form method="GET">

            <div class="filtros">

                <div class="campo">

                    <label for="fornecedor">
                        Nome do fornecedor
                    </label>

                    <input
                        type="text"
                        id="fornecedor"
                        name="fornecedor"
                        value="<?= htmlspecialchars(
                            $filtroFornecedor
                        ) ?>"
                    >

                </div>


                <div class="campo">

                    <label for="valor_max">
                        Valor máximo
                    </label>

                    <input
                        type="text"
                        id="valor_max"
                        name="valor_max"
                        placeholder="Ex.: 5000,00"
                        value="<?= htmlspecialchars(
                            $filtroValor
                        ) ?>"
                    >

                </div>


                <div class="acoes">

                    <button type="submit">
                        Filtrar
                    </button>

                    <a
                        href="supplychain.php"
                        class="limpar"
                    >
                        Limpar
                    </a>

                </div>

            </div>

        </form>


        <div class="tabela">

            <table class="tabela-cotacoes">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Fornecedor</th>
                        <th>Categoria</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Prazo</th>
                        <th>Pagamento</th>
                        <th>Data</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty($cotacoesFiltradas)): ?>

                    <tr>
                        <td colspan="8">
                            Nenhuma cotação encontrada.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach (
                        $cotacoesFiltradas as $cotacao
                    ): ?>

                        <tr>

                            <td>
                                <?= $cotacao['id'] ?>
                            </td>

                            <td>

                                <?= htmlspecialchars(
                                    $cotacao['fornecedor']
                                ) ?>

                                <br>

                                <small>
                                    <?= htmlspecialchars(
                                        $cotacao['email']
                                    ) ?>
                                </small>

                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $cotacao['categoria']
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $cotacao['descricao']
                                ) ?>
                            </td>

                            <td class="valor">

                                R$
                                <?= number_format(
                                    $cotacao['valor'],
                                    2,
                                    ',',
                                    '.'
                                ) ?>

                            </td>

                            <td>
                                <?= $cotacao['prazo'] ?> dias
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $cotacao['condicao']
                                ) ?>
                            </td>

                            <td>

                                <?= date(
                                    'd/m/Y',
                                    strtotime(
                                        $cotacao['data_abertura']
                                    )
                                ) ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>


    <!-- NOVA COTAÇÃO -->

    <div class="card">

        <h2>Nova Cotação</h2>


        <?php if ($mensagem != ''): ?>

            <div class="sucesso">
                <?= htmlspecialchars($mensagem) ?>
            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="formulario">


                <div class="campo">

                    <label for="nome_fornecedor">
                        Nome do fornecedor
                    </label>

                    <input
                        type="text"
                        id="nome_fornecedor"
                        name="nome_fornecedor"
                        value="<?= htmlspecialchars(
                            $dados['nome_fornecedor']
                        ) ?>"
                        required
                    >

                    <?php if (
                        isset($erros['nome_fornecedor'])
                    ): ?>

                        <div class="erro">
                            <?= htmlspecialchars(
                                $erros['nome_fornecedor']
                            ) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <div class="campo">

                    <label for="email_fornecedor">
                        E-mail
                    </label>

                    <input
                        type="email"
                        id="email_fornecedor"
                        name="email_fornecedor"
                        value="<?= htmlspecialchars(
                            $dados['email_fornecedor']
                        ) ?>"
                        required
                    >

                    <?php if (
                        isset($erros['email_fornecedor'])
                    ): ?>

                        <div class="erro">
                            <?= htmlspecialchars(
                                $erros['email_fornecedor']
                            ) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <div class="campo">

                    <label for="categoria_produto">
                        Categoria
                    </label>

                    <select
                        id="categoria_produto"
                        name="categoria_produto"
                        required
                    >

                        <option value="">
                            Selecione
                        </option>

                        <?php foreach (
                            CATEGORIAS_PERMITIDAS as $categoria
                        ): ?>

                            <option
                                value="<?= htmlspecialchars(
                                    $categoria
                                ) ?>"
                                <?= $dados[
                                    'categoria_produto'
                                ] == $categoria
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= htmlspecialchars($categoria) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (
                        isset($erros['categoria_produto'])
                    ): ?>

                        <div class="erro">
                            <?= htmlspecialchars(
                                $erros['categoria_produto']
                            ) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <div class="campo">

                    <label for="valor_cotacao">
                        Valor da cotação
                    </label>

                    <input
                        type="text"
                        id="valor_cotacao"
                        name="valor_cotacao"
                        placeholder="Ex.: R$ 4.500,00"
                        value="<?= htmlspecialchars(
                            $dados['valor_cotacao']
                        ) ?>"
                        required
                    >

                    <?php if (
                        isset($erros['valor_cotacao'])
                    ): ?>

                        <div class="erro">
                            <?= htmlspecialchars(
                                $erros['valor_cotacao']
                            ) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <div class="campo completo">

                    <label for="descricao_item">
                        Descrição do item
                    </label>

                    <textarea
                        id="descricao_item"
                        name="descricao_item"
                        required
                    ><?= htmlspecialchars(
                        $dados['descricao_item']
                    ) ?></textarea>

                    <?php if (
                        isset($erros['descricao_item'])
                    ): ?>

                        <div class="erro">
                            <?= htmlspecialchars(
                                $erros['descricao_item']
                            ) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <div class="campo">

                    <label for="prazo_entrega_dias">
                        Prazo de entrega (dias)
                    </label>

                    <input
                        type="number"
                        id="prazo_entrega_dias"
                        name="prazo_entrega_dias"
                        min="1"
                        max="60"
                        value="<?= htmlspecialchars(
                            (string)$dados[
                                'prazo_entrega_dias'
                            ]
                        ) ?>"
                        required
                    >

                    <?php if (
                        isset($erros['prazo_entrega_dias'])
                    ): ?>

                        <div class="erro">
                            <?= htmlspecialchars(
                                $erros['prazo_entrega_dias']
                            ) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <div class="campo">

                    <label for="condicoes_pagamento">
                        Condições de pagamento
                    </label>

                    <select
                        id="condicoes_pagamento"
                        name="condicoes_pagamento"
                        required
                    >

                        <option value="">
                            Selecione
                        </option>

                        <?php foreach (
                            CONDICOES_PAGAMENTO as $condicao
                        ): ?>

                            <option
                                value="<?= htmlspecialchars(
                                    $condicao
                                ) ?>"
                                <?= $dados[
                                    'condicoes_pagamento'
                                ] == $condicao
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= htmlspecialchars($condicao) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (
                        isset($erros['condicoes_pagamento'])
                    ): ?>

                        <div class="erro">
                            <?= htmlspecialchars(
                                $erros['condicoes_pagamento']
                            ) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <div class="completo">

                    <button type="submit">
                        Cadastrar Cotação
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

</body>

</html>