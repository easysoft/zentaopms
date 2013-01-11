<?php
include dirname(dirname(dirname(__FILE__))) . "/config/config.php";

if($config->requestType == 'PATH_INFO')
{
    system('php ztcli "http://localhost/admin-win2Unix"');
}
elseif($config->requestType == 'GET')
{
    system('php ztcli "http://localhost/?m=admin&f=win2Unix"');
}

/* avoid init error. */
function getWebRoot(){}
class config{}
