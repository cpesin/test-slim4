<?php

$finder = PhpCsFixer\Finder::create()
    ->in('public', 'src', 'bin', 'config', 'tests')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@Symfony' => true,
        'curly_braces_position' => true,
    ])
    ->setFinder($finder)
;