<?php
$lang->entry->common  = 'Application';
$lang->entry->list    = 'Applications';
$lang->entry->api     = 'API';
$lang->entry->webhook = 'Webhook';
$lang->entry->log     = 'Log';
$lang->entry->setting = 'Settings';

$lang->entry->browse    = 'Browse';
$lang->entry->create    = 'Add Application';
$lang->entry->edit      = 'Edit';
$lang->entry->delete    = 'Delete';
$lang->entry->createKey = 'Regenerate Secret Key';

$lang->entry->id          = 'ID';
$lang->entry->name        = 'Name';
$lang->entry->account     = 'Account';
$lang->entry->code        = 'Code';
$lang->entry->freePasswd  = 'Free Password Login';
$lang->entry->key         = 'Key';
$lang->entry->ip          = 'IP';
$lang->entry->desc        = 'Description';
$lang->entry->createdBy   = 'CreatedBy';
$lang->entry->createdDate = 'CreateDate';
$lang->entry->editedby    = 'EditedBy';
$lang->entry->editedDate  = 'EditedDate';
$lang->entry->date        = 'Requesting Time';
$lang->entry->url         = 'Requesting URL';

$lang->entry->confirmDelete = 'Do you want to delete this entry?';
$lang->entry->help          = 'Help';
$lang->entry->notify        = 'Notification';

$lang->entry->helpLink   = 'https://www.zentao.pm/book/zentaomanual/scrum-tool-open-source-integrate-third-party-application-221.html';
$lang->entry->notifyLink = 'https://www.zentao.net/book/zentaopmshelp/301.html';

$lang->entry->note = new stdClass();
$lang->entry->note->name    = 'Name';
$lang->entry->note->code    = 'Code should be letters and numbers';
$lang->entry->note->ip      = "Use comma to seperate IPs. IP segment is supported, e.g. 192.168.1.*";
$lang->entry->note->allIP   = 'All IPs';
$lang->entry->note->account = 'Application Account';

$lang->entry->freePasswdList[1] = 'On';
$lang->entry->freePasswdList[0] = 'Off';

$lang->entry->errmsg['PARAM_CODE_MISSING']    = 'Parameter code is missing.';
$lang->entry->errmsg['PARAM_TOKEN_MISSING']   = 'Parameter token is missing.';
$lang->entry->errmsg['SESSION_CODE_MISSING']  = 'Session code is missing.';
$lang->entry->errmsg['EMPTY_KEY']             = 'Secret key is missing.';
$lang->entry->errmsg['INVALID_TOKEN']         = 'Invalid token.';
$lang->entry->errmsg['SESSION_VERIFY_FAILED'] = 'Session verification failed.';
$lang->entry->errmsg['IP_DENIED']             = 'IP is denied.';
$lang->entry->errmsg['ACCOUNT_UNBOUND']       = 'Account is not bound.';
$lang->entry->errmsg['INVALID_ACCOUNT']       = 'Invalid account.';
$lang->entry->errmsg['EMPTY_ENTRY']           = 'Application does not exist.';
$lang->entry->errmsg['CALLED_TIME']           = 'Token has expired';
$lang->entry->errmsg['ERROR_TIMESTAMP']       = 'Timestamp Error';
