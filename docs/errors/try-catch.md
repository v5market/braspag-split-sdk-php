# Tratamento de exceções

Para tratar exceções utilize o bloco `try..catch`.

**Exemplo do tratamento de exceções _LengthException_ e _InvalidArgumentException_:**

```php
try {
  $merchant = new Merchant();
  $merchant->setCorporateName("Nome da Empresa");
  $merchant->setFancyName("Nome Fatansia");
  $merchant->setContactName("Nome do contato");
  $merchant->setContactPhone("");
  $merchant->setMailAddress("nome@email.com");
  $merchant->setWebsite("Web site inválido");

} catch (LengthException | InvalidArgumentException $e) {
  $error = array(
    '--------------------------',
    'Code: ' . $e->getCode(),
    'Message: ' . $e->getMessage()
    '--------------------------'
  );

  die(implode(PHP_EOL, $error));
}
```

**Exemplo do tratamento de exceções _BraspagSplitException_:**

```php
use \Braspag\Split\Exception\BraspagSplitException;

try {
  $request = new RequestMerchant($env, $auth);
  $response = $request->register($merchant);

} catch (BraspagSplitException $e) {
  $error = array(
    '--------------------------',
    'Status Code: ' . $e->getStatusCode(),
    'Response: ' . $e->getResponse()
    '--------------------------'
  );

  die(implode(PHP_EOL, $error));

} catch (Exception $e) {
  $error = array(
    '--------------------------',
    'Unexpected Error',
    'Code: ' . $e->getCode(),
    'Message: ' . $e->getMessage(),
    'File: ' . $e->getFile(),
    'Line: ' . $e->getLine(),
    '--------------------------'
  );

  die(implode(PHP_EOL, $error));
}
```

| Descrição dos erros LengthException e InvalidArgumentException | Código |
|   -  |   -     |
| A rua é obrigatória | 1000 |
| O número do endereço é obrigatório | 1001 |
| O bairro é obrigatório | 1002 |
| A cidade é obrigatória | 1003 |
| A sigla do estado deve possui duas letras | 1004 |
| A sigla do estado é obrigatória | 1005 |
| O CEP deve possuir 8 caraceteres (apenas números) | 1006 |
| O código do país deve possuir até 3 caracteres | 1007 |
| A classe Environment não é instanciável | 2000 |
| A categoria de presente é inválida. | 3000 |
| A cobertura do host é inválida. | 3001 |
| A cobertura não sensorial é inválida. | 3002 |
| A cobertura de obscenidades é inválida. | 3003 |
| O hedge do telefone é inválido. | 3004 |
| O hedge do telefone é inválido. | 3005 |
| O tipo de item do carrinho é inválido. | 3006 |
| O hedge de velocidade é inválido. | 3007 |
| E-mail inválido | 4000 |
| Endereço IP inválido | 4001 |
| Sequence inválido. | 5000 |
| O ID dos campos definidos pelo comerciante é inválido. Saiba mais na [documentaçãod a API](https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-20-payment.fraudanalysis.merchantdefinedfields) | 5001 |
| O método de envio é inválido. | 5002 |
| Verifique se os campos _Sequence_, _SequenceCriteria_, _Provider_, _TotalOrderAmount_, _FingerPrintId_, _Browser_, _Cart_ foram preenchidos corretamente | 5003 |
| O nome do cliente é obrigatório | 6000 |
| O nome deve ter menos de 256 caracteres | 6001 |
| O email do cliente é inválido | 6002 |
| O telefone do cliente deve ter o DDD e o dígito 9 | 6003 |
| País de pagamento inválido. Use a ISO 3166-1 alfa-3. Ex: BRA = Brazil, USA = United States, DEU = Germany | 7000 |
| A marca do cartão é inválida. | 7001 |
| Juros de pagamento inválidos. | 7002 |
| O URL de retorno deve ser válido. | 7003 |
| O URL de retorno deve ser válido. | 7004 |
| Nome é obrigatório | 7005 |
| Verifique se os campos _provider_, _type_, _amount_ e _card_ foram preenchidos corretamente | 7006 |
| Parcelamento inválido | 7007 |
| A captura deve ser automática com cartão de débito | 7008 |
| A URL de retorno é obrigatória com pagamento do tipo de débito | 7009 |
| O estado deve ter menos de 51 caracteres | 8000 |
| Os vampos _merchantOrderId_, _customer_ e _payment_ são obrigatórios | 8001 |
| O número do cartão deve ter apenas números | 9000 |
| A data de validade deve estar no formato MM/AAAA | 9001 |
| O número do endereço deve ter menos de 65 caracteres | 9002 |
| O valor de _MerchantDiscountRate_ deve ser um _array_ | 9003 |
| O valor deve ser uma instância de MerchantDiscountRate | 9004 |
| Tipo de anexo inválido. | 10000 |
| O nome do arquivo deve ter até 51 caracteres | 10001 |
| A extensão do arquivo é inválida. | 10002 |
| O tamanho máximo do arquivo deve ser de até 1MB. | 10003 |
| Arquivo inválido. Use _base64_ ou informe o local dele. | 10004 |
| O código de compensação do banco é inválido. Saiba mais no site do [Banco Central do Brasil](https://www.bcb.gov.br/Fis/CODCOMPE/Tabela.pdf) | 11000 |
| O tipo de conta bancária é inválida. | 11001 |
| O número do banco é inválido. | 11002 |
| A valor da operação bancária deve ter até 11 caracteres | 11003 |
| O valor do digito verificado é inválido. | 11004 |
| O número da agência deve menos de 16 caracteres. | 11005 |
| O digito da agência é obrigatório e deve ser numérico. | 11006 |
| O _MasterMerchantId_ deve ter até 37 caracteres. | 12000 |
| O _MerchantId_ deve ter até 37 caracteres. | 12001 |
| O nome da empresa deve ter até 37 caracteres. | 12002 |
| O nome fantasia da empresa deve ter até 51 caracteres. | 12003 |
| Código de categoria do vendedor (MCC) é inválido. Saiba mais na [lista de MCC](https://www.web-payment-software.com/online-merchant-accounts/mcc-codes/) | 12004 |
| O nome do contato deve ter até 101 caracteres. | 12005 |
| O telefone de contato deve ter até 12 caracteres. | 12006 |
| O endereço de e-mail é inválido | 12007 |
| O endereço do website é inválido | 12008 |
| O tipo de cartão é inválido. | 13000 |
| A bandeira do cartão é inválida. | 13001 |
| A parcela inicial deve ser maior que 0 e menor que 13 | 13002 |
| A parcela final deve ser maior que 0 e menor que 13. | 13003 |
| O tipo do valor da taxa deve ser um número inteiro ou _float_. | 13004 |
| O valor da taxa deve possui até duas casas decimais. | 13005 |
| A URL de notificação é inválida | 14000 |