<?php
error_reporting(E_ERROR);
include dirname(dirname(dirname(__FILE__))) . "/config/my.php";

if($config->requestType == 'PATH_INFO')
{
    system('php ../php/ztcli "http://localhost/admin-checkdb"', $requestVar);
}
elseif($config->requestType == 'GET')
{
    system('php ../php/ztcli "http://localhost/?m=admin&f=checkdb"', $requestVar);
}

if(!$requestVar)
{
    echo "Check DataBase successfully!\n";
}
