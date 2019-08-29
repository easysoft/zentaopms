<?php
$lang->webhook->common   = 'Webhook';
$lang->webhook->list     = 'Hook Liste';
$lang->webhook->api      = 'API';
$lang->webhook->entry    = 'Eintrag';
$lang->webhook->log      = 'Log';
$lang->webhook->assigned = 'Augeordnet an';
$lang->webhook->setting  = 'Einstellungen';

$lang->webhook->browse = 'Durchsuchen';
$lang->webhook->create = 'Erstellen';
$lang->webhook->edit   = 'Bearbeiten';
$lang->webhook->delete = 'Löschen';

$lang->webhook->id          = 'ID';
$lang->webhook->type        = 'Typ';
$lang->webhook->name        = 'Name';
$lang->webhook->url         = 'Webhook Adresse';
$lang->webhook->domain      = 'Zentao Domain';
$lang->webhook->contentType = 'Content Type';
$lang->webhook->sendType    = 'Sendungs Typ';
$lang->webhook->product     = "{$lang->productCommon}";
$lang->webhook->project     = "{$lang->projectCommon}";
$lang->webhook->params      = 'Parameter';
$lang->webhook->action      = 'Aktion';
$lang->webhook->desc        = 'Beschreibung';
$lang->webhook->createdBy   = 'Ersteller';
$lang->webhook->createdDate = 'Erstellt am';
$lang->webhook->editedby    = 'Bearbeitet von';
$lang->webhook->editedDate  = 'Bearbeitet am';
$lang->webhook->date        = 'Datum';
$lang->webhook->data        = 'Daten';
$lang->webhook->result      = 'Ergebnis';

$lang->webhook->typeList['']          = '';
$lang->webhook->typeList['dingding']  = 'Dingding';
$lang->webhook->typeList['default']   = 'SDtandard';

$lang->webhook->sendTypeList['sync']  = 'Synchron';
$lang->webhook->sendTypeList['async'] = 'Asynchron';

$lang->webhook->paramsList['objectType'] = 'Objekt Typ';
$lang->webhook->paramsList['objectID']   = 'Objekt ID';
$lang->webhook->paramsList['product']    = "{$lang->productCommon}";
$lang->webhook->paramsList['project']    = "{$lang->projectCommon}";
$lang->webhook->paramsList['action']     = 'Aktion';
$lang->webhook->paramsList['actor']      = 'Actor';
$lang->webhook->paramsList['date']       = 'Datum';
$lang->webhook->paramsList['comment']    = 'Kommentar';
$lang->webhook->paramsList['text']       = 'Aktionsbeschreibung';

$lang->webhook->confirmDelete = 'Möchten Sie diesen Hook löschen?';

$lang->webhook->trimWords = '';

$lang->webhook->note = new stdClass();
$lang->webhook->note->async   = 'Wenn der Sendungstyp asynchron ist, wird Cron benötigt.';
$lang->webhook->note->product = "Alle Aktionen triggern den Hook wenn das {$lang->productCommon} leer ist, andernsfalls werden nur Aktionen des {$lang->productCommon} ausgelöst.";
$lang->webhook->note->project = "Alle Aktionen triggern den Hook wenn das {$lang->projectCommon} leer ist, andernsfalls werden nur Aktionen des {$lang->projectCommon} ausgelöst.";

$lang->webhook->note->typeList['bearychat'] = 'Fügen Sie einen ZenTao bot in bearychat ein um die Adresse des Webhooks zu erhalten.';
$lang->webhook->note->typeList['dingding']  = 'Fügen Sie einen eigenen bot in dingding ein um die Adresse des Webhooks zu erhalten.';
$lang->webhook->note->typeList['default']   = 'Webhookadresse on anderen erhalten.';

$lang->webhook->error = new stdclass();
$lang->webhook->error->curl = 'Laden Sie php-curl in der php.ini.';
