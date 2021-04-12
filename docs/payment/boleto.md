# Boleto

Com o código abaixo, é possível criar uma transação *split* usando o boleto como forma de pagamento.

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\Sale\{
  Sale,
  Customer,
  Payment,
  Identity,
  SplitPayments
};

use Braspag\Split\Domains\PaymentMethod\Boleto;
use Braspag\Split\Domains\Address;
use Braspag\Split\Request\Sale as RequestSale;

/**
 * Definindo as informações do cartão de crédito
 */
$instance = new Boleto();
$boleto->setBoletoNumber('2017091101');
$boleto->setAssignor('Empresa Teste');
$boleto->setDemonstrative('Desmonstrative Teste');
$boleto->setExpirationDate(DateTime::createFromFormat('Y-m-d', '2022-07-13'));
$boleto->setIdentification('00.000.000/0001-91');
$boleto->setInstructions('Aceitar somente até a data de vencimento.');
$boleto->setDaysToFine(1);
$boleto->setFineRate(10.00000);
$boleto->setDaysToInterest(1);
$boleto->setInterestRate(5.00000);

/**
 * Adiciona os subordinados e os dados do acordo com o master:
 * - 0000000-000-0000-0000-00000000000: É o Merchant ID do subordinado.
 * - $mdr: Percentual (MDR) referente à comissão do master, que será descontado da parte do subordinado.
 * - $fee: Tarifa fixa em centavos que será descontaddo da parte do subordinado.
 */
$splitPayments = new SplitPayments;
$splitPayments->setSubordinateMerchantId('0000000-000-0000-0000-00000000000');
$splitPayments->setAmount(10000); /* Valor em centavos */
$splitPayments->setFares($mdr, $fee);

/**
 * Definições básicas do pagamento
 */
$payment = new Payment;
$payment->setProvider('Simulado'); /* Verifique o provedor correto para produção */
$payment->setAmount(10000); /* Valor total em centavos */
$payment->setInstallments(1); /* Quantidade de parcelas */
$payment->setPaymentMethod($boleto);
$payment->setCurrency('BRL');
$payment->setCountry('BRA');
$payment->setDoSplit(true);
$payment->addSplitPayments($splitPayments);
$payment->addExtraDataCollection('NomeDoCampo', 'Valor do campo'); /* Opcional */

/**
 * Definindo as informações sobre o endereço do cliente
 */
$address = new Address;
$address->setStreet('Alameda Vai Quem Quer');
$address->setNumber('123');
$address->setComplement('APTO 777'); /* Opcional */
$address->setZipCode('12345678');
$address->setCity('São Paulo');
$address->setState('SP');
$address->setCountry('BR');
$address->setDistrict('Centro');

/**
 * Definindo as informações do cliente
 */
$identity = Identity::cpf('000.000.000-00');
$customer = new Customer;
$customer->setName('Comprador Accept');
$customer->setIdentity($identity);
$customer->setEmail('comprador@braspag.com.br');
$customer->setBirthdate(DateTime::createFromFormat('Y-m-d', '1990-01-30')); /* Opcional */
$customer->setPhone('5521912341234');
$customer->setAddress($address);
$customer->setDeliveryAddress($address);

/**
 * Preparando para executar o pagamento
 */
$sale = new Sale('az19'); /* Número do pedido */
$sale->setPayment($payment);
$sale->setCustomer($customer);

/**
 * Executa o pagamento
 */
$instance = new RequestSale($env, $merchantKey);
$result = $instance->create($sale);

/**
 * Captura o PaymentId
 */
echo $result->getPayment()->getPaymentId();

/**
 * Captura o JSON encodado retornado pela API
 */
var_dump($result->getResponseRaw());
```