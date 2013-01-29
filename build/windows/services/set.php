<?php
replaceDriver('../php/php.ini');
replaceDriver('../mysql/my.ini');

/* Replace a config file with current driver. */
function replaceDriver($file)
{
    $driver = getDriver();
    $lines  = file_get_contents($file);
    $lines  = preg_replace('|([a-zA-Z]{1}:){0,1}/xampp/|', "$driver:/xampp/", $lines);
    file_put_contents($file, $lines);
}

/* Get current driver letter. */
function getDriver()
{
    return strtolower(substr(__FILE__, 0, strpos(__FILE__, ':')));
}
