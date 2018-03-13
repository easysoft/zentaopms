<?php
$config->entry->create = new stdclass();
$config->entry->create->requiredFields = 'name, code, key';

$config->entry->edit = new stdclass();
$config->entry->edit->requiredFields = 'name, code, key';

$config->entry->help = 'http://www.zentao.net/book/zentaopmshelp/integration-287.html';

$config->entry->errcode['PARAM_CODE_MISSING']    = 401;
$config->entry->errcode['PARAM_TOKEN_MISSING']   = 401;
$config->entry->errcode['SESSION_CODE_MISSING']  = 401;
$config->entry->errcode['EMPTY_KEY']             = 401;
$config->entry->errcode['INVALID_TOKEN']         = 401;
$config->entry->errcode['SESSION_VERIFY_FAILED'] = 401;
$config->entry->errcode['IP_DENIED']             = 403;
$config->entry->errcode['EMPTY_ENTRY']           = 404;
