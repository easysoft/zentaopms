<?php
$config->user->create->requiredFields = 'account,realname,password,password1,password2';
$config->user->edit->requiredFields   = 'account,realname';
$config->user->failTimes = 5;
$config->user->lockMinutes = 10;
