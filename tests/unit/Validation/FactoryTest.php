<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Validation\Validator;

final class FactoryTest extends TestCase
{
    public function testCreateValidatorWithRuleValid()
    {
        $instance = Validator::cpf();
        $this->assertInstanceOf(\Braspag\Split\Validation\Rule\AbstractRule::class, $instance);
    }
}
