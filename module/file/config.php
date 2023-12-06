<?php
$config->file->mimes['xml']     = 'text/xml';
$config->file->mimes['html']    = 'text/html';
$config->file->mimes['csv']     = 'text/csv';
$config->file->mimes['default'] = 'application/octet-stream';

$config->file->imageExtensions = array('jpeg', 'jpg', 'gif', 'png');
$config->file->image2Compress  = array('.jpg', '.bmp', '.jpeg');

$config->file->charset   = array('UTF-8' => 'UTF-8', 'GBK' => 'GBK', 'BIG5' => 'BIG5');
$config->file->maxImport = 100;

$config->file->objectType['stepResult']  = 'testcase';
$config->file->objectType['requirement'] = 'story';

$config->file->convertURL['common']['view']       = '1';
$config->file->convertURL['story']['edit']        = '1';
$config->file->convertURL['testsuite']['libview'] = '1';

$config->file->objectGroup = array();
$config->file->objectGroup['design']      = 'project';
$config->file->objectGroup['issue']       = 'project';
$config->file->objectGroup['risk']        = 'project';
$config->file->objectGroup['story']       = 'product';
$config->file->objectGroup['requirement'] = 'product';
$config->file->objectGroup['bug']         = 'product';
$config->file->objectGroup['testcase']    = 'product';
$config->file->objectGroup['testtask']    = 'product';
$config->file->objectGroup['task']        = 'execution';
$config->file->objectGroup['build']       = 'execution';
