<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

$header = <<<'EOF'
This file is part of Git-Live

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.

@category   GitCommand
@package    Git-Live
@subpackage Core
@author     akito<akito-artisan@five-foxes.com>
@author     suzunone<suzunone.eleven@gmail.com>
@copyright  Project Git Live
@license    MIT
@version    GIT: $Id\$
@link       https://github.com/Git-Live/git-live
@see        https://github.com/Git-Live/git-live
EOF;

$finder = (new PhpCsFixer\Finder())
    ->ignoreDotFiles(false)
    ->ignoreVCSIgnored(true)
    // ->exclude(['dev-tools/phpstan', 'tests/Fixtures'])
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_unsets' => true,
        'class_attributes_separation' => ['elements' => ['method' => 'one',]],
        'multiline_whitespace_before_semicolons' => false,
        'single_quote' => true,
        'header_comment' => [
            'comment_type' => 'PHPDoc',
            'location' => 'after_open',
            'header' => $header,
            ],
        'modernize_strpos' => false,
        'no_useless_concat_operator' => false,
        'concat_space' => ['spacing' => 'one'],

        'function_typehint_space' => true,

        'declare_equal_normalize' => true,
        'whitespace_after_comma_in_array' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'php_unit_test_class_requires_covers' => false,
        'fully_qualified_strict_types' => false,

        'phpdoc_no_empty_return' => false,
        'phpdoc_order_by_value' => true,
        'phpdoc_types_order' => true,
        'phpdoc_var_annotation_correct_order' => true,
        'no_superfluous_phpdoc_tags' => false,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_scalar' => true,
        'phpdoc_return_self_reference' => true,
    ])
    ->setFinder($finder)
    ;
