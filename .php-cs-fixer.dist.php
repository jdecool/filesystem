<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
;

$config = new PhpCsFixer\Config();
$config
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'single_import_per_statement' => false,
    ])
    ->setFinder($finder)
;

return $config;
