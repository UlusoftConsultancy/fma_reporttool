<?php

$fma_files = scandir('./assets/excels/fma', SCANDIR_SORT_ASCENDING);
// filter out . and .., assuming the rest are excel files!
$fma_files = array_filter($fma_files, function($el) { return strlen($el) > 3; });

$apk_files = scandir('./assets/excels/apk', SCANDIR_SORT_ASCENDING);
// filter out . and .., assuming the rest are excel files!
$apk_files = array_filter($apk_files, function($el) { return strlen($el) > 3; });

// 
echo json_encode(array('fma' => array_values($fma_files), 'apk' => array_values($apk_files)));

?>