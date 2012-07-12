<?php
$filePath   = $argv[1];
$users      = empty($argv[2]) ? 0  : $argv[2];
$company    = empty($argv[3]) ? '' : $argv[3];
$type       = empty($argv[4]) ? '' : $argv[4];
$ip         = empty($argv[5]) ? '' : $argv[5];
$mac        = empty($argv[6]) ? '' : $argv[6];
$dirName    = basename($filePath);

$order->account = $company;
$order->users   = $users;
$order->ip      = $ip;
$order->mac     = $mac;
$order->type    = $type;
createLicense($order, $dirName, '/tmp/encrypt/');
echo "Ziping extension\n";
if(file_exists("license$dirName$company.zip")) `rm license$dirName$company.zip`;
`cd /tmp/encrypt/; zip -rm -9 license$dirName$company.zip $dirName`;
echo "Finished\n";

function createLicense($order, $saveName, $encryptPath)
{
    echo "Creating license.\n";
    $property = $order->users == 0 ? '' : "--property user=$order->users";
    $expire   = empty($order->account) ? '--expire-in 180d' : '';
    if(!is_dir($encryptPath . $saveName))mkdir($encryptPath . $saveName);
    if(!is_dir($encryptPath . $saveName . "/config"))mkdir($encryptPath . $saveName . '/config');
    if(!is_dir($encryptPath . $saveName . "/config/license"))mkdir($encryptPath . $saveName . "/config/license");
    $server = empty($order->ip) ? '' : $order->ip;
    $server = !empty($order->mac) ? empty($server) ? "'{{$order->mac}}'" : "'$server{{$order->mac}}'" : $server;
    $server = empty($server) ? '' : '--allowed-server ' . $server;
    $expire  = $order->type == 'year' ? "--expire-in 365d" : $expire;
    $expire  = $order->type == 'try' ? "--expire-in 30d" : $expire;
    $passphrase = empty($order->account) ? 'try' : $order->account;
    $license = $encryptPath . $saveName . '/config/license/' . $saveName . $order->account . '.txt';
    echo `/usr/local/ioncube/make_license $property $expire --passphrase $passphrase -o $license`;
}
