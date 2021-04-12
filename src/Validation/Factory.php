<?php

namespace Braspag\Split\Validation;

class Factory
{
    public const RULES_NAMESPACE = '\\Braspag\\Split\\Validation\\Rule\\';

    private static $defaultInstance;

    public static function getDefaultInstance()
    {
        if (self::$defaultInstance === null) {
            self::$defaultInstance = new self();
        }

        return self::$defaultInstance;
    }

    /**
     * Instancia o objeto com a regra definida
     *
     * @param String $name Nome da regra
     * @param Mixed $args
     *
     * @return Rule\AbstractRule;
     *
     * @throws \UnexpectedValueException Ocorre quando a classe não existe
     * @throws \BadMethodCallException Ocorre quando a classe não pode ser instanciada
     */
    public function rule($name, $args)
    {
        $className = self::RULES_NAMESPACE . ucfirst($name);

        if (!class_exists($className)) {
            throw new \UnexpectedValueException("Rule {$className} not found");
        }

        $reflection = new \ReflectionClass($className);

        if (!$reflection->isInstantiable()) {
            throw new \BadMethodCallException("Class $className is not instantiable");
        }

        return $reflection->newInstanceArgs($args);
    }
}
