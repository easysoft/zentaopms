<?php
$config->user = new stdclass();
$config->user->create = new stdclass();
$config->user->edit   = new stdclass();

$config->user->create->requiredFields = 'account,realname,password,password1,password2';
$config->user->edit->requiredFields   = 'account,realname';

$config->user->customBatchEditFields = 'dept,role,join,email,commiter,skype,qq,yahoo,gtalk,wangwang,mobile,phone,address,zipcode';

$config->user->custom = new stdclass();
$config->user->custom->batchedit = 'dept,role,join,email,commiter';

$config->user->failTimes   = 6;
$config->user->lockMinutes = 10;
$config->user->batchCreate = 10;
