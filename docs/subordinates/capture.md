# Consulta

Consulta os dados do subordinado.

> O Merchant ID é informado após o cadastro do subordinado.

```php
require 'vendor/autoload.php';

use Braspag\Split\Request\Merchant as RequestMerchant;

/**
 * Executa a consulta
 */
$request = new RequestMerchant($env, $auth);
$result = $request->capture('merchant-id'); /* Merchant ID do subordinado */

/**
 * Captura o status do subordinado
 */
$analysis = $result->getAnalysis();
echo $analysis->getStatus();

/**
 * Captura o JSON encodado retornado pela API
 */
var_dump($result->getResponseRaw());
```