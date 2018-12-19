<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('tests/')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'single_import_per_statement' => false,
        'ordered_imports' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(true)
;
