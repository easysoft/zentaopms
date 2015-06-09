<?php
$config->cron = new stdclass();
$config->cron->create = new stdclass();
$config->cron->edit   = new stdclass();
$config->cron->create->requiredFields = 'm,h,dom,mon,dow,command';
$config->cron->edit->requiredFields   = 'm,h,dom,mon,dow,command';

$config->cron->maxRunDays = 8;
$config->cron->maxRunTime = 65;
