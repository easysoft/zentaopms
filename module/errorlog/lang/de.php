<?php
$lang->errorlog->common      = 'Fehlerprotokoll';
$lang->errorlog->browse      = 'Fehlerprotokolle durchsuchen';
$lang->errorlog->browseAbbr  = 'Durchsuchen';
$lang->errorlog->view        = 'Fehlerprotokoll anzeigen';
$lang->errorlog->viewAbbr    = 'Anzeigen';
$lang->errorlog->delete      = 'Löschen';
$lang->errorlog->batchDelete = 'Mehrfach Löschung';
$lang->errorlog->setting     = 'Einstellungen';

$lang->errorlog->days               = 'Speichertage';
$lang->errorlog->info               = 'Fehlerprotokolle, die älter als die Aufbewahrungsfrist sind, werden gelöscht. Bitte aktivieren Sie die geplanten Aufgaben (Cron).';
$lang->errorlog->notFound           = 'Kein entsprechendes Fehlerprotokoll gefunden.';
$lang->errorlog->empty              = 'Noch keine Fehlerprotokolle.';
$lang->errorlog->confirmDelete      = 'Möchten Sie dieses Fehlerprotokoll wirklich löschen?';
$lang->errorlog->confirmBatchDelete = 'Möchten Sie die ausgewählten Fehlerprotokolle wirklich löschen?';

$lang->errorlog->requestID   = 'Anfrage-ID';
$lang->errorlog->account     = 'Konto';
$lang->errorlog->module      = 'Modul';
$lang->errorlog->method      = 'Methode';
$lang->errorlog->url         = 'URL';
$lang->errorlog->level       = 'Stufe';
$lang->errorlog->message     = 'Meldung';
$lang->errorlog->file        = 'Datei';
$lang->errorlog->line        = 'Zeile';
$lang->errorlog->trace       = 'Ablaufverfolgung';
$lang->errorlog->createdDate = 'Aufgetreten am';

$lang->errorlog->featureBar = array();
$lang->errorlog->featureBar['browse'] = array('all' => 'Alle');

$lang->errorlog->notice = new stdclass();
$lang->errorlog->notice->int = '『%s』sollte eine positive Zahl sein.';

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
if(PHP_VERSION_ID < 80400) $lang->errorlog->levelList[E_STRICT] = 'Strict'; // E_STRICT自PHP 8.4起弃用，仅低版本需要展示。
$lang->errorlog->levelList[E_RECOVERABLE_ERROR] = 'Recoverable Error';
$lang->errorlog->levelList[E_DEPRECATED]        = 'Deprecated';
$lang->errorlog->levelList[E_USER_DEPRECATED]   = 'User Deprecated';
