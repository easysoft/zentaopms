<?php
$config->entry->create = new stdclass();
$config->entry->create->requiredFields = 'name, code, account, key';

$config->entry->edit = new stdclass();
$config->entry->edit->requiredFields = 'name, code, account, key';

$config->entry->errcode['PARAM_CODE_MISSING']    = 401;
$config->entry->errcode['PARAM_TOKEN_MISSING']   = 401;
$config->entry->errcode['SESSION_CODE_MISSING']  = 401;
$config->entry->errcode['EMPTY_KEY']             = 401;
$config->entry->errcode['INVALID_TOKEN']         = 401;
$config->entry->errcode['SESSION_VERIFY_FAILED'] = 401;
$config->entry->errcode['IP_DENIED']             = 403;
$config->entry->errcode['ACCOUNT_UNBOUND']       = 403;
$config->entry->errcode['EMPTY_ENTRY']           = 404;
$config->entry->errcode['CALLED_TIME']           = 405;
$config->entry->errcode['INVALID_ACCOUNT']       = 406;
$config->entry->errcode['ERROR_TIMESTAMP']       = 407;
