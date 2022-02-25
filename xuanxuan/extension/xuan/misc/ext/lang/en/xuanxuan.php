<?php
$lang->misc->client = new stdclass();
$lang->misc->client->version     = 'Client Version';
$lang->misc->client->os          = 'Select OS';
$lang->misc->client->download    = 'Download';
$lang->misc->client->downloading = 'Downloading:';
$lang->misc->client->downloaded  = 'Downloaded!';
$lang->misc->client->setting     = 'Settings';
$lang->misc->client->setted      = 'Done!';

$lang->misc->client->osList['win64']   = 'Windows 64';
$lang->misc->client->osList['win32']   = 'Windows 32';
$lang->misc->client->osList['linux64'] = 'Linux 64';
$lang->misc->client->osList['linux32'] = 'Linux 32';
$lang->misc->client->osList['mac64']   = 'Mac';

$lang->misc->client->errorInfo = new stdclass();
$lang->misc->client->errorInfo->downloadError  = 'Failed to download package!';
$lang->misc->client->errorInfo->configError    = 'Failed to set up!';
$lang->misc->client->errorInfo->manualOpt      = 'Please get client package from %s .';
$lang->misc->client->errorInfo->dirNotExist    = 'The dir <span class="code text-red">%s</span> does not exist. Create it.';
$lang->misc->client->errorInfo->dirNotWritable = 'The dir <span class="code text-red">%s</span> is not writable. <br /> Please exec:<span class="code text-red">sudo chmod 777 %s</span> in Linux.';
