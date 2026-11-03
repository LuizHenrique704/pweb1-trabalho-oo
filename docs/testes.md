# Testes

## Roteiro de testes no terminal

Comando: `php teste.php`

```text
OK: estacionamento criado vazio e com total zero.
OK, recusado: Já existe uma estadia com esse identificador.
OK: duas estadias registradas; total de R$ 21,00.
OK, recusado: O estacionamento está lotado.
OK: conversão inclui estacionamento, estadias e veículos.
OK, capacidade 0 recusada: A capacidade deve ser maior que zero.
OK, capacidade -1 recusada: A capacidade deve ser maior que zero.
OK, nome vazio recusado: O nome do estacionamento não pode estar vazio.
[luiz@luiz-cachy pweb1-trabalho-oo]$ php -l teste.php
php teste.php
No syntax errors detected in teste.php
=== 1. Veículos e encapsulamento ===
OK: Veiculo aceita dados válidos e aplica a tarifa padrão.
OK: Getters retornam placas e modelos padronizados.
OK: Veiculo recusa placa '' — A placa não pode estar vazia.
OK: Veiculo recusa placa '   ' — A placa não pode estar vazia.
OK: Carro recusa placa '' — A placa não pode estar vazia.
OK: Carro recusa placa '   ' — A placa não pode estar vazia.
OK: Moto recusa placa '' — A placa não pode estar vazia.
OK: Moto recusa placa '   ' — A placa não pode estar vazia.

=== 2. Herança e polimorfismo ===
OK: Carro responde ao mesmo método com tarifa de R$ 5,00
OK: Moto responde ao mesmo método com tarifa de R$ 3,00

=== 3. Estadias e cálculo individual ===
OK: Estadia guarda identificador, veículo e horas.
OK: Carro por 3 horas custa R$ 15,00; moto por 2 horas custa R$ 6,00.
OK: Recusa duração de 0 horas — A duração da estadia deve ser maior que zero.
OK: Recusa duração de -2 horas — A duração da estadia deve ser maior que zero.
OK: Recusa identificador 0 — O identificador da estadia deve ser maior que zero.
OK: Recusa identificador -1 — O identificador da estadia deve ser maior que zero.

=== 4. Estacionamento e composição ===
OK: Estacionamento válido começa vazio e com total zero.
OK: Aceita a primeira estadia enquanto há vaga.
OK: Recusa identificador repetido mesmo havendo vaga — Já existe uma estadia com esse identificador.
OK: Recusa por identificador repetido preserva os dados.
OK: Aceita identificador diferente na última vaga e soma R$ 21,00.
OK: Recusa nova estadia quando a capacidade foi atingida — O estacionamento está lotado.
OK: Recusa por lotação preserva os dados.
OK: Recusa nome '' — O nome do estacionamento não pode estar vazio.
OK: Recusa nome '   ' — O nome do estacionamento não pode estar vazio.
OK: Recusa capacidade 0 — A capacidade deve ser maior que zero.
OK: Recusa capacidade -1 — A capacidade deve ser maior que zero.

=== 5. Conversão para array e JSON ===
OK: Veiculo converte seus dados para array.
OK: Classes filhas incluem tipo e tarifa correta no array.
OK: Composição inteira é convertida em arrays com os valores esperados.
OK: JSON gerado pode ser lido preservando tipo do veículo e total.

SUCESSO: todos os testes do núcleo passaram.

```

**Explicação de cada linha:**

| Grupo | O que as saídas comprovam |
|---|---|
| Veículos e encapsulamento | Dados válidos são aceitos, getters retornam os dados padronizados e placas vazias são recusadas na mãe e nas filhas. |
| Herança e polimorfismo | Carro e Moto são veículos e respondem à mesma chamada com tarifas diferentes. |
| Estadias | Identificador, veículo e horas são armazenados; os cálculos são corretos; identificadores e durações não positivos são recusados. |
| Estacionamento e composição | A lista começa vazia, recebe estadias e calcula o total; identificadores repetidos e lotação são recusados sem alterar os dados. Nome vazio e capacidade não positiva também são recusados. |
| Array e JSON | Os objetos são convertidos em arrays, incluindo os objetos relacionados; o JSON pode ser gerado e decodificado preservando os dados verificados. |

## Camada web

**Uma página que existe:** `curl.exe -i "http://localhost:8000/?pagina=..."` (cole só até o início do HTML)

```
HTTP/1.1 200 OK
Host: localhost:8000
Date: Tue, 03 Nov 2026 10:53:40 GMT
Connection: close
X-Powered-By: PHP/8.5.12
Content-Type: text/html; charset=utf-8
```

**Uma página que não existe:** `curl.exe -i "http://localhost:8000/?pagina=inexistente"`

```
HTTP/1.1 404 Not Found
Host: localhost:8000
Date: Tue, 03 Nov 2026 10:54:12 GMT
Connection: close
X-Powered-By: PHP/8.5.12
Content-Type: text/html; charset=utf-8
```

**A rota JSON:** `curl.exe -i "http://localhost:8000/?formato=json"`

```
HTTP/1.1 200 OK
Host: localhost:8000
Date: Tue, 03 Nov 2026 10:58:32 GMT
Connection: close
X-Powered-By: PHP/8.5.12
Content-Type: application/json; charset=utf-8
```

**O formulário com um dado inválido:** print da página com a mensagem de recusa e o log do servidor.

```
HTTP/1.1 422 Unknown Status Code
Host: localhost:8000
Date: Tue, 03 Nov 2026 11:00:28 GMT
Connection: close
X-Powered-By: PHP/8.5.12
Content-Type: text/html; charset=utf-8
```

## Persistência em JSON

- Cadastrei uma estadia válida e conferi sua presença na lista.
- Atualizei a página e o registro permaneceu.
- Reiniciei o servidor e o registro permaneceu.
- Enviei duração zero e confirmei que nenhum registro foi acrescentado.

Os dados ficam em dados/estacionamento.json, fora da pasta pública.
O arquivo é criado automaticamente e ignorado pelo Git.

## Escape de HTML

Informei <strong>Teste</strong> no modelo do veículo.
As tags apareceram como texto, sem aplicar negrito.