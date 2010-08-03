<?php
$config->task->create->requiredFields   = 'name,estimate,type,pri';
$config->task->edit->requiredFields     = $config->task->create->requiredFields;
$config->task->start->requiredFields    = 'estimate';
$config->task->complete->requiredFields = $config->task->start->requiredFields;
