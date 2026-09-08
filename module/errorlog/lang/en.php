<?php
$lang->errorlog->common      = 'Error Log';
$lang->errorlog->browse      = 'Browse Error Logs';
$lang->errorlog->browseAbbr  = 'Browse';
$lang->errorlog->view        = 'View Error Log';
$lang->errorlog->viewAbbr    = 'View';
$lang->errorlog->delete      = 'Delete';
$lang->errorlog->batchDelete = 'Batch Delete';
$lang->errorlog->setting     = 'Settings';

$lang->errorlog->days               = 'Save days';
$lang->errorlog->info               = 'Error logs exceeding the retention period will be deleted. Please enable Scheduled Tasks (Cron).';
$lang->errorlog->notFound           = 'No corresponding error log found.';
$lang->errorlog->empty              = 'No error logs yet.';
$lang->errorlog->confirmDelete      = 'Are you sure you want to delete this error log?';
$lang->errorlog->confirmBatchDelete = 'Are you sure you want to delete the selected error logs?';

$lang->errorlog->requestID   = 'Request ID';
$lang->errorlog->account     = 'Account';
$lang->errorlog->module      = 'Module';
$lang->errorlog->method      = 'Method';
$lang->errorlog->url         = 'URL';
$lang->errorlog->level       = 'Level';
$lang->errorlog->message     = 'Message';
$lang->errorlog->file        = 'File';
$lang->errorlog->line        = 'Line';
$lang->errorlog->trace       = 'Trace';
$lang->errorlog->createdDate = 'Occurred at';

$lang->errorlog->featureBar = array();
$lang->errorlog->featureBar['browse'] = array('all' => 'All');

$lang->errorlog->notice = new stdclass();
$lang->errorlog->notice->int = '『%s』should be a positive integer.';

$lang->errorlog->levelList = array();
$lang->errorlog->levelList[E_ERROR]             = 'Fatal Error';
$lang->errorlog->levelList[E_WARNING]           = 'Warning';
$lang->errorlog->levelList[E_PARSE]             = 'Parse Error';
$lang->errorlog->levelList[E_NOTICE]            = 'Notice';
$lang->errorlog->levelList[E_CORE_ERROR]        = 'Core Error';
$lang->errorlog->levelList[E_CORE_WARNING]      = 'Core Warning';
$lang->errorlog->levelList[E_COMPILE_ERROR]     = 'Compile Error';
$lang->errorlog->levelList[E_COMPILE_WARNING]   = 'Compile Warning';
$lang->errorlog->levelList[E_USER_ERROR]        = 'User Error';
$lang->errorlog->levelList[E_USER_WARNING]      = 'User Warning';
$lang->errorlog->levelList[E_USER_NOTICE]       = 'User Notice';
if(PHP_VERSION_ID < 80400) $lang->errorlog->levelList[E_STRICT] = 'Strict'; // E_STRICT is deprecated since PHP 8.4, only needed on older versions.
$lang->errorlog->levelList[E_RECOVERABLE_ERROR] = 'Recoverable Error';
$lang->errorlog->levelList[E_DEPRECATED]        = 'Deprecated';
$lang->errorlog->levelList[E_USER_DEPRECATED]   = 'User Deprecated';
