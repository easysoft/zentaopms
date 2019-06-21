<?php
$lang->entry->common  = 'Entry';
$lang->entry->list    = 'Entry List';
$lang->entry->api     = 'API';
$lang->entry->webhook = 'Webhook';
$lang->entry->log     = 'Log';
$lang->entry->setting = 'Settings';

$lang->entry->browse    = 'Browse';
$lang->entry->create    = 'Create Entry';
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
$lang->entry->createdBy   = 'Created By';
$lang->entry->createdDate = 'Created';
$lang->entry->editedby    = 'Edited By';
$lang->entry->editedDate  = 'Edited';
$lang->entry->date        = 'Requesting Time';
$lang->entry->url         = 'Requesting URL';

$lang->entry->confirmDelete = 'Do you want to delete this entry?';
$lang->entry->help          = 'Help';
$lang->entry->notify        = 'Notify';

$lang->entry->note = new stdClass();
$lang->entry->note->name    = 'Name';
$lang->entry->note->code    = 'Alias should be letters and numbers';
$lang->entry->note->ip      = "Use comma to seperate IPs. IP segment is supported, e.g. 192.168.1.*";
$lang->entry->note->allIP   = 'All IPs';
$lang->entry->note->account = 'Entry Account';

$lang->entry->freePasswdList[0] = 'Off';
$lang->entry->freePasswdList[1] = 'On';

$lang->entry->errmsg['PARAM_CODE_MISSING']    = 'Parameter code is missing.';
$lang->entry->errmsg['PARAM_TOKEN_MISSING']   = 'Parameter token is missing.';
$lang->entry->errmsg['SESSION_CODE_MISSING']  = 'Session code is missing.';
$lang->entry->errmsg['EMPTY_KEY']             = 'Secret key is missing.';
$lang->entry->errmsg['INVALID_TOKEN']         = 'Invalid token.';
$lang->entry->errmsg['SESSION_VERIFY_FAILED'] = 'Session verification failed.';
$lang->entry->errmsg['IP_DENIED']             = 'IP is denied.';
$lang->entry->errmsg['ACCOUNT_UNBOUND']       = 'Account is not bound.';
$lang->entry->errmsg['INVALID_ACCOUNT']       = 'Invalid account.';
$lang->entry->errmsg['EMPTY_ENTRY']           = 'Entry does not exist.';
$lang->entry->errmsg['CALLED_TIME']           = 'Token has expired';
