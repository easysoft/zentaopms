<?php
$config->gitlab->create = new stdclass();
$config->gitlab->edit   = new stdclass();

$config->gitlab->create->requiredFields = 'name,url';
$config->gitlab->edit->requiredFields   = 'name,url';
