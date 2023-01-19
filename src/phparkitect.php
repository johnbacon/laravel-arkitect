<?php

declare(strict_types=1);

use Arkitect\CLI\Config;
use Mortexa\LaravelArkitect\RuleLoader;
use Mortexa\LaravelArkitect\Rules\BaseRule;

return static function (Config $config): void {
    $addRules = function (array $rules) use (&$config): void {
        foreach ($rules as $rule) {
            if ($rule::isValid()) {
                $config->add($rule::classSet(), $rule::rule());
            }
        }
    };

    $rules = RuleLoader::all();

    $psr4_namespaces = data_get(json_decode(file_get_contents('composer.json'), true), 'autoload.psr-4');

    foreach ($psr4_namespaces as $namespace => $path) {
        BaseRule::$rootNamespace = $namespace;
        BaseRule::$rootPath = $path;
        $addRules($rules);
    }
};
