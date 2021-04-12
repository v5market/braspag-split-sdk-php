# Eventos

Consulta eventos por intervalo de data.

**Por data prevista de pagamento**

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
$filter->setPageIndex(2); /* Página a ser consultada (Paginação) */

/**
 * Executa a consulta
 */
$instance = new ScheduleRequest($env, $auth);
$result = $instance->getEvents($filter);

echo 'PageCount: ' . $result->getPageCount() . PHP_EOL;
echo 'PageSize: ' . $result->getPageSize() . PHP_EOL;
echo 'PageIndex: ' . $result->getPageIndex() . PHP_EOL;

foreach($result->getSchedules() as $schedule) {
  echo implode(PHP_EOL, [
    'Id' => $schedule->getId(),
    'PaymentId' => $schedule->getPaymentId(),
    'MerchantId' => $schedule->getMerchantId(),
    'ForecastedDate' => $schedule->getForecastedDate()->format('d/M/Y'),
    'Installments' => $schedule->getInstallments(),
    'InstallmentAmount' => $schedule->getInstallmentAmount(),
    'InstallmentNumber' => $schedule->getInstallmentNumber(),
    'Event' => $schedule->getEvent(),
    'EventDescription' => $schedule->getEventDescription(),
    'EventStatus' => $schedule->getEventStatus(),
    'SourceId' => $schedule->getSourceId(),
    'Mdr' => $schedule->getMdr(),
    'Commission' => $schedule->getId() ? 'Yes' : 'No',
    '---------------------------------------',
  ]);
}
```

**Por data de pagamento**

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\{
  Environment,
  Authentication
};

use Braspag\Split\Domains\Schedule\Filter;
use Braspag\Split\Request\Schedule as ScheduleRequest;

$filter = new Filter;
$filter->setInitialPaymentDate('2020-12-01'); /* Data inicial */
$filter->setFinalPaymentDate('2020-12-07'); /* Data final */
$filter->pageSize(100); /* Valores: 25, 50, 100. */

/**
 * Executa a consulta
 */
$instance = new ScheduleRequest($env, $auth);
$result = $instance->getEvents($filter);

/**
 * Captura o JSON encodado retornado pela API
 */
var_dump($result->getResponseRaw());
```