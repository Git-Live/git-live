<?php
/**
 * Fix #[AsCommand] attributes: remove description from attribute and restore setDescription() in configure()
 */

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(__DIR__ . '/src/libs/GitLive/Command')
);

foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }

    $content = file_get_contents($file->getPathname());

    // Match AsCommand attribute with description
    if (!preg_match('/#\[\\\\Symfony\\\\Component\\\\Console\\\\Attribute\\\\AsCommand\([^)]*description:/s', $content)) {
        continue;
    }

    // Extract description expression from attribute line
    // Pattern: #[AsCommand(name: 'xxx', description: EXPR)]
    // or:      #[AsCommand(name: 'xxx', description: EXPR, aliases: [...])]
    if (!preg_match(
        '/#\[\\\\Symfony\\\\Component\\\\Console\\\\Attribute\\\\AsCommand\(name:\s*\'([^\']+)\'(?:,\s*name:\s*\'[^\']+\')?(?:,\s*description:\s*(.*?))?(?:,\s*aliases:\s*(\[[^\]]+\]))?\)\]/s',
        $content,
        $matches
    )) {
        echo "SKIP (no match): " . $file->getPathname() . "\n";
        continue;
    }

    $commandName = $matches[1];
    $descriptionExpr = isset($matches[2]) ? trim($matches[2]) : null;
    $aliasesExpr = isset($matches[3]) ? trim($matches[3]) : null;

    if (!$descriptionExpr) {
        echo "SKIP (no description): " . $file->getPathname() . "\n";
        continue;
    }

    // Build new AsCommand attribute (name only, or name + aliases)
    if ($aliasesExpr) {
        $newAttribute = "#[\\Symfony\\Component\\Console\\Attribute\\AsCommand(name: '{$commandName}', aliases: {$aliasesExpr})]";
    } else {
        $newAttribute = "#[\\Symfony\\Component\\Console\\Attribute\\AsCommand(name: '{$commandName}')]";
    }

    // Replace the old attribute with the new one
    $content = preg_replace(
        '/#\[\\\\Symfony\\\\Component\\\\Console\\\\Attribute\\\\AsCommand\(.*?\)\]/s',
        $newAttribute,
        $content
    );

    // Add setDescription() back into configure() after parent::configure()
    // Find configure() method and add setDescription after parent::configure();
    $content = preg_replace(
        '/(protected function configure\(\):\s*void\s*\{[^}]*parent::configure\(\);)\s*\n(\s*)(\$this)/s',
        "$1\n$2\$this\n$2    ->setDescription({$descriptionExpr})\n$2",
        $content,
        1
    );

    // Check if the replacement worked (if $this was in the pattern)
    if (!str_contains($content, "->setDescription({$descriptionExpr})")) {
        // Try simpler pattern - just add after parent::configure();
        $content = preg_replace(
            '/(parent::configure\(\);)(\s*\n)(\s*)(\$this|$)/m',
            "$1\n        \$this\n            ->setDescription({$descriptionExpr})\n        ;\n$3$4",
            $content,
            1
        );
    }

    file_put_contents($file->getPathname(), $content);
    echo "FIXED: " . $file->getPathname() . "\n";
}

echo "Done.\n";
