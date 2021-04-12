# Trava

Trava/Destrava o repasse de valores para o subordinado.

Pode ser utilizado para travar e destravar o repasse de vários subordinados na transação.

> O bloqueio será removido automaticamente após 180 dias, caso não seja liberado.

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\{
  Environment,
  Authentication,
  Sale\Settlements
};

use Braspag\Split\Request\Settlements as RequestSettlements;

$settlements = new Settlements();
$settlements->add('merchant-id-one', true); /* Se true, trava */
$settlements->add('merchant-id-two', false); /* Se false, destrava */

/**
 * Executa a trava
 */
$request = new Settlements($env, $auth);
$result = $request->register(
  'payment-id', /* PaymentId da transação */
  $settlements
);

/**
 * Captura o JSON encodado retornado pela API
 */
var_dump($result->getResponseRaw());
```