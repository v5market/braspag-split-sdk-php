# Sem split no cartão de débito

Realiza o pagamento com cartão de débito.

Não informa os dados dos subordinados.

Requer redirecionamento do usuário para URL de autenticação bancária (caso disponível).

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
$payment->setCapture(true); /* Se true define a captura como automática */
$payment->setSoftDescriptor('IdentificaoDaLoja');
$payment->setDoSplit(true);
$payment->addExtraDataCollection('NomeDoCampo', 'Valor do campo'); /* Opcional */
$payment->setReturnUrl('https://www.seusite.com/retorno.php'); /* URL para retorno após a autenticação externa */

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