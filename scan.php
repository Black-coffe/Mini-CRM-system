<?php
$dir = $_SERVER['DOCUMENT_ROOT'];

function get_files_recursive($dir){
    $files = array();
    $dir_list = scandir($dir);
    foreach($dir_list as $file){
        if($file != '.' && $file != '..'){
            if(is_dir($dir.'/'.$file)){
                $files = array_merge($files, get_files_recursive($dir.'/'.$file));
            }else{
                $files[] = $dir.'/'.$file;
            }
        }
    }
    return $files;
}

$dirs = get_files_recursive($dir);


echo 'Directories:<br>';
foreach($dirs as $dir){
    echo $dir . '<br>';
}



