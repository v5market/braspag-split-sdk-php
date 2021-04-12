# Pós-Transacional

Informa os subordinados após a captura da transação.

É possível informar vários subordinado na transação.

O somatório dos valores de cada subordinado deverá ser igual ao total da transação capturada.

> Existe um prazo limite para realizar o split.

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\Sale\SplitPayments;
use Braspag\Split\Request\Sale as RequestSale;

/**
 * Adiciona os subordinados e os dados do acordo com o master:
 * - 0000000-000-0000-0000-00000000000: É o Merchant ID do subordinado.
 * - 5: Percentual (MDR) referente à comissão do master, que será descontado da parte do subordinado.
 * - 0: Tarifa fixa em centavos que será descontaddo da parte do subordinado.
 */

/* Subordinado 1 */
$splitOne = new SplitPayments;
$splitOne->setSubordinateMerchantId('00000000-0000-0000-0000-000000000000');
$splitOne->setAmount(3000); /* Valor em centavos */
$splitOne->setFares(5, 0);

/* Subordinado 2 */
$splitTwo = new SplitPayments;
$splitTwo->setSubordinateMerchantId('00000000-0000-0000-0000-000000000000');
$splitTwo->setAmount(7000); /* Valor em centavos */
$splitTwo->setFares(5, 0);

/* Executa o split */
$instance = new RequestSale($env, $merchantKey, $auth);
$result = $instance->split(
  'payment-id', /* PaymentId da transação já capturada */
  [$splitOne, $splitTwo] /* Array com dados dos subordinados */
);

/**
 * Captura o JSON encodado retornado pela API
 */
var_dump($result->getResponseRaw());
```