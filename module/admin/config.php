<?php
$config->admin->apiRoot = 'https://www.zentao.net';

$config->admin->log = new stdclass();
$config->admin->log->saveDays = 30;

if(!isset($config->safe))       $config->safe = new stdclass();
if(!isset($config->safe->weak)) $config->safe->weak = '123456,password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123';

$config->admin->menuGroup['setting']   = array('custom|mode', 'backup', 'action|trash', 'admin|xuanxuan', 'admin|license', 'admin|checkweak', 'admin|resetpwdsetting', 'admin|safe', 'custom|timezone', 'search|buildindex', 'admin|tableengine', 'ldap', 'custom|libreoffice');
$config->admin->menuGroup['user']      = array('dept', 'company', 'user', 'group');
$config->admin->menuGroup['switch']    = array('admin|setmodule');
$config->admin->menuGroup['model']     = array('auditcl', 'stage', 'design', 'cmcl', 'reviewcl', 'custom|required', 'custom|set', 'custom|flow', 'custom|code', 'custom|estimate', 'subject', 'process');
$config->admin->menuGroup['feature']   = array('custom|set', 'custom|product', 'custom|execution', 'custom|required', 'custom|kanban', 'approvalflow', 'measurement', 'meetingroom', 'custom|browsestoryconcept', 'custom|kanban');
$config->admin->menuGroup['template']  = array('custom|set', 'baseline');
$config->admin->menuGroup['message']   = array('mail', 'webhook', 'sms', 'message');
$config->admin->menuGroup['dev']       = array('dev', 'entry');
$config->admin->menuGroup['extension'] = array('extension');
$config->admin->menuGroup['convert']   = array('convert');

$config->admin->menuModuleGroup['model']['custom|set']        = array('project', 'issue', 'risk', 'opportunity', 'nc');
$config->admin->menuModuleGroup['model']['custom|required']   = array('project', 'build');
$config->admin->menuModuleGroup['feature']['custom|set']      = array('todo', 'feedback', 'user', 'block', 'story', 'task', 'bug', 'testcase', 'testtask', 'feedback', 'user');
$config->admin->menuModuleGroup['feature']['custom|required'] = array('bug', 'doc', 'product', 'story', 'productplan', 'release', 'task', 'bug', 'testcase', 'testsuite', 'testtask', 'testreport', 'caselib', 'doc', 'feedback', 'user');
$config->admin->menuModuleGroup['template']['custom|set']     = array('baseline');
