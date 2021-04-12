# Ajustes

Realiza o ajuste de valores entre dois participantes.

Só pode ser realizado após o split.

Só é liquidado, caso exista saldo positivo na conta para débito.

Se não liquidado, ficará postergado até que haja saldo para o débito.

> Ocorre exclusivamente do master para o subordinado e vice-versa.

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\{
  Environment,
  Authentication,
  Schedule\Adjustment
};

use Braspag\Split\Request\Adjustment as RequestAdjustment;

$adjustment = new Adjustment;
$adjustment->setMerchantIdToDebit('0000000-000-0000-0000-00000000000'); /* Merchant ID de onde o valor será debitado */
$adjustment->setMerchantIdToCredit('0000000-000-0000-0000-00000000000'); /* Merchant ID para onde o valor será creditado */
$adjustment->setForecastedDate('2020-12-01'); /* Data em que o ajuste deverá ocorrer */
$adjustment->setAmount(1000); /* Valor em centavos */
$adjustment->setDescription('Multa por não cumprimento do prazo de entrega no pedido XYZ'); /* Informações sobre o motivo do ajuste */
$adjustment->setTransactionId('payment-id'); /* Opcional */

/**
 * Executa o ajuste
 */
$request = new RequestAdjustment($env, $auth);
$result = $request->register($adjustment);

echo implode(PHP_EOL, [
  $result->getMerchantIdToDebit(),
  $result->getMerchantIdToCredit(),
  $result->getForecastedDate(),
  $result->getAmount(),
  $result->getDescription(),
  $result->getTransactionId(),
  $result->getId(),
  $result->getStatus()
]);

/**
 * Captura o JSON encodado retornado pela API
 */
var_dump($result->getResponseRaw());
```