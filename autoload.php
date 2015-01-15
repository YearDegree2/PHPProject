<?php

$cache_folder = __DIR__ . DIRECTORY_SEPARATOR . 'cache';
$cache_file = $cache_folder . DIRECTORY_SEPARATOR . 'cache.php';

if (!is_file($cache_file)) {
    createCacheFile($cache_folder, $cache_file);
}
include_once $cache_file;

$autoload = function ($className) use ($cache_file) {
    global $cache_map;
    if (!empty($cache_map) && array_key_exists($className, $cache_map)) {
        require_once $cache_map[$className];

        return;
    }
    searchFile($cache_file, $className);
};

function createCacheFile($folder, $file)
{
    if (!is_dir($folder)) {
        createCacheFolder($folder);
    }
    file_put_contents($file, '<?php' . "\n\n" . '$cache_map = array(' . "\n" . ');' . "\n");
}

function createCacheFolder($folder)
{
    mkdir($folder);
}

function searchFile($cache_file , $className)
{
    $classNameFirst = $className;
    $file = '';
    if ($last_position = strrpos($className, '\\')) {
        $pathName = substr($className, 0, $last_position);
        $className = substr($className, $last_position + 1);
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $pathName) . DIRECTORY_SEPARATOR;
    }
    $file .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    if (strrpos($file, DIRECTORY_SEPARATOR)) {
        $file = substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1);
    }
    loadFile($cache_file, $file, $classNameFirst);
}

function loadFile($cache_file, $file, $className, $dir = __DIR__)
{
    $directory = opendir($dir);
    $notAllowedDirectory = array(
        '.',
        '..',
    );
    while (false !== ($entry = readdir($directory))) {
        if (is_dir($dir . DIRECTORY_SEPARATOR . $entry) && !in_array($entry, $notAllowedDirectory)) {
            loadFile($cache_file, $file, $className, $dir . DIRECTORY_SEPARATOR . $entry);
        }
        if ($entry === $file) {
            closedir($directory);
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            addFileToCache($cache_file, $className, $path);
            require_once $path;

            return;
        }
    }
    closedir($directory);
}

function addFileToCache($cache_file, $className, $file)
{
    global $cache_map;
    $cache_map[$className] = $file;
    file_put_contents($cache_file, '<?php' . "\n\n" . '$cache_map = ' . var_export($cache_map, true) . ';' . "\n");
}

spl_autoload_register($autoload);
