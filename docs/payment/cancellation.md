# Cancelamento

Realiza o cancelamento de valores das transações nas modalidades abaixo:

> O limite de cancelamento parcial para a mesma transação é de 1 (uma) vez a cada 24 horas.

**Cancelamento total**

Não informar o valor da transação nem os subordinados.

```php
require 'vendor/autoload.php';

use Braspag\Split\Request\Sale;
use Braspag\Split\Domains\Sale\VoidSplitPayments;

/**
 * Executa o cancelamento
 */
$request = new Sale($env, $merchantKey);
$request->cancel('payment-id'); /* PaymentId da transação */
```

**Cancelamento parcial sem split**

Informa apenas o valor parcial, que será cancelado.

```php
require 'vendor/autoload.php';

use Braspag\Split\Request\Sale;
use Braspag\Split\Domains\Sale\VoidSplitPayments;

/**
 * Executa o cancelamento
 */
$request = new Sale($env, $merchantKey);
$result = $request->cancel(
  'payment-id', /* PaymentId da transação */
  1500, /* Valor em centavos */
  [] /* Array vazio */
);

/**
 * Captura o JSON encodado retornado pela API
 */
var_dump($result->getResponseRaw());
```

**Cancelamento parcial com split**

Informar o valor parcial que será cancelado de cada subordinado.

> É possível cancelar o valor de apenas um dos subordinados.

```php
require 'vendor/autoload.php';

use \Braspag\Split\Request\Sale;
use \Braspag\Split\Domains\Sale\VoidSplitPayments;

/* Subordinado 1 */
$splitOne = new VoidSplitPayments();
$splitOne->setSubordinateMerchantId('0000000-000-0000-0000-00000000000'); /* Merchant ID do subordinado */
$splitOne->setVoidedAmount(1500); /* Valor em centavos */

/* Subordinado 2 */
$splitTwo = new VoidSplitPayments();
$splitTwo->setSubordinateMerchantId('0000000-000-0000-0000-00000000000'); /* Merchant ID do subordinado */
$splitTwo->setVoidedAmount(1000); /* Valor em centavos */

/**
 * Executa o cancelamento
 */
$request = new Sale($env, $merchantKey);
$result = $request->cancel(
  'payment-id', /* PaymentId da transação */
  2500, /* Valor total em centavos */
  [$splitOne, $splitTwo] /* Array com dados dos subordinados */
);

/**
 * Captura o JSON encodado retornado pela API
 */
var_dump($result->getResponseRaw());
```