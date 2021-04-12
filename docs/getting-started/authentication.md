# Autenticação

Necessário possuir credenciais de acesso para o ambiente sandbox ou produção.

As credenciais são: ClientId, ClientSecret e MerchantKey.

> Solicite as credenciais através do site https://suporte.braspag.com.br ou pelo telefone 4003-3058

**Sandbox**

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\{
  Environment,
  Authentication
};

$merchantKey = 'merchant-key';
$env = Environment::sandbox('client-id', 'client-sercret');
$auth = new Authentication($env);
```

**Produção**

```php
require 'vendor/autoload.php';

use Braspag\Split\Domains\{
  Environment,
  Authentication
};

$merchantKey = 'merchant-key';
$env = Environment::production('client-id', 'client-sercret');
$auth = new Authentication($env);
```