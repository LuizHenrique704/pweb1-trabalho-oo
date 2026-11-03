# Diário de uso de LLM

Utilizei o ChatGPT como apoio para interpretar os requisitos, planejar
o sistema, entender o código, executar testes e resolver dificuldades.

## Interação 1 — Escolha do tema

- **Ferramenta e data:** ChatGPT — 02/11/2026.
- **Prompt:**

```text
preciso fazer esse trabalho o quanto antes, vamos fazer um passo a passo de tudo que devo fazer para entregar o trabalho da exata forma que o pdf pede
```

- **Resumo da resposta:** A ferramenta leu o PDF e organizou as etapas:
  escolha do tema, repositório, modelagem, classes, testes, camada web,
  documentação, relatório, vídeo e apresentação. Também destacou os
  requisitos OO1 a OO8 e W1 a W5.
- **O que aceitei, modifiquei ou rejeitei, e por quê:** Aceitei utilizar
  a sequência como roteiro, pois ela ajudava a acompanhar as exigências
  e mostrava que a entrega incluía outras atividades além da programação.
## Interação 2 — Diagnóstico de arquivo não encontrado

- **Ferramenta e data:** ChatGPT — 02/11/2026.
- **Prompt:** Enviei a saída do terminal após executar o teste.
  Trecho da mensagem enviada:

```text
PHP Warning: require_once(/home/luiz/Documentos/GitHub/pweb1-trabalho-oo/src/Carro.php): Failed to open stream: No such file or directory
```

- **Resumo da resposta:** A ferramenta explicou que o PHP não tinha
  encontrado o arquivo necessário e orientou conferir a branch, o merge
  das classes anteriores, a pasta src e as letras maiúsculas no nome.
- **O que aceitei, modifiquei ou rejeitei, e por quê:** Aceitei verificar
  os arquivos e a branch antes de alterar a lógica das classes.
  Depois de resolver a disponibilidade dos arquivos, executei novamente
  os testes. Aprendi que uma falha de require_once pode impedir os testes
  antes mesmo de qualquer regra de negócio ser executada.

## Interação 3 — Sugestão de interface que não consegui utilizar

- **Ferramenta e data:** ChatGPT — 02/11/2026.
- **Contexto:** Eu precisava criar a tag v0.2-nucleo. A ferramenta
  orientou clicar com o botão direito em um commit no GitHub Desktop
  e escolher Create Tag.
- **Prompt:**

```text
não estou achando isso
```

- **Resumo da resposta:** A ferramenta apresentou uma alternativa:
  criar a tag localmente pelo terminal com git tag v0.2-nucleo e
  conferir com git tag --list, mantendo o envio pelo GitHub Desktop.
- **O que aceitei, modifiquei ou rejeitei, e por quê:** Não consegui
  utilizar a opção de interface indicada inicialmente. Informei essa
  dificuldade e utilizei a alternativa pelo terminal, que funcionou.
  Aprendi que criar uma tag local não exige autenticação no GitHub.

## Interação 4 — Persistência dos cadastros

- **Ferramenta e data:** ChatGPT — 02/11/2026.
- **Prompt:**

```text
me mande o index.php por completo corrigido
```

- **Resumo da resposta:** A ferramenta reuniu em uma versão completa
  do index.php as funções auxiliares, leitura e gravação do JSON,
  reconstrução dos objetos, tratamento do formulário, rotas e HTML.
  Também incluiu redirecionamento após cadastro válido e tratamento
  de falhas de acesso aos dados.
- **O que aceitei, modifiquei ou rejeitei, e por quê:** Utilizei a
  versão consolidada para evitar erros ao encaixar trechos separados.
  Mantive a lógica de processamento antes do HTML e os caminhos
  relativos com __DIR__. Depois, executei o sistema e conferi
  o funcionamento do cadastro no navegador.
  o cadastro ao atualizar a página.

## Interação 5 — Exibição segura de HTML

- **Ferramenta e data:** ChatGPT — 02/11/2026.
- **Prompt:**

```text
ok, ele esta cadastrando mas o modfelo não esta indo em negrito, mas isso é o correto
```

- **Resumo da resposta:** A ferramenta confirmou que o conteúdo
  <strong>Teste</strong> deveria aparecer como texto, sem aplicar
  negrito, porque os dados eram exibidos com htmlspecialchars.
- **O que aceitei, modifiquei ou rejeitei, e por quê:** Mantive o escape
  de HTML. Entendi que o campo deve exibir o conteúdo informado pelo
  usuário sem interpretá-lo como marcação da página, atendendo ao
  requisito W4.

## Sugestão inadequada identificada e corrigida

Na interação 3, a ferramenta indicou um caminho da interface do
GitHub Desktop que não consegui localizar na minha instalação.

Percebi que a orientação não estava sendo suficiente para executar
a tarefa e comuniquei a dificuldade. A solução foi criar a tag
localmente pelo terminal e continuar usando o Desktop para o envio.

Esse caso mostrou que as instruções da LLM precisam ser verificadas
no ambiente utilizado e que uma dificuldade deve ser investigada,
em vez de presumir que toda orientação está correta.

## Reflexão

O ChatGPT ajudou a dividir o trabalho em etapas e a relacionar os
conceitos de orientação a objetos com exemplos do estacionamento.

Os testes e as mensagens de erro foram importantes para verificar
as sugestões. Também precisei conferir os arquivos, as branches
e as opções disponíveis no meu ambiente.

Para a apresentação, preciso compreender o funcionamento das classes,
das validações, das rotas e da persistência, pois sou responsável
pelo código entregue.