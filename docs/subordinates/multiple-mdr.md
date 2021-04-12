# Cadastro com MDR por intervalo de parcelas

Cadastra o subordinado com percentual definido conforme o intervalo de parcelas.

> Armazene o Merchant ID do subordinado para utilizar nas transações.

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\Subordinate\{
  Merchant,
  Document,
  Bank,
  MerchantDiscountRate,
  Agreement,
  Notification,
  Attachment
};

use Braspag\Split\Constants\Card\{
  Type as CardType,
  Brand as CardBrand
};

use Braspag\Split\Constants\Subordinate\{
  Attachment as AttachmentConstants,
  Bank as BankConstants
};

use Braspag\Split\Request\Merchant as RequestMerchant;
use Braspag\Split\Domains\Address;

use Braspag\Split\Exception\BraspagSplitException;

$merchant = new Merchant();
$merchant->setCorporateName("Nome da Empresa"); /* Nome ou Razão Social */
$merchant->setFancyName("Nome Fantasia"); /* Nome da loja */
$merchant->setContactName("Nome do contato");
$merchant->setContactPhone("11912341234"); /* Telefone para contato */
$merchant->setMailAddress("nome@email.com"); /* E-mail para contato */
$merchant->setWebsite("https://www.site.com.br"); /* Opcional */

/**
 * O MCC é um número registrado na ISO 18245 para serviços financeiros de varejo,
 * utilizado para classificar o negócio pelo tipo fornecido de bens ou serviços.
 * https://www.web-payment-software.com/online-merchant-accounts/mcc-codes/
 */
$merchant->setMerchantCategoryCode("MCC"); /* Contém 4 números */

/**
 * Utilize Document::cnpj para cadastrar um CNPJ válido
 * Utilize Document::cpf para cadastrar um CPF válido
 */
$document = Document::cnpj("00000000000000"); /* Somente os números */
$merchant->setDocument($document);

/**
 * Utilize BankConstants::ACCOUNT_TYPE_SAVINGS para conta poupança
 * Utilize BankConstants::ACCOUNT_TYPE_CHECKING para conta corrente
 */
$bank = new Bank;
$bank->setBankAccountType(BankConstants::ACCOUNT_TYPE_CHECKING);
$bank->setNumber("0002");
$bank->setOperation("2"); /* Utilizado geralmente pela Caixa para identificar a operação (é opcional) */
$bank->setVerifierDigit("2"); /* Número da agência */
$bank->setAgencyNumber("0002"); /* Número da agência */
$bank->setAgencyDigit("2"); /* Digito da agência (utilize X caso não tenha) */
$bank->setDocument($document);
$merchant->setBankAccount($bank);

/**
 * Em `setBank`, utilize o código numérico definido pelo Banco Central do Brasil
 * https://www.bcb.gov.br/Fis/CODCOMPE/Tabela.pdf
 */
$bank->setBank("001"); /* Contém 3 números */

$address = new Address;
$address->setStreet("Rua do subordinado");
$address->setNumber("11111");
$address->setComplement("APTO 1111"); /* Opcional */
$address->setNeighborhood("Centro");
$address->setCity("São Paulo");
$address->setState("SP");
$address->setZipCode("12345678");
$merchant->setAddress($address);

/**
 * Define juros de 1,5% para pagamento à vista com
 * cartão de débito da bandeira Mastercard
 */
$mdr1 = new MerchantDiscountRate;
$mdr1->setCardType(CardType::DEBIT);
$mdr1->setCardBrand(CardBrand::MASTER); /* Bandeira do cartão (pode ser um nome como Master) */
$mdr1->setPercent(1.5); /* Percentual de comissão do master */

/**
 * Define juros de 2% para pagamento à vista com
 * cartão de crédito da bandeira Mastercard
 */
$mdr2 = new MerchantDiscountRate;
$mdr2->setCardType(CardType::CREDIT);
$mdr2->setCardBrand(CardBrand::MASTER); /* Bandeira do cartão (pode ser um nome como Master) */
$mdr2->setInitialInstallment(1); /* Parcela inicial */
$mdr2->setFinalInstallment(1); /* Parcela final */
$mdr2->setPercent(2); /* Percentual de comissão do master */

/**
 * Define juros de 3% para pagamento parcelado (2 a 6 vezes)
 * com cartão de crédito da bandeira Mastercard
 */
$mdr3 = new MerchantDiscountRate;
$mdr3->setCardType(CardType::CREDIT);
$mdr3->setCardBrand(CardBrand::MASTER); /* Bandeira do cartão (pode ser um nome como Master) */
$mdr3->setInitialInstallment(2); /* Parcela inicial */
$mdr3->setFinalInstallment(6); /* Parcela final */
$mdr3->setPercent(3); /* Percentual de comissão do master */

/**
 * Define juros de 3% para pagamento parcelado (7 a 12 vezes)
 * com cartão de crédito da bandeira Mastercard
 */
$mdr4 = new MerchantDiscountRate;
$mdr4->setCardType(CardType::CREDIT);
$mdr4->setCardBrand(CardBrand::MASTER); /* Bandeira do cartão (pode ser um nome como Master) */
$mdr4->setInitialInstallment(7); /* Parcela inicial */
$mdr4->setFinalInstallment(12); /* Parcela final */
$mdr4->setPercent(4); /* Percentual de comissão do master */

$agreement = new Agreement;
$agreement->setFee(10); /* Taxa fixa em centavos para o master */
$agreement->setMerchantDiscountRates([
  $mdr1,
  $mdr2,
  $mdr3,
  $mdr4
]);
$merchant->setAgreement($agreement);

/**
 * Define URL para notificação da situação do subordinado.
 * É necessário pelo menos um "header"
 * É do tipo chave/valor
 */
$notification = new Notification;
$notification->setUrl("https://www.seusite.com.br/notificacao.php");
$notification->addHeader("key", "value"); /* Obrigatório */
$merchant->setNotification($notification);

/**
 * Adiciona um documento para validação do subordinado (opcional).
 * O documento pode ser de dois tipos:
 *  - AttachmentConstants::TYPE_PROOF_OF_BANK_DOMICILE => Comprovante de domicílio bancário
 *  - AttachmentConstants::TYPE_MODEL_OF_ADHESION_TERM => Modelo de termo de adesão
 * O arquivo pode ser do tipo:
 *  - jpg, png ou pdf
 */
$attachment = new Attachment;
$attachment->setType(AttachmentConstants::TYPE_PROOF_OF_BANK_DOMICILE);
$attachment->setFilename('comprovante'); /* Nome do arquivo */
$attachment->setFileType('png'); /* Extensão do arquivo */
$attachment->setData('/path/to/imagem.png'); /* Caminho completo para o arquivo */
$merchant->addAttachment($attachment);

try {
  /**
   * Executa o cadastro
   */
  $request = new RequestMerchant($env, $auth);
  $response = $request->register($merchant);

  /**
   * Captura o Merchant ID do subordinado
   */
  echo $response->getMerchantId();
} catch (BraspagSplitException $e) {
  die($e->getResponse());
} catch (\Exception $e) {
  die($e->getMessage());
}
```