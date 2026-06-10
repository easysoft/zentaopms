<?php
$config->provider->create = new stdclass();
$config->provider->create->requiredFields = 'type,name,url';

$config->provider->edit = new stdclass();
$config->provider->edit->requiredFields = 'name,url';
