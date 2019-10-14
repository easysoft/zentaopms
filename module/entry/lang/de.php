<?php
$lang->entry->common  = 'Eintrag';
$lang->entry->list    = 'Eintragliste';
$lang->entry->api     = 'API';
$lang->entry->webhook = 'Webhook';
$lang->entry->log     = 'Log';
$lang->entry->setting = 'Einstellungen';

$lang->entry->browse    = 'Durchsuchen';
$lang->entry->create    = 'Erstellen';
$lang->entry->edit      = 'Bearbeiten';
$lang->entry->delete    = 'Löschen';
$lang->entry->createKey = 'Generieren';

$lang->entry->id          = 'ID';
$lang->entry->name        = 'Name';
$lang->entry->account     = 'Account';
$lang->entry->code        = 'Code';
$lang->entry->freePasswd  = 'Secret-free login';
$lang->entry->key         = 'Key';
$lang->entry->ip          = 'IP';
$lang->entry->desc        = 'Beschreibung';
$lang->entry->createdBy   = 'Erstellt von';
$lang->entry->createdDate = 'Erstellt am';
$lang->entry->editedby    = 'Bearbeitet von';
$lang->entry->editedDate  = 'Bearbeiten am';
$lang->entry->date        = 'Request Zeit';
$lang->entry->url         = 'Request URL';

$lang->entry->confirmDelete = 'Möchten Sie diesen Eintrag löschen?';
$lang->entry->help          = 'Hilfe';
$lang->entry->notify        = 'Notification';

$lang->entry->helpLink   = 'https://www.zentao.pm/book/zentaomanual/scrum-tool-open-source-integrate-third-party-application-221.html';
$lang->entry->notifyLink = 'https://www.zentao.net/book/zentaopmshelp/301.html';

$lang->entry->note = new stdClass();
$lang->entry->note->name    = 'Name';
$lang->entry->note->code    = 'Code, should be english and number.';
$lang->entry->note->ip      = "Benutzen Sie eine Komma zwischen den IPs. IP Segmente werden unterstützt, z.B. 192.168.1.*";
$lang->entry->note->allIP   = 'Alle';
$lang->entry->note->account = 'Application Account';

$lang->entry->freePasswdList[1] = 'On';
$lang->entry->freePasswdList[0] = 'Off';

$lang->entry->errmsg['PARAM_CODE_MISSING']    = 'Parameter code fehlt.';
$lang->entry->errmsg['PARAM_TOKEN_MISSING']   = 'Parameter token fehlt.';
$lang->entry->errmsg['SESSION_CODE_MISSING']  = 'Session code fehlt.';
$lang->entry->errmsg['EMPTY_KEY']             = 'Key des Eintrags fehlt.';
$lang->entry->errmsg['INVALID_TOKEN']         = 'Ungültiger token.';
$lang->entry->errmsg['SESSION_VERIFY_FAILED'] = 'Session überprüfung fehlgeschlagen.';
$lang->entry->errmsg['IP_DENIED']             = 'IP ist unzulässig.';
$lang->entry->errmsg['ACCOUNT_UNBOUND']       = 'Account is not bound.';
$lang->entry->errmsg['INVALID_ACCOUNT']       = 'Account is not exists';
$lang->entry->errmsg['EMPTY_ENTRY']           = 'Key des Eintrags fehlt.';
$lang->entry->errmsg['CALLED_TIME']           = 'Token has expired';
$lang->entry->errmsg['ERROR_TIMESTAMP']       = 'Timestamp Error';
