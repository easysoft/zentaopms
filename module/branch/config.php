<?php
$config->branch = new stdclass();
$config->branch->create = new stdclass();
$config->branch->edit   = new stdclass();

$config->branch->create->requiredFields = 'name';
$config->branch->edit->requiredFields   = 'name,status';
