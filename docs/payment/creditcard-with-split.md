# Com split no cartão de crédito

Realiza o pagamento com cartão de crédito.

Informa os dados do subordinado e define a captura automática ao realizar a transação.

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\Sale\{
  Sale,
  Customer,
  Payment,
  Identity,
  SplitPayments
};

use Braspag\Split\Domains\FraudAnalysis\{
  FraudAnalysis,
  Browser
};

use Braspag\Split\Domains\Cart\{
  Cart,
  Item as CartItem
};

use Braspag\Split\Constants\{
  Sale\Currency,
  Sale\Interest,
  Cart\Item as ConstantCartItem,
  FraudAnalysis\FraudAnalysis as ConstantFraudAnalysis
};

use Braspag\Split\Domains\PaymentMethod\CreditCard;
use Braspag\Split\Domains\Address;
use Braspag\Split\Request\Sale as RequestSale;

/**
 * Definindo as informações do navegador
 */
$browser = new Browser;
$browser->setEmail('comprador@braspag.com.br');
$browser->setHostName('Teste'); /* Opcional */
$browser->setIpAddress('127.0.0.1'); /* Não pode ser um IP de "localhost" */
$browser->setType('Chrome');

/**
 * Definindo o item para o carrinho
 */
$item = new CartItem;
$item->setName('Descrição do produto');
$item->setQuantity(1);
$item->setSku('20170511');
$item->setUnitPrice(10000); /* Valor em centavos */
/**
 * Definindo as regras da análise antifraude para o item
 */
$item->setGiftCategory('Off');
$item->setHostHedge('Off');
$item->setNonSensicalHedge('Off');
$item->setObscenitiesHedge('Off');
$item->setPhoneHedge('Off');
$item->setRisk('High');
$item->setTimeHedge('Normal');
$item->setVelocityHedge('High');
$item->setType('Default');

/**
 * Adiciona o item no carrinho
 */ 
$cart = new Cart;
$cart->setReturnsAccepted(true);
$cart->addItem($item);

/**
 * Definindo as configurações gerais para o antifraude
 */
$fraudAnalysis = new FraudAnalysis;
$fraudAnalysis->setSequence(ConstantFraudAnalysis::SEQUENCE_AUTHORIZE);
$fraudAnalysis->setSequenceCriteria('OnSuccess');
$fraudAnalysis->setProvider('Cybersource');
$fraudAnalysis->setCaptureOnLowRisk(false);
$fraudAnalysis->setVoidOnHighRisk(false);
$fraudAnalysis->setTotalOrderAmount(10000); /* Valor em centavos */
$fraudAnalysis->setBrowser($browser);
$fraudAnalysis->setCart($cart);
$fraudAnalysis->setFingerPrintId('id123456');

/**
 * Veja as opções de método de envio em ConstantFraudAnalysis::SHIPPING_METHODS[]
 */
$fraudAnalysis->setShippingAddressee('Comprador Accept');
$fraudAnalysis->setShippingMethod('other');
$fraudAnalysis->setShippingPhone('5521912341234');

/**
 * Definindo informações adicionais para o antifraude
 * https://braspag.github.io//manual/split-de-pagamentos-pagador#tabela-20-payment.fraudanalysis.merchantdefinedfields
 */
$fraudAnalysis->addMerchantDefinedFields(2, '100');
$fraudAnalysis->addMerchantDefinedFields(4, 'Web');
$fraudAnalysis->addMerchantDefinedFields(9, 'SIM');

/**
 * Definindo as informações do cartão de crédito
 */
$card = new CreditCard;
$card->setCardNumber('4111111111111111');
$card->setHolder('Nome do Titular');
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
$payment->setInstallments(1); /* Quantidade de parcelas */
$payment->setPaymentMethod($card);
$payment->setFraudAnalysis($fraudAnalysis);
$payment->setCurrency(Currency::BRL);
$payment->setCountry('BRA');
$payment->setInterest(Interest::INTEREST_MERCHANT);
$payment->setCapture(true); /* Deve ser `true`, para captura automática */
$payment->setSoftDescriptor('IdentificaoDaLoja');
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