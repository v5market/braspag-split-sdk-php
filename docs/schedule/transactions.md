# Transações

Consulta os eventos das transações por intervalo de data.

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\{
  Environment,
  Authentication
};

use Braspag\Split\Domains\Schedule\Filter;
use Braspag\Split\Request\Schedule as ScheduleRequest;

$filter = new Filter;
$filter->setInitialForecastedDate('2020-12-01'); /* Data inicial */
$filter->setFinalForecastedDate(DateTime::createFromFormat('d/m/Y', '07/12/2020')); /* Data final */

/**
 * Executa a consulta
 */
$instance = new ScheduleRequest($env, $auth);
$result = $instance->getTransactions($filter);

echo 'PageCount: ' . $result->getPageCount() . PHP_EOL;
echo 'PageSize: ' . $result->getPageSize() . PHP_EOL;
echo 'PageIndex: ' . $result->getPageIndex() . PHP_EOL;
echo '-----------------' . PHP_EOL;

foreach ($result->getTransactions() as $transaction) {
  echo 'PaymentId: ' . $transaction->getPaymentId() . PHP_EOL;
  echo 'Captured Date: ' . $transaction->getCapturedDate() . PHP_EOL;
  echo '*****************' . PHP_EOL;
  
  foreach($transaction->getSchedules() as $schedule) {
    echo implode(PHP_EOL, [
      'MerchantId' => $schedule->getMerchantId(),
      'ForecastedDate' => $schedule->getForecastedDate(),
      'Installments' => $schedule->getInstallments(),
      'InstallmentAmount' => $schedule->getInstallmentAmount(),
      'InstallmentNumber' => $schedule->getInstallmentNumber(),
      'Event' => $schedule->getEvent(),
      'EventDescription' => $schedule->getEventDescription(),
      'EventStatus' => $schedule->getEventStatus(),
      '---------------------------------------',
    ]);
  }
}
```

Consulta os eventos de uma transação por *PaymentId*:

> O filtro *MarchantIds* é opcional.

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\{
  Environment,
  Authentication
};

use Braspag\Split\Domains\Schedule\Filter;
use Braspag\Split\Request\Schedule as ScheduleRequest;

$filter = new Filter;
$filter->setMerchantIds([
  'merchant-id-one',
  'merchant-id-two',
  'merchant-id-three',
]);

/**
 * Executa a consulta
 */
$instance = new ScheduleRequest($env, $auth);
$result = $instance->getTransactions(
  'payment-id', /* PaymentId da transação */
  $filter /* Opcional */
);

/**
 * Captura o JSON encodado retornado pela API
 */
var_dump($result->getResponseRaw());
```