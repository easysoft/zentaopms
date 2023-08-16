<?php
$lang->CNE->InstallSuccess = 'Installation Succeeded';
$lang->CNE->InstallFailure = 'Installation Failed';
$lang->CNE->serverError    = 'CNE Server Error';

$lang->CNE->statusList = array();
$lang->CNE->statusList['normal']   = 'Normal';
$lang->CNE->statusList['abnormal'] = 'Anomaly';
$lang->CNE->statusList['stopped']  = 'Stopped';
$lang->CNE->statusList['unknown']  = 'Unknown';

$lang->CNE->statusIcons = array();
$lang->CNE->statusIcons['normal']   = "<i class='icon icon-5x icon-check-circle status-green'></i>";
$lang->CNE->statusIcons['abnormal'] = "<i class='icon icon-5x icon-close-circle status-red'></i>";
$lang->CNE->statusIcons['stopped']  = "<i class='icon icon-5x icon-off status-gray'></i>";
$lang->CNE->statusIcons['unknown']  = "<i class='icon icon-5x icon-alert-sign status-orange'></i>";

$lang->CNE->errorList = array();
//$lang->CNE->errorList[400]   = 'Only number and letter allowed';
$lang->CNE->errorList[400]   = 'Failed to request cluster info';
$lang->CNE->errorList[404]   = 'Service does not exist';
$lang->CNE->errorList[40004] = 'Certificate does not match the domain';
$lang->CNE->errorList[41001] = 'Certificate has expired';
$lang->CNE->errorList[41002] = 'Certificate mismatch';
$lang->CNE->errorList[41003] = 'Incomplete certificate chain';
$lang->CNE->errorList[41004] = 'Certificate and private key do not match';
$lang->CNE->errorList[41005] = 'Failed parsing certificate';
$lang->CNE->errorList[41006] = 'Failed parsing key';
