<?php
$config->account->require = new stdclass;
$config->account->require->create = 'name,account,provider';
$config->account->require->edit   = 'name,account,provider';

$config->account->search['module'] = 'account';
