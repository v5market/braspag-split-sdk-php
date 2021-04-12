<?php
declare(strict_types=1);

namespace Braspag\Split\Test;

use PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Environment;
use \Braspag\Split\Domains\Authentication;

class BraspagSplitTestCase extends TestCase
{
    protected $clientId = '<client-id>';
    protected $clientSecret = '<client-secret>';
    protected $merchantKey = '<merchant-key>';

    protected $subordinateOne = '<subordinate-one>';
    protected $subordinateTwo = '<subordinate-two>';

    protected $env = null;
    protected $envProd = null;
    protected $auth = null;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->env = Environment::sandbox($this->clientId, $this->clientSecret);
        $this->envProd = Environment::production($this->clientId, $this->clientSecret);
        $this->auth = new Authentication($this->env);
    }
}
