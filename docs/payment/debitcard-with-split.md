# Com split no cartão de débito

Realiza o pagamento com cartão de débito.

Informa os dados do subordinado.

Requer redirecionamento do usuário para URL de autenticação bancária.

Requer tratamento do retorno, após o usuário realizar ou não a autenticação.

> A utilização do antifraude não é necessária no cartão de débito. 

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\Sale\{
  Sale,
  Customer,
  Payment,
  Identity,
  SplitPayments
};

use Braspag\Split\Constants\{
  Sale\Currency,
  Sale\Interest
};

use Braspag\Split\Domains\PaymentMethod\DebitCard;
use Braspag\Split\Request\Sale as RequestSale;

/**
 * Definindo as informações do cartão de débito
 */
$card = new DebitCard;
$card->setCardNumber('4111111111111111');
$card->setHolder('Portador do cartão');
$card->setExpirationDate('12/2099');
$card->setSecurityCode('123');
$card->setBrand('Visa');

/**
 * Adiciona os subordinados e os dados do acordo com o master:
 * - 0000000-000-0000-0000-00000000000: É o Merchant ID do subordinado.
 * - 5: Percentual (MDR) referente à comissão do master, que será descontado da parte do subordinado.
 * - 0: Tarifa fixa em centavos que será descontaddo da parte do subordinado.
 */
$splitPayments = new SplitPayments;
$splitPayments->setSubordinateMerchantId('0000000-000-0000-0000-00000000000');
$splitPayments->setAmount(10000); /* Valor em centavos */
$splitPayments->setFares(5, 0);

/**
 * Definições básicas do pagamento
 */
$payment = new Payment;
$payment->setProvider('Simulado'); /* Verifique o provedor correto para produção */
$payment->setAmount(10000); /* Valor total em centavos */
$payment->setInstallments(1); /* No débito deve ser sempre 1 */
$payment->setPaymentMethod($card);
$payment->setCurrency(Currency::BRL);
$payment->setCountry('BRA');
$payment->setInterest(Interest::INTEREST_MERCHANT);
$payment->setCapture(true); /* Deve ser true */
$payment->setSoftDescriptor('IdentificaoDaLoja');
$payment->setDoSplit(true);
$payment->addSplitPayments($splitPayments);
$payment->addExtraDataCollection('NomeDoCampo', 'Valor do campo'); /* Opcional */
$payment->setReturnUrl('https://www.seusite.com.br/retorno.php'); /* URL para retorno após a autenticação externa */

/**
 * Para CNPJ utilize:
 * Identity::cnpj('00.000.000/0000-00')
 */
$identity = Identity::cpf('000.000.000-00');

/**
 * Definindo as informações do cliente
 */
$customer = new Customer;
$customer->setName('Comprador Accept');
$customer->setIdentity($identity);
$customer->setEmail('comprador@braspag.com.br');
$customer->setBirthdate(DateTime::createFromFormat('Y-m-d', '1990-01-30')); /* Opcional */
$customer->setPhone('5521912341234');

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
 * Captura a URL para autenticação bancária
 */
echo $result->getPayment()->getAuthenticationUrl();

/**
 * Captura o JSON encodado retornado pela API
 */
var_dump($result->getResponseRaw());
```