:star2: Braspag Pagador SDK PHP
-----------------------

Este SDK tem o objetivo de facilitar a integração de aplicações PHP com a API de pagamento da Braspag.

:hammer: Instalação
-------------------

Utilize o comando abaixo em seu terminal:

```bash
composer require v5market/braspag-split-sdk-php
```

:books: Requisitos
-------------------

 - [PHP 7.3](http://php.net/) ou superior
 - [cURL](https://www.php.net/manual/pt_BR/curl.installation.php)
 - [Extensão PHP JSON](https://www.php.net/manual/en/ref.json.php)
 - [Extensão PHP mbstring](https://www.php.net/manual/en/ref.json.php)
 - [Composer](https://getcomposer.org/)
 - Protocolo TLS 1.2

:orange_book: Documentação
-----------------------

[Acessar documentação](https://v5market.github.io/braspag-split-sdk-php)

:computer: Exemplos
-------------------

Siga o passo a passo abaixo:

1. Instale as dependências do SDK utilizando o *composer*

```bash
composer install
```

2. Acesse a pasta _samples_ e crie o arquivo `.env` com as credenciais

```env
BRASPAG_MERCHANT_ID=""
BRASPAG_MERCHANT_KEY=""
```

3. Execute os arquivos PHP, por exemplo:

```bash
php 09-pix.php
```