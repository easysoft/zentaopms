<?php
$lang->entry->common  = 'Application';
$lang->entry->list    = 'Application List';
$lang->entry->api     = 'API';
$lang->entry->webhook = 'Webhook';
$lang->entry->log     = 'Effort';
$lang->entry->setting = 'Settings';

$lang->entry->browse    = 'View Application';
$lang->entry->create    = 'Add Application';
$lang->entry->edit      = 'Edit Application';
$lang->entry->delete    = 'Delete Application';
$lang->entry->createKey = 'Regenerate Key';

$lang->entry->id          = 'ID';
$lang->entry->name        = 'Name';
$lang->entry->account     = 'Account';
$lang->entry->code        = 'Code';
$lang->entry->freePasswd  = 'Passwordless Login';
$lang->entry->key         = 'Key';
$lang->entry->ip          = 'IP';
$lang->entry->desc        = 'Description';
$lang->entry->createdBy   = 'Creator';
$lang->entry->createdDate = 'Created on';
$lang->entry->editedby    = 'Last Edited by';
$lang->entry->editedDate  = 'Edited on';
$lang->entry->date        = 'Requested on';
$lang->entry->url         = 'Request URL';
$lang->entry->calledTime  = 'Call Time';
$lang->entry->deleted     = 'Deleted';

$lang->entry->confirmDelete = 'Are you sure you want to delete this application?';
$lang->entry->help          = 'Help';
$lang->entry->notify        = 'Notification';

$lang->entry->helpLink   = 'https://www.zentao.net/book/zentaopmshelp/integration-287.html';
$lang->entry->notifyLink = 'https://www.zentao.net/book/zentaopmshelp/301.html';

$lang->entry->summaryTip = '%s applications on this page.';

$lang->entry->note = new stdClass();
$lang->entry->note->name    = 'Authorized App Name';
$lang->entry->note->code    = 'Authorized App code must be alphanumeric.';
$lang->entry->note->ip      = "Allowed IPs (Separate with commas. Wildcards like 192.168.1. are supported).";
$lang->entry->note->allIP   = 'No Restrictions';
$lang->entry->note->account = 'Authorized App Account';

$lang->entry->freePasswdList[1] = 'Enable';
$lang->entry->freePasswdList[0] = 'Disable';

$lang->entry->errmsg['PARAM_CODE_MISSING']    = 'Missing code parameter.';
$lang->entry->errmsg['PARAM_TOKEN_MISSING']   = 'Missing token parameter.';
$lang->entry->errmsg['SESSION_CODE_MISSING']  = 'Missing session code.';
$lang->entry->errmsg['EMPTY_KEY']             = 'App key not set.';
$lang->entry->errmsg['INVALID_TOKEN']         = 'Invalid token.';
$lang->entry->errmsg['SESSION_VERIFY_FAILED'] = 'Session verification failed.';
$lang->entry->errmsg['IP_DENIED']             = 'Access denied for this IP.';
$lang->entry->errmsg['ACCOUNT_UNBOUND']       = 'No user linked.';
$lang->entry->errmsg['INVALID_ACCOUNT']       = 'User does not exist.';
$lang->entry->errmsg['EMPTY_ENTRY']           = 'App does not exist.';
$lang->entry->errmsg['CALLED_TIME']           = 'Token has expired.';
$lang->entry->errmsg['ERROR_TIMESTAMP']       = 'Invalid timestamp.';
