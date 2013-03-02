<?php
/**
 * This file is used to set path and port for apache, mysql and php config files. 
 */

/* Config files. */
$phpConf    = '../php/php.ini';
$mysqlConf  = '../mysql/my.ini';
$apacheConf = '../apache/conf/httpd.conf';
$zentaoConf = '../zentao/config/my.php';
$pmaConf    = '../phpmyadmin/config.inc.php';

/* Replace drivers for php and mysql. */
replaceDriver($phpConf);   print("Set driver for php.ini.\n");
replaceDriver($mysqlConf); print("Set driver for my.ini.\n");

/* Set ports of apache and mysql. */
$usedPorts  = getUsedPorts();

$apachePort = setApachePort($usedPorts, 88);
$apachePort ? print("Apache is using $apachePort port.\n") : "Set apache port error, please check $apacheConf.\n";

$mysqlPort  = setMySQLPort($usedPorts, 3308);
if($mysqlPort)
{
    echo "Mysql is using $mysqlPort port.\n";
    setZenTaoConf($mysqlPort);
    setPMAConf($mysqlPort);
}
else
{
    echo "Set mysql port error, please check $mysqlConf.\n";
}

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

/* Set apache port. */
function setApachePort($usedPorts, $suggestPort)
{
    if(file_exists('./port.apache')) return trim(file_get_contents('./port.apache'));

    global $apacheConf;

    $currentPort = getApachePort();
    if(isset($usedPorts[$currentPort]))
    {
        $found = false;

        for($port = $suggestPort; $port < 100; $port ++)
        {
            if(!isset($usedPorts[$port]))
            {
                $apache = file_get_contents($apacheConf);
                $apache = str_replace($currentPort, $port, $apache);
                file_put_contents($apacheConf, $apache);

                $found = true;
                $currentPort = $port;
                break;
            }
        }

        if(!$found) $currentPort = 0;
    }

    if($currentPort != 0) file_put_contents('./port.apache', $currentPort);
    return $currentPort;
}

/* Get current port of apache. */
function getApachePort()
{
    global $apacheConf;

    preg_match('/Listen\s*([0-9]*)/', file_get_contents($apacheConf), $result);
    if(isset($result[1])) return trim($result[1]);
    return 80;
}

/* Set port of mysql. */
function setMySQLPort($usedPorts, $suggestPort)
{
    if(file_exists('./port.mysql')) return trim(file_get_contents('./port.mysql'));

    global $mysqlConf;

    $currentPort = getMySQLPort();
    if(isset($usedPorts[$currentPort]))
    {
        $found = false;

        for($port = $suggestPort; $port < 3399; $port ++)
        {
            if(!isset($usedPorts[$port]))
            {
                $mysql = file_get_contents($mysqlConf);
                $mysql = str_replace($currentPort, $port, $mysql);
                file_put_contents($mysqlConf, $mysql);

                $found = true;
                $currentPort = $port;
                break;
            }
        }

        if(!$found) $currentPort = 0;
    }

    if($currentPort != 0) file_put_contents('./port.mysql', $currentPort);
    return $currentPort;
}

/* Get current port of mysql. */
function getMySQLPort()
{
    global $mysqlConf;

    preg_match('/port\s*=\s*([0-9]*)/', file_get_contents($mysqlConf), $result);
    if(isset($result[1])) return trim($result[1]);
    return 3306;
}

/* Get used ports. */
function getUsedPorts()
{
    /**
     * Call netstat -na | find /i 'listening' to get all listening ports, which will output:
     * TCP   0.0.0.0:135   0.0.0.0:0   LISTENING
     * TCP   0.0.0.0:445   0.0.0.0:0   LISTENING 
     */
    $netstats = `netstat -an |find /i "listening"`;

    /* Using preg to strip all listening ports. */
    preg_match_all('/:([0-9]*)\s{1,}[0-9]{1}/', $netstats, $results);

    if(isset($results[1])) return array_flip(array_unique($results[1]));
    return array();
}

/* Set mysql port for my.php. */
function setZenTaoConf($mysqlPort)
{
    global $zentaoConf;
    $lines = file_get_contents($zentaoConf);
    $lines = preg_replace("/=\s'[0-9]{1,}'/", "= '$mysqlPort'", $lines);
    file_put_contents($zentaoConf, $lines);
}

/* Set mysql port for phpmyadmin. */
function setPMAConf($mysqlPort)
{
    global $pmaConf;
    $lines = file_get_contents($pmaConf);
    $lines = preg_replace("/=\s'[0-9]{1,}'/", "= '$mysqlPort'", $lines);
    file_put_contents($pmaConf, $lines);
}
