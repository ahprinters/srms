<?php
// Script to update all module files to use sidebar.php instead of leftbar.php
$baseDir = __DIR__ . '/modules';
$moduleDirs = glob($baseDir . '/*', GLOB_ONLYDIR);

$replacements = 0;

foreach ($moduleDirs as $moduleDir) {
    $phpFiles = glob($moduleDir . '/*.php');
    
    foreach ($phpFiles as $file) {
        $content = file_get_contents($file);
        
        // Replace leftbar.php with sidebar.php
        $newContent = str_replace(
            "include('../../includes/leftbar.php')", 
            "include('../../includes/sidebar.php')", 
            $content, 
            $count1
        );
        
        // Also check for sidetbar.php (the typo version)
        $newContent = str_replace(
            "include('../../includes/sidetbar.php')", 
            "include('../../includes/sidebar.php')", 
            $newContent, 
            $count2
        );
        
        $replacements += $count1 + $count2;
        
        if ($count1 > 0 || $count2 > 0) {
            file_put_contents($file, $newContent);
            echo "Updated: " . $file . " ($count1 + $count2 replacements)<br>";
        }
    }
}

echo "Total replacements: $replacements";
?>