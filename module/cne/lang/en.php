<?php
$lang->CNE->InstallSuccess = 'Install succeeded';
$lang->CNE->InstallFailure = 'Install failed';
$lang->CNE->serverError    = 'CNE Server Error';

$lang->CNE->statusList = array();
$lang->CNE->statusList['normal']   = 'Normal';
$lang->CNE->statusList['abnormal'] = 'Abnormal';
$lang->CNE->statusList['stopped']  = 'Stopped';
$lang->CNE->statusList['unknown']  = 'UnKnown';

$lang->CNE->statusIcons = array();
$lang->CNE->statusIcons['normal']   = "check";
$lang->CNE->statusIcons['abnormal'] = "exclamation-pure";
$lang->CNE->statusIcons['stopped']  = "pause-pure";
$lang->CNE->statusIcons['unknown']  = "exclamation-pure";

$lang->CNE->errorList = array();
//$lang->CNE->errorList[400]   = '不能包含特殊字符';
$lang->CNE->errorList[400]   = 'Failed to request Cluster API';
$lang->CNE->errorList[404]   = 'Service not found';
$lang->CNE->errorList[40004] = 'Certificate does not match the domain';
$lang->CNE->errorList[41001] = 'Certificate has expired';
$lang->CNE->errorList[41002] = 'Certificate mismatch';
$lang->CNE->errorList[41003] = 'Incomplete certificate chain';
$lang->CNE->errorList[41004] = 'Private key does not match the certificate';
$lang->CNE->errorList[41005] = 'Failed to parse certificate';
$lang->CNE->errorList[41006] = 'Failed to parse key';
