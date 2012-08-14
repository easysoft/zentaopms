<?php
$filePath   = $argv[1];
$users      = empty($argv[2]) ? 0  : $argv[2];
$company    = empty($argv[3]) ? '' : $argv[3];
$type       = empty($argv[4]) ? '' : $argv[4];
$ip         = empty($argv[5]) ? '' : $argv[5];
$mac        = empty($argv[6]) ? '' : $argv[6];
$dirName    = basename($filePath);
define('PASSWORD', md5(md5('Zentao Pro editor') . 'cnezsoft'));

$order->account = $company;
$order->users   = $users;
$order->ip      = $ip;
$order->mac     = $mac;
$order->type    = $type;
createLicense($order, $dirName, '/tmp/encrypt/');
echo "Ziping extension\n";
if(file_exists("/tmp/encrypt/license$dirName$company.zip")) `rm /tmp/encrypt/license$dirName$company.zip`;
`cd /tmp/encrypt/; zip -rm -9 license$dirName$company.zip $dirName`;
echo "license$dirName$company.zip Finished\n";

function createLicense($order, $saveName, $encryptPath)
{
    echo "Creating license.\n";
    if(!is_dir($encryptPath . $saveName))mkdir($encryptPath . $saveName);
    if(!is_dir($encryptPath . $saveName . "/config"))mkdir($encryptPath . $saveName . '/config');
    if(!is_dir($encryptPath . $saveName . "/config/license"))mkdir($encryptPath . $saveName . "/config/license");

    $property  = empty($order->account) ? "company='try'" : "company='$order->account'";
    $property .= $order->users == 0 ? '' : ",user=$order->users";
    $property = "--property \"$property\"";

    $server = empty($order->ip) ? '' : '127.0.0.1,' . $order->ip;
    $server = !empty($order->mac) ? empty($server) ? "'{{$order->mac}}'" : "'$server{{$order->mac}}'" : $server;
    $server = empty($server) ? '' : '--allowed-server ' . $server;

    $expire  = empty($order->account) ? '--expire-in 186d' : '';
    $expire  = $order->type == 'year' ? "--expire-in 372d" : $expire;
    $expire  = $order->type == 'try' ? "--expire-in 31d" : $expire;

    $passphrase = PASSWORD;
    $license = $encryptPath . $saveName . '/config/license/' . $saveName . '.txt';
    echo `/usr/local/ioncube/make_license $property $server $expire --passphrase $passphrase -o $license`;
}
