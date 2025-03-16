<?php
// Script to fix sidebar implementation in all module files
$baseDir = __DIR__ . '/modules';
$moduleDirs = glob($baseDir . '/*', GLOB_ONLYDIR);

$replacements = 0;

foreach ($moduleDirs as $moduleDir) {
    $phpFiles = glob($moduleDir . '/*.php');
    
    foreach ($phpFiles as $file) {
        $content = file_get_contents($file);
        
        // Check if BASE_URL is defined in the file
        if (strpos($content, 'BASE_URL') === false) {
            // Add BASE_URL definition after config.php include
            $content = str_replace(
                "include('../../includes/config.php');",
                "include('../../includes/config.php');\nif (!defined('BASE_URL')) { define('BASE_URL', 'http://localhost/srms/'); }",
                $content,
                $count1
            );
            $replacements += $count1;
        }
        
        // Make sure Font Awesome is included
        if (strpos($content, 'font-awesome') !== false && strpos($content, 'fas fa-') !== false) {
            // Add Font Awesome 5 or 6 if using fas classes but missing the library
            if (strpos($content, 'fontawesome') === false && strpos($content, 'all.min.css') === false) {
                $content = str_replace(
                    '<link rel="stylesheet" href="../../css/font-awesome.min.css" media="screen" >',
                    '<link rel="stylesheet" href="../../css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">',
                    $content,
                    $count2
                );
                $replacements += $count2;
            }
        }
        
        // Make sure sidebar.js is included
        if (strpos($content, 'sidebar.js') === false) {
            $content = str_replace(
                '<script src="../../js/main.js"></script>',
                '<script src="../../js/main.js"></script>
        <script src="../../js/sidebar.js"></script>',
                $content,
                $count3
            );
            $replacements += $count3;
        }
        
        // Save the updated file
        file_put_contents($file, $content);
        echo "Updated: " . $file . "<br>";
    }
}

echo "Total replacements: $replacements";
?>