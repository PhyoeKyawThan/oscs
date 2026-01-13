<?php
echo "App generator\n";
echo "App Name: ";
$app_name = trim(fgets(STDIN));

if(file_exists(__DIR__."/$app_name")){
    echo "\nApp already exists. [Skipped]\n";
}else{
    $app_dir = __DIR__.'/'.$app_name;
    $app_views = $app_dir.'/views';
    mkdir($app_dir);
    mkdir($app_views);
    echo "\nCreated App at: $app_dir\nCreated App's Views at: $app_views\n";
}