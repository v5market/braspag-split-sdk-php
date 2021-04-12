# Origem do desconto das taxas

Por padrão, as taxas são descontadas da comissão do master; porém, é possível descontar as taxas a partir do valor informado por ele; para isso, este deverá ser adicionado como um dos participantes da transação.

**Tipos de desconto da taxa:**

| Tipo | Descrição |
| - | - |
| Commission | O desconto será realizado baseado no valor da comissão que o master receberá. |
| Sale | O desconto será realizado baseado no valor que o master informar. |

**Commission**

Definido no pagamento, através das informações adicionais abaixo:

```php
use Braspag\Split\Constants\Sale\MasterRateDiscountType;

/**
 * Definições básicas do pagamento
 */
...
$payment->setInterest(Interest::INTEREST_MERCHANT);
$payment->setMasterRateDiscountType(MasterRateDiscountType::COMMISSION);
...
```

**Sale**

Definindo no split (apenas Pós-Transacional), conforme abaixo:

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\Sale\SplitPayments;
use Braspag\Split\Constants\Sale\MasterRateDiscountType;
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

/* Master (Obrigatório) */
$splitMaster = new SplitPayments;
$splitMaster->setSubordinateMerchantId('00000000-0000-0000-0000-000000000000'); /* É o Merchant ID do master */
$splitMaster->setAmount(2000); /* Valor em centavos refente a parte do master */

/**
 * Executa o split
 */
$instance = new RequestSale($env, $merchantKey, $auth);
$result = $instance->split(
  'payment-id', /* PaymentId da transação */
  [$splitOne, $splitTwo, $splitMaster], /* Array com dados dos subordinados e do master */
  MasterRateDiscountType::SALE
);

/**
 * Captura o JSON encodado retornado pela API
 */
var_dump($result->getResponseRaw());
```