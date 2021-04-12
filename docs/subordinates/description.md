# Descrição

A solicitação de cadastro deve ser realizada através de uma requisição pelo Master informando os dados do subordinado.

Para a definição de acordos entre o Master e seus subordinados, o Split de Pagamentos dispõe de duas possibilidades:

 - É possível definir a porcentagem do MDR cobrado por transação por Arranjos de Pagamento e intervalo de parcelas.

 - Caso o Master não queira definir a porcetagem do MDR para cada Arranjo de Pagamento e intervalor de parcelas, o Split de Pagamentos irá replicar os mesmos acordos do Master com a Subadquirente, para o Master com o Subordinado. Dessa forma, o Master deverá informar apenas um MDR único, que erá aplicado para todos os acordos. Exemplo:

| ACORDO SUBADQUIRENTE - MASTER | Visa | Master | Elo | Diners | Amex | Hiper |
| - | - | - | - | - | - | - |
| Débito | 2.00% | 2.00% | 2.00% | - | - | - |
| Crédito a Vista | 2.50% | 2.50% | 2.50% | 2.50% | 2.50% | 2.50%
| Crédito 2x a 6x | 3.00% | 3.00% | 3.00% | 3.00% | 3.00% | 3.00%
| Crédito 7x a 12x | 3.50% | 3.50% | 3.50% | 3.50% | 3.50% | 3.50%

<br>

| ACORDO MASTER - SUBORDINADO | Visa | Master | Elo | Diners | Amex | Hiper |
| - | - | - | - | - | - | - |
| Débito | 4.0% | 4.0% | 4.0% | - | - | - |
| Crédito a Vista | 4.0% | 4.0% | 4.0% | 4.0% | 4.0% | 4.0%
| Crédito 2x a 6x | 4.0% | 4.0% | 4.0% | 4.0% | 4.0% | 4.0%
| Crédito 7x a 12x | 4.0% | 4.0% | 4.0% | 4.0% | 4.0% | 4.0%