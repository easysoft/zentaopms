<?php
$config->user = new stdclass();
$config->user->create = new stdclass();
$config->user->edit   = new stdclass();

$config->user->create->requiredFields = 'account,realname,password,password1,password2,role';
$config->user->edit->requiredFields   = 'account,realname,role';
$config->user->failTimes   = 6;
$config->user->lockMinutes = 10;
$config->user->batchCreate = 10;
